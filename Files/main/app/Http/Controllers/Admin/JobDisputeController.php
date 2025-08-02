<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\AssignedJob;
use App\Models\JobConversation;
use App\Models\JobPost;
use App\Models\Transaction;
use Exception;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\Response;

class JobDisputeController extends Controller
{
    function disputes(JobPost $job) {
        $pageTitle = "Disputes for '$job->title'";
        $disputes  = $job->assignedJobs()
            ->disputed()
            ->with('disputant')
            ->latest()
            ->paginate(getPaginate());

        return view('admin.job.disputes', compact('pageTitle', 'disputes', 'job'));
    }

    function disputeDetails(JobPost $job, int $id) {
        $pageTitle     = 'Dispute Details';
        $dispute       = AssignedJob::with(['userAssignedBy', 'userAssignedTo', 'disputant'])->findOrFail($id);
        $conversations = $this->getJobConversations($dispute);

        return view('admin.job.disputeShow', compact('pageTitle', 'job', 'dispute', 'conversations'));
    }

    function sendMessage() {
        $assignedJob = AssignedJob::disputed()->find(request('id'));

        if (!$assignedJob) {
            return response()->json(['error' => 'No record found'], Response::HTTP_NOT_FOUND);
        }

        $this->validate(request(), [
            'message' => 'required_without:file|nullable|string',
            'file'    => ['nullable', File::types(['png', 'jpg', 'jpeg', 'pdf']), 'max:2000'],
        ], [
            'file.max' => 'The file must not be greater than 2 megabytes.',
        ]);

        if (request()->hasFile('file')) {
            try {
                $uploadedFile     = fileUploader(request()->file('file'), getFilePath('jobConversationFile'));
                $fileOriginalName = request()->file('file')->getClientOriginalName();
            } catch (Exception $e) {
                return response()->json(['error' => 'File upload failed'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $jobConversation                     = new JobConversation();
        $jobConversation->assigned_job_id    = $assignedJob->id;
        $jobConversation->sender_id          = auth('admin')->id();
        $jobConversation->is_admin           = true;
        $jobConversation->message            = request('message') ?? null;
        $jobConversation->file               = $uploadedFile ?? null;
        $jobConversation->file_original_name = $fileOriginalName ?? null;
        $jobConversation->save();

        $conversations = $this->getJobConversations($assignedJob);

        return response()->json([
            'html' => view('admin.partials.jobConversations', compact('conversations'))->render(),
        ]);
    }

    function downloadFile(JobConversation $conversation) {
        $filePath = getFilePath('jobConversationFile') . '/' . $conversation->file;

        if (file_exists($filePath)) {
            return response()->download($filePath, $conversation->file_original_name);
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    function fetchMessage() {
        $assignedJob = AssignedJob::find(request('id'));

        if (!$assignedJob) {
            return response()->json(['error' => 'No record found'], Response::HTTP_NOT_FOUND);
        }

        $conversations = $this->getJobConversations($assignedJob);

        return response()->json([
            'html' => view('admin.partials.jobConversations', compact('conversations'))->render(),
        ]);
    }

    function takeAction(int $id) {
        $assignedJob = AssignedJob::with([
            'job:id,job_code,title,quantity,rate',
            'userAssignedBy',
            'userAssignedTo'
        ])
            ->disputed()
            ->findOrFail($id);

        $this->validate(request(), [
            'author_amount'     => 'required|numeric|gte:0',
            'freelancer_amount' => 'required|numeric|gte:0',
        ]);

        $job                    = $assignedJob->job;
        $freelancerCompensation = $job->quantity * $job->rate;

        if ($freelancerCompensation != request('author_amount') + request('freelancer_amount')) {
            $toast[] = ['error', 'The total of the entered amounts must be equal to the freelancer\'s compensation'];

            return back()->with('toasts', $toast);
        }

        // update the balance of job author & freelancer
        $author       = $assignedJob->userAssignedBy;
        $freelancer   = $assignedJob->userAssignedTo;
        $trx          = getTrx();
        $authorAmount = $freelancerAmount = 0;

        if (request()->filled('author_amount')) {
            $authorAmount = request('author_amount');
            $author->increment('balance', $authorAmount);
            $freelancer->refresh();

            // create transaction
            $transaction               = new Transaction();
            $transaction->user_id      = $author->id;
            $transaction->amount       = $authorAmount;
            $transaction->post_balance = $author->balance;
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->details      = 'An amount of ' . showAmount($authorAmount) . ' ' . bs('site_cur') . ' has been added to the balance for settling the disputed job';
            $transaction->remark       = 'disputed_job_settlement';
            $transaction->save();
        }

        if (request()->filled('freelancer_amount')) {
            $freelancerAmount = request('freelancer_amount');
            $freelancer->increment('balance', $freelancerAmount);
            $freelancer->refresh();

            // create transaction
            $transaction               = new Transaction();
            $transaction->user_id      = $freelancer->id;
            $transaction->amount       = $freelancerAmount;
            $transaction->post_balance = $freelancer->balance;
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->details      = 'An amount of ' . showAmount($freelancerAmount) . ' ' . bs('site_cur') . ' has been added to the balance for settling the disputed job';
            $transaction->remark       = 'disputed_job_settlement';
            $transaction->save();
        }

        // update data in the db
        $assignedJob->status                    = ManageStatus::ASSIGNED_JOB_SETTLED;
        $assignedJob->settled_author_amount     = $authorAmount;
        $assignedJob->settled_freelancer_amount = $freelancerAmount;
        $assignedJob->settled_at                = now();
        $assignedJob->save();

        // notify both author & freelancer
        $shortCodes = [
            'job_code'          => $job->job_code,
            'title'             => $job->title,
            'amount'            => showAmount($freelancerCompensation),
            'author_amount'     => showAmount($authorAmount),
            'freelancer_amount' => showAmount($freelancerAmount),
            'trx'               => $trx,
        ];

        notify($author, 'DISPUTED_JOB_TAKE_ACTION', $shortCodes);
        notify($freelancer, 'DISPUTED_JOB_TAKE_ACTION', $shortCodes);

        $toast[] = ['success', 'The action on the disputed job has been successfully taken'];

        return back()->with('toasts', $toast);
    }

    private function getJobConversations(AssignedJob $assignedJob) {
        return $assignedJob->conversations()->orderBy('created_at')->get();
    }
}
