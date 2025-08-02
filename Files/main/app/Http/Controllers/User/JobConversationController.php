<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\AssignedJob;
use App\Models\JobConversation;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\Response;

class JobConversationController extends Controller
{
    function sendMessage() {
        $assignedJob = AssignedJob::where(function (Builder $query) {
            $query->where('status', ManageStatus::ASSIGNED_JOB_IN_PROGRESS)
                ->orWhere('status', ManageStatus::ASSIGNED_JOB_DISPUTED);
        })->find(request('id'));

        if (!$assignedJob) {
            return response()->json(['error' => 'No record found'], Response::HTTP_NOT_FOUND);
        }

        $response = Gate::inspect('communicate', [JobConversation::class, $assignedJob]);

        if ($response->denied()) {
            return response()->json(['error' => $response->message()], Response::HTTP_UNAUTHORIZED);
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
        $jobConversation->sender_id          = auth()->id();
        $jobConversation->message            = request('message') ?? null;
        $jobConversation->file               = $uploadedFile ?? null;
        $jobConversation->file_original_name = $fileOriginalName ?? null;
        $jobConversation->save();

        $conversations = $this->getJobConversations($assignedJob);

        return response()->json([
            'html' => view($this->activeTheme . 'partials.jobConversations', compact('conversations'))->render(),
        ]);
    }

    function downloadFile(JobConversation $conversation) {
        $conversation->load('assignedJob');

        $response = Gate::inspect('download', $conversation);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $filePath = getFilePath('jobConversationFile') . '/' . $conversation->file;

        if (file_exists($filePath)) {
            return response()->download($filePath, $conversation->file_original_name);
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    function fetchMessage() {
        $assignedJob = AssignedJob::where(function (Builder $query) {
            $query->where('status', ManageStatus::ASSIGNED_JOB_IN_PROGRESS)
                ->orWhere('status', ManageStatus::ASSIGNED_JOB_DISPUTED);
        })->find(request('id'));

        if (!$assignedJob) {
            return response()->json(['error' => 'No record found'], Response::HTTP_NOT_FOUND);
        }

        $conversations = $this->getJobConversations($assignedJob);

        return response()->json([
            'html' => view($this->activeTheme . 'partials.jobConversations', compact('conversations'))->render(),
        ]);
    }

    private function getJobConversations(AssignedJob $assignedJob)
    {
        return $assignedJob->conversations()->orderBy('created_at')->get();
    }
}
