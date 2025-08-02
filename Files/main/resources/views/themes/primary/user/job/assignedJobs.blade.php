@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="d-flex justify-content-end mb-3">
        <form action="" method="get" class="input--group">
            <input type="text" class="form--control form--control--sm" name="search" placeholder="@lang('Search here')..." value="{{ request('search') }}">
            <button type="submit" class="btn btn--sm btn--base"><i class="ti ti-search"></i></button>
        </form>
    </div>
    <table class="table table-borderless table--striped table--responsive--xl">
        <thead>
            <tr>
                <th>@lang('S.N.')</th>
                <th>@lang('Job Title')</th>
                <th>@lang('Freelancer')</th>
                <th>@lang('Assigned On')</th>
                <th>@lang('Status')</th>
                <th>@lang('Completed On')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignedJobs as $assignedJob)
                <tr>
                    <td>{{ $assignedJobs->firstItem() + $loop->index }}</td>
                    <td>{{ __($assignedJob->job->title) }}</td>
                    <td>
                        <a href="{{ route('freelancer.show', ['username' => $assignedJob->userAssignedTo->username]) }}">
                            {{ __($assignedJob->userAssignedTo->fullname) }}
                        </a>
                    </td>
                    <td>
                        <span class="d-block">
                            <span class="d-block">{{ showDateTime($assignedJob->created_at) }}</span>
                            <span class="d-block">{{ diffForHumans($assignedJob->created_at) }}</span>
                        </span>
                    </td>
                    <td>
                        @php echo $assignedJob->status_badge @endphp
                    </td>
                    <td>
                        @if($assignedJob->completed_at)
                            <span class="d-block">
                                <span class="d-block">{{ showDateTime($assignedJob->completed_at) }}</span>
                                <span class="d-block">{{ diffForHumans($assignedJob->completed_at) }}</span>
                            </span>
                        @else
                            <p>@lang('Not Yet')</p>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.assigned.job.show', $assignedJob) }}" class="btn btn--sm btn--icon btn-outline--info" title="@lang('Details')" data-bs-custom-class="tooltip-sm">
                                <i class="ti ti-device-desktop transform-1"></i>
                            </a>

                            @if($assignedJob->status == ManageStatus::ASSIGNED_JOB_IN_PROGRESS)
                                <a href="{{ route('user.assigned.job.complete', $assignedJob) }}" class="btn btn--sm btn--icon btn-outline--success btn-job-complete" title="@lang('Mark as Completed')" data-bs-custom-class="tooltip-sm">
                                    <i class="ti ti-circle-check transform-1"></i>
                                </a>
                                <a href="{{ route('user.assigned.job.dispute', $assignedJob) }}" class="btn btn--sm btn--icon btn-outline--danger btn-job-dispute" title="@lang('Mark as Disputed')" data-bs-custom-class="tooltip-sm">
                                    <i class="ti ti-exclamation-circle transform-1"></i>
                                </a>
                            @endif

                            @if($assignedJob->status == ManageStatus::ASSIGNED_JOB_COMPLETED && !$assignedJob->review_exists)
                                <a href="{{ route('user.assigned.job.share.feedback', $assignedJob) }}" class="btn btn--sm btn--icon btn-outline--base btn-feedback" title="@lang('Share Your Feedback')" data-bs-custom-class="tooltip-sm">
                                    <i class="ti ti-user-star transform-1"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                @include('partials.noData')
            @endforelse
        </tbody>
    </table>

    @if ($assignedJobs->hasPages())
        {{ paginateLinks($assignedJobs) }}
    @endif
@endsection

@push('user-panel-modal')
    {{-- Job Complete Modal --}}
    <div class="custom--modal modal fade" id="jobCompleteModal" tabindex="-1" aria-labelledby="jobCompleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="jobCompleteModalLabel">@lang('Complete Assigned Job')</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to complete this assigned job?')</p>
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

    {{-- Job Dispute Modal --}}
    <div class="custom--modal modal fade" id="jobDisputeModal" tabindex="-1" aria-labelledby="jobDisputeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="jobDisputeModalLabel">@lang('Dispute Assigned Job')</h1>
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

    {{-- Feedback Modal --}}
    <div class="custom--modal modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="feedbackModalLabel">@lang('Share Feedback')</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <label class="form--label mb-0">@lang('Your Rating'):</label>
                                    <span class="rating-list input-rating w-auto" data-input-id="rating">
                                        <button type="button" class="rating-list__item">
                                            <i class="ti ti-star-filled"></i>
                                        </button>
                                        <button type="button" class="rating-list__item">
                                            <i class="ti ti-star-filled"></i>
                                        </button>
                                        <button type="button" class="rating-list__item">
                                            <i class="ti ti-star-filled"></i>
                                        </button>
                                        <button type="button" class="rating-list__item">
                                            <i class="ti ti-star-filled"></i>
                                        </button>
                                        <button type="button" class="rating-list__item">
                                            <i class="ti ti-star-filled"></i>
                                        </button>
                                    </span>
                                    <input type="number" min="0" max="5" id="rating" name="rating" hidden readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label">@lang('Review'):</label>
                                <textarea class="form--control form--control--sm" name="review"></textarea>
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

            $(function () {
                $('.btn-job-complete').on('click', function (event) {
                    event.preventDefault()
                    let completeModal = $('#jobCompleteModal')

                    completeModal.find('form').attr('action', $(this).attr('href'))
                    completeModal.modal('show')
                })

                $('.btn-job-dispute').on('click', function (event) {
                    event.preventDefault()
                    let disputeModal = $('#jobDisputeModal')

                    disputeModal.find('form').attr('action', $(this).attr('href'))
                    disputeModal.modal('show')
                })

                // feedback start
                let inputRating = $('.input-rating .rating-list__item')

                inputRating.each(function () {
                    $(this).on('mouseenter', function () {
                        $(this).parent('.input-rating').addClass('rating')

                        let index = $(this).index()

                        $(this).parent().children().find('i').removeClass('rating')
                        $(this).parent().children().slice(0, index + 1).find('i').addClass('rating')
                    })
                })

                inputRating.each(function () {
                    $(this).on('click', function () {
                        let index = $(this).index()
                        $(this).parent().children().find('i').removeClass('rated')
                        $(this).parent().children().slice(0, index + 1).find('i').addClass('rated')

                        let starCount = index + 1
                        let inputID = $(this).parent('.input-rating').attr('data-input-id')
                        $('#' + inputID).attr('value', starCount)
                    })
                })

                $('.input-rating').on('mouseleave', function () {
                    $(this).removeClass('rating')
                    $(this).find('.rating-list__item').find('i').removeClass('rating')
                })

                $('.btn-feedback').on('click', function (event) {
                    event.preventDefault()
                    let feedbackModal = $('#feedbackModal')

                    feedbackModal.find('form').attr('action', $(this).attr('href'))
                    feedbackModal.modal('show')
                })
                // feedback end
            })
        })(jQuery)
    </script>
@endpush
