<?php

namespace App\Http\Controllers\User;

use App\Models\AdminNotification;
use App\Models\AssignedJob;
use App\Models\Form;
use App\Lib\FormProcessor;
use App\Models\GatewayCurrency;
use App\Models\Transaction;
use App\Constants\ManageStatus;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    function home() {
        $pageTitle    = 'Dashboard';
        $user         = auth()->user();
        $kycContent   = $user->kc == ManageStatus::UNVERIFIED || $user->kc == ManageStatus::PENDING
            ? getSiteData('kyc.content', true)
            : null;

        // count job-posts and completed-jobs
        $user->loadCount([
            'jobs as job_posts_count'              => fn($query) => $query->approved(),
            'assignedJobs as completed_jobs_count' => fn($query) => $query->completed(),
        ]);

        $user->load(['assignedJobs.job', 'deposits', 'withdrawals']);

        // calculate total earnings for completed assigned jobs
        $totalEarning = $user->assignedJobs->reduce(function (float $carry, AssignedJob $assignedJob) {
            if ($assignedJob->status == ManageStatus::ASSIGNED_JOB_COMPLETED) {
                return $carry + ($assignedJob->job->quantity * $assignedJob->job->rate);
            } elseif ($assignedJob->status == ManageStatus::ASSIGNED_JOB_SETTLED) {
                return $carry + $assignedJob->settled_freelancer_amount;
            }

            return $carry;
        }, 0);

        // average rating of the freelancer
        $user->loadAvg('freelancerReviews as average_rating', 'rating');

        // the deposit & withdrawal amounts of the user
        $depositAmount    = $user->deposits()->done()->sum('amount');
        $withdrawalAmount = $user->withdrawals()->done()->sum('amount');

        // count transactions
        $user->loadCount('transactions');

        // monthly completed-jobs graph
        $completedJobsReport = collect([]);

        $monthWiseCompletedJobs = $user->assignedJobs()
            ->where('status', ManageStatus::ASSIGNED_JOB_COMPLETED)
            ->whereYear('completed_at', date('Y'))
            ->selectRaw('date_format(completed_at, "%M") as month, count(*) as total_completed_jobs')
            ->groupBy('month')
            ->get();

        for ($i = 1; $i <= 12; $i++) {
            $monthName    = Carbon::create()->month($i)->format('F');
            $completedJob = $monthWiseCompletedJobs->firstWhere('month', $monthName);

            if ($completedJob) $completedJobsReport->push((int) $completedJob->total_completed_jobs);
            else $completedJobsReport->push(0);
        }

        $completedJobs = $completedJobsReport->toArray();

        // recent earnings
        $recentEarnings = $user->assignedJobs->filter(function (AssignedJob $assignedJob) {
            return in_array($assignedJob->status, [
                ManageStatus::ASSIGNED_JOB_COMPLETED,
                ManageStatus::ASSIGNED_JOB_SETTLED,
            ]);
        });

        return view($this->activeTheme . 'user.page.dashboard', compact('pageTitle', 'kycContent', 'user', 'totalEarning', 'depositAmount', 'withdrawalAmount', 'completedJobs', 'recentEarnings'));
    }

    function kycForm() {
        if (auth()->user()->kc == ManageStatus::PENDING) {
            $toast[] = ['warning', 'Your identity verification is being processed'];

            return back()->with('toasts', $toast);
        }

        if (auth()->user()->kc == ManageStatus::VERIFIED) {
            $toast[] = ['success', 'Your identity verification is being succeed'];

            return back()->with('toasts', $toast);
        }

        $pageTitle = 'Identification Form';
        $user      = auth()->user();

        return view($this->activeTheme . 'user.kyc.form', compact('pageTitle', 'user'));
    }

    function kycSubmit() {
        $form           = Form::where('act', 'kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        request()->validate($validationRule);

        $userData       = $formProcessor->processFormData(request(), $formData);
        $user           = auth()->user();
        $user->kyc_data = $userData;
        $user->kc       = ManageStatus::PENDING;
        $user->save();

        $toast[] = ['success', 'Your identity verification information has been submitted'];

        return to_route('user.home')->with('toasts', $toast);
    }

    function kycData() {
        $pageTitle = 'Identification Information';
        $user      = auth()->user();

        return view($this->activeTheme . 'user.kyc.info', compact('pageTitle', 'user'));
    }

    function profile() {
        $pageTitle = 'Profile Settings';
        $user      = auth()->user();

        return view($this->activeTheme . 'user.page.profile', compact('pageTitle', 'user'));
    }

    function profileUpdate() {
        $this->validate(request(), [
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
            'image'     => ['nullable', File::types(['png', 'jpg', 'jpeg'])],
        ], [
            'firstname.required' => 'First name field is required',
            'lastname.required'  => 'Last name field is required',
        ]);

        $user = auth()->user();

        if (request()->hasFile('image')) {
            try {
                $user->image = fileUploader(request('image'), getFilePath('userProfile'), getFileSize('userProfile'), $user->image);
            } catch (Exception) {
                $toast[] = ['error', 'Image uploading process has failed'];

                return back()->with('toasts', $toast);
            }
        }

        $user->firstname = request('firstname');
        $user->lastname  = request('lastname');

        $user->address = [
            'state'   => request('state'),
            'zip'     => request('zip'),
            'city'    => request('city'),
            'address' => request('address'),
        ];

        $user->save();

        $toast[] = ['success', 'Your profile has updated'];

        return back()->with('toasts', $toast);
    }

    function freelancerProfile() {
        $pageTitle = 'Freelancer Profile';
        $user      = auth()->user();

        return view($this->activeTheme . 'user.page.freelancerProfile', compact('pageTitle', 'user'));
    }

    function freelancerProfileUpdate() {
        $rules = [
            'role'     => 'required|string|max:255',
            'skills'   => 'required|array',
            'skills.*' => 'required|string',
            'bio'      => 'required|string|min:30',
        ];

        $form           = Form::where('act', 'kyf')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $allRules       = array_merge($rules, $validationRule);

        $this->validate(request(), $allRules);

        $freelancerData = $formProcessor->processFormData(request(), $formData);

        $freelancer                        = auth()->user();
        $freelancer->kyf_data              = $freelancerData;
        $freelancer->freelancer_status     = ManageStatus::FREELANCER_PENDING;
        $freelancer->role                  = request('role');
        $freelancer->skills                = request('skills');
        $freelancer->bio                   = request('bio');
        $freelancer->freelancer_applied_at = now();
        $freelancer->save();

        // notify admin
        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $freelancer->id;
        $adminNotification->title     = "$freelancer->fullname has requested to register as a freelancer";
        $adminNotification->click_url = urlPath('admin.freelancer.pending');
        $adminNotification->save();

        $toast[] = ['success', 'Your freelancing verification information has been successfully submitted'];

        return back()->with('toasts', $toast);
    }

    function password() {
        $pageTitle             = 'Change Password';
        $changePasswordContent = getSiteData('change_password.content', true);
        $user                  = auth()->user();

        return view($this->activeTheme . 'user.page.password', compact('pageTitle', 'changePasswordContent', 'user'));
    }

    function passwordChange() {
        $passwordValidation = Password::min(6);

        if (bs('strong_pass')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate(request(), [
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', $passwordValidation],
        ]);

        $user = auth()->user();

        if (!Hash::check(request('current_password'), $user->password)) {
            $toast[] = ['error', 'Current password mismatched!'];

            return back()->with('toasts', $toast);
        }

        $user->password = Hash::make(request('password'));
        $user->save();

        $toast[] = ['success', 'Your password has changed'];

        return back()->with('toasts', $toast);
    }

    function show2faForm() {
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . bs('site_name'), $secret);
        $pageTitle = '2FA Settings';

        return view($this->activeTheme . 'user.page.twoFactor', compact('pageTitle', 'secret', 'qrCodeUrl', 'user'));
    }

    function enable2fa() {
        $user = auth()->user();

        $this->validate(request(), [
            'key'    => 'required',
            'code'   => 'required|array|min:6',
            'code.*' => 'required|integer',
        ]);

        $verCode  = (int)(implode("", request('code')));
        $response = verifyG2fa($user, $verCode, request('key'));

        if ($response) {
            $user->tsc = request('key');
            $user->ts  = ManageStatus::YES;
            $user->save();

            $toast[] = ['success', 'Two factor authenticator successfully activated'];
        } else {
            $toast[] = ['error', 'Wrong verification code'];
        }

        return back()->with('toasts', $toast);
    }

    function disable2fa() {
        $this->validate(request(), [
            'code'   => 'required|array|min:6',
            'code.*' => 'required|integer',
        ]);

        $verCode  = (int)(implode("", request('code')));
        $user     = auth()->user();
        $response = verifyG2fa($user, $verCode);

        if ($response) {
            $user->tsc = null;
            $user->ts  = ManageStatus::NO;
            $user->save();

            $toast[] = ['success', 'Two factor authenticator successfully deactivated'];
        } else {
            $toast[] = ['error', 'Wrong verification code'];
        }

        return back()->with('toasts', $toast);
    }

    function deposit() {
        $pageTitle         = 'Deposit Money';
        $user              = auth()->user();
        $gatewayCurrencies = GatewayCurrency::whereHas('method', fn ($gateway) => $gateway->active())
            ->with('method')
            ->orderby('method_code')
            ->get();

        return view($this->activeTheme . 'user.deposit.index', compact('pageTitle', 'user', 'gatewayCurrencies'));
    }

    function depositHistory() {
        $pageTitle = 'Deposit History';
        $user      = auth()->user();
        $deposits  = $user->deposits()
            ->with('gateway')
            ->searchable(['trx'])
            ->index()
            ->latest()
            ->paginate(getPaginate());

        return view($this->activeTheme . 'user.deposit.history', compact('pageTitle', 'deposits', 'user'));
    }

    function transactions() {
        $pageTitle    = 'Transactions';
        $user         = auth()->user();
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = $user->transactions()
            ->searchable(['trx'])
            ->filter(['trx_type', 'remark'])
            ->latest()
            ->paginate(getPaginate());

        return view($this->activeTheme . 'user.page.transactions', compact('pageTitle', 'transactions', 'remarks', 'user'));
    }

    function fileDownload() {
        $path = request('filePath');
        $file = fileManager()->$path()->path . '/' . request('fileName');

        return response()->download($file);
    }
}
