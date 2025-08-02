<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\AssignedJob;
use App\Models\User;

class FreelancerController extends Controller
{
    function index() {
        $pageTitle   = "All Freelancers";
        $freelancers = $this->freelancerData();

        return view('admin.page.freelancers', compact('pageTitle', 'freelancers'));
    }

    function pending() {
        $pageTitle   = "Pending Freelancers";
        $freelancers = $this->freelancerData('pendingFreelancer');

        return view('admin.page.freelancers', compact('pageTitle', 'freelancers'));
    }

    function active() {
        $pageTitle   = "Active Freelancers";
        $freelancers = $this->freelancerData('activeFreelancer');

        return view('admin.page.freelancers', compact('pageTitle', 'freelancers'));
    }

    function rejected() {
        $pageTitle   = "Rejected Freelancers";
        $freelancers = $this->freelancerData('rejectedFreelancer');

        return view('admin.page.freelancers', compact('pageTitle', 'freelancers'));
    }

    function banned() {
        $pageTitle   = "Banned Freelancers";
        $freelancers = $this->freelancerData('bannedFreelancer');

        return view('admin.page.freelancers', compact('pageTitle', 'freelancers'));
    }

    function accept(int $id) {
        $freelancer                    = User::where('id', $id)->active()->firstOrFail();
        $freelancer->freelancer_status = ManageStatus::FREELANCER_ACTIVE;
        $freelancer->save();

        // notify freelancer
        notify($freelancer, 'FREELANCER_APPLICATION_ACCEPT');

        $toast[] = ['success', 'Freelancer application has been successfully accepted'];

        return back()->with('toasts', $toast);
    }

    function reject(int $id) {
        $this->validate(request(), [
            'rejection_reason' => 'required|string|max:255',
        ]);

        $freelancer                              = User::where('id', $id)->active()->firstOrFail();
        $freelancer->freelancer_status           = ManageStatus::FREELANCER_REJECTED;
        $freelancer->freelancer_rejection_reason = request('rejection_reason');
        $freelancer->save();

        // notify freelancer
        notify($freelancer, 'FREELANCER_APPLICATION_REJECT', [
            'reason' => $freelancer->freelancer_rejection_reason,
        ]);

        $toast[] = ['success', 'Freelancer application has been successfully rejected'];

        return back()->with('toasts', $toast);
    }

    function ban(int $id) {
        $this->validate(request(), [
            'ban_reason' => 'required|string|max:255',
        ]);

        $freelancer                        = User::where('id', $id)->active()->activeFreelancer()->firstOrFail();
        $freelancer->freelancer_status     = ManageStatus::FREELANCER_BANNED;
        $freelancer->freelancer_ban_reason = request('ban_reason');
        $freelancer->save();

        // notify freelancer
        notify($freelancer, 'FREELANCER_BAN', [
            'reason' => $freelancer->freelancer_ban_reason,
        ]);

        $toast[] = ['success', 'Freelancer has been successfully banned'];

        return back()->with('toasts', $toast);
    }

    function unban(int $id) {
        $freelancer                        = User::where('id', $id)->active()->bannedFreelancer()->firstOrFail();
        $freelancer->freelancer_status     = ManageStatus::FREELANCER_ACTIVE;
        $freelancer->freelancer_ban_reason = null;
        $freelancer->save();

        // notify freelancer
        notify($freelancer, 'FREELANCER_UNBAN');

        $toast[] = ['success', 'Freelancer has been successfully unbanned'];

        return back()->with('toasts', $toast);
    }

    private function freelancerData(string $scope = null)
    {
        if ($scope) $gigWorker = User::whereNotNull('kyf_data')->$scope();
        else $gigWorker = User::whereNotNull('kyf_data');

        $freelancers = $gigWorker->withCount([
            'jobApplications',
            'assignedJobs as ongoing_jobs_count'   => fn($query) => $query->inProgress(),
            'assignedJobs as completed_jobs_count' => fn($query) => $query->completed(),
            'assignedJobs as disputed_jobs_count'  => fn($query) => $query->disputed(),
            'assignedJobs as settled_jobs_count'   => fn($query) => $query->settled(),
        ])
            ->with('assignedJobs.job')
            ->searchable(['username', 'email'])
            ->dateFilter()
            ->orderByDesc('freelancer_applied_at')
            ->paginate(getPaginate());

        // calculate total earnings
        $freelancers->getCollection()->transform(function($freelancer) {
            $freelancer['total_earning'] = $freelancer->assignedJobs->reduce(function (float $carry, AssignedJob $assignedJob) {
                if ($assignedJob->status == ManageStatus::ASSIGNED_JOB_COMPLETED) {
                    return $carry + ($assignedJob->job->quantity * $assignedJob->job->rate);
                } elseif ($assignedJob->status == ManageStatus::ASSIGNED_JOB_SETTLED) {
                    return $carry + $assignedJob->settled_freelancer_amount;
                }

                return $carry;
            }, 0);

            return $freelancer;
        });

        return $freelancers;
    }
}
