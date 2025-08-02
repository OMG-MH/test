@extends($activeTheme . 'layouts.auth')

@section('auth')
    <table class="table table-borderless table--striped table--responsive--md">
        <thead>
            <tr>
                <th>@lang('S.N.')</th>
                <th>@lang('Job Code')</th>
                <th>@lang('Job Title')</th>
                <th>@lang('Author')</th>
                <th>@lang('Assigned On')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ongoingJobs as $ongoingJob)
                <tr>
                    <td>{{ $ongoingJobs->firstItem() + $loop->index }}</td>
                    <td>{{ $ongoingJob->job->job_code }}</td>
                    <td>
                        <a href="{{ route('job.show', $ongoingJob->job->job_code) }}">
                            {{ __($ongoingJob->job->title) }}
                        </a>
                    </td>
                    <td>{{ __($ongoingJob->userAssignedBy->fullname) }}</td>
                    <td>
                        <span class="d-block">
                            <span class="d-block">{{ showDateTime($ongoingJob->created_at) }}</span>
                            <span class="d-block">{{ diffForHumans($ongoingJob->created_at) }}</span>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.workspace.job.show', $ongoingJob->id) }}" class="btn btn--sm btn--icon btn-outline--info" title="@lang('Details')" data-bs-custom-class="tooltip-sm">
                                <i class="ti ti-device-desktop transform-1"></i>
                            </a>
                            <a href="{{ route('user.workspace.ongoing.job.dispute', $ongoingJob->id) }}" class="btn btn--sm btn--icon btn-outline--danger btn-job-dispute" title="@lang('Mark as Disputed')" data-bs-custom-class="tooltip-sm">
                                <i class="ti ti-exclamation-circle transform-1"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                @include('partials.noData')
            @endforelse
        </tbody>
    </table>

    @if ($ongoingJobs->hasPages())
        {{ $ongoingJobs->links() }}
    @endif
@endsection

@push('user-panel-modal')
    {{-- Job Dispute Modal --}}
    <div class="custom--modal modal fade" id="jobDisputeModal" tabindex="-1" aria-labelledby="jobDisputeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="jobDisputeModalLabel">@lang('Dispute Ongoing Job')</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form--label">@lang('Dispute Reason'):</label>
                                <textarea class="form--control form--control--sm" name="dispute_reason"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $('.btn-job-dispute').on('click', function (event) {
                event.preventDefault()
                let disputeModal = $('#jobDisputeModal')

                disputeModal.find('form').attr('action', $(this).attr('href'))
                disputeModal.modal('show')
            })
        })(jQuery)
    </script>
@endpush
