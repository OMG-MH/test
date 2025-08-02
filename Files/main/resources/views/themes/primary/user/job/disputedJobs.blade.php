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
                <th>@lang('Job Title')</th>
                <th>@lang('Freelancer')</th>
                <th>@lang('Disputant')</th>
                <th>@lang('Disputed On')</th>
                <th>@lang('Status')</th>
                <th>@lang('Settled On')</th>
                <th>@lang('Settled Amount')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($disputedJobs->sortByDesc('status') as $disputedJob)
                <tr>
                    <td>{{ __($disputedJob->job->title) }}</td>
                    <td>
                        <a href="{{ route('freelancer.show', ['username' => $disputedJob->userAssignedTo->username]) }}">
                            {{ __($disputedJob->userAssignedTo->fullname) }}
                        </a>
                    </td>
                    <td>
                        {{ auth()->id() == $disputedJob->disputant->id ? trans('Me') : __($disputedJob->disputant->fullname) }}
                    </td>
                    <td>
                        <span class="d-block">
                            <span class="d-block">{{ showDateTime($disputedJob->disputed_at) }}</span>
                            <span class="d-block">{{ diffForHumans($disputedJob->disputed_at) }}</span>
                        </span>
                    </td>
                    <td>
                        @php echo $disputedJob->status_badge @endphp
                    </td>
                    <td>
                        @if($disputedJob->settled_at)
                            <span class="d-block">
                                <span class="d-block">{{ showDateTime($disputedJob->settled_at) }}</span>
                                <span class="d-block">{{ diffForHumans($disputedJob->settled_at) }}</span>
                            </span>
                        @else
                            <p>@lang('Not Yet')</p>
                        @endif
                    </td>
                    <td>{{ $setting->cur_sym . showAmount($disputedJob->settled_author_amount) }}</td>
                    <td>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.disputed.job.show', ['assignedJob' => $disputedJob]) }}" class="btn btn--sm btn--icon btn-outline--info" title="@lang('Details')" data-bs-custom-class="tooltip-sm">
                                <i class="ti ti-device-desktop transform-1"></i>
                            </a>

                            @if($disputedJob->status == ManageStatus::ASSIGNED_JOB_SETTLED && !$disputedJob->review_exists)
                                <a href="{{ route('user.disputed.job.share.feedback', ['assignedJob' => $disputedJob]) }}" class="btn btn--sm btn--icon btn-outline--base btn-feedback" title="@lang('Share Your Feedback')" data-bs-custom-class="tooltip-sm">
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

    @if ($disputedJobs->hasPages())
        {{ paginateLinks($disputedJobs) }}
    @endif
@endsection

@push('user-panel-modal')
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
