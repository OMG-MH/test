@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="user-profile py-120">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="user-profile__img">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $freelancer->image, null, true) }}" alt="freelancer">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="custom--card">
                        <div class="card-body">
                            <h3 class="user-profile__name">{{ __($freelancer->fullname) }}</h3>
                            <span class="user-profile__username">{{ '@' . $freelancer->username }}</span>
                            <ul class="user-profile__list">
                                <li>@lang('Role'): <span>{{ $freelancer->role ? __($freelancer->role) : trans('Not Set Yet') }}</span></li>
                                <li>@lang('Joining Date'): <span>{{ showDateTime($freelancer->created_at, 'M d, Y') }}</span></li>
                                <li>@lang('Country'): <span>{{ __($freelancer->country_name) }}</span></li>
                                <li>@lang('Jobs Completed'): <span>{{ $freelancer->completed_jobs_count }}</span></li>

                                @php
                                    $averageRating = $freelancer->average_rating;
                                    $avgRating = $averageRating == floor($averageRating) ? (int) $averageRating : number_format($averageRating, 1)
                                @endphp

                                <li>@lang('Rating'): <span>{{ $avgRating . '/5' }} <small class="text--muted">{{ '(' . $freelancer->reviews_count . ')' }}</small></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Skills')</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($freelancer->skills as $skill)
                                    <span class="badge badge--secondary fs-16 fw-medium">{{ __($skill) }}</span>
                                @empty
                                    <p>@lang('No skills have been set yet!')</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('About Me')</h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $freelancer->bio ? __($freelancer->bio) : trans('Not set yet!') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Reviews')</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-4 justify-content-center">
                                <div class="col-xxl-8 col-xl-7 col-lg-6">
                                    <div class="user-profile__reviews">
                                        @php $reviews = $freelancer->freelancerReviews @endphp

                                        @if(count($reviews))
                                            @include($activeTheme . 'partials.freelancerReviews')
                                        @else
                                            @include($activeTheme . 'partials.basicNoData')
                                        @endif
                                    </div>

                                    @if($freelancer->reviews_count > 5)
                                        <div class="d-flex justify-content-center mt-4">
                                            <button type="button" class="btn btn--sm btn--base px-5" id="loadMoreReview" data-url="{{ route('freelancer.reviews', $freelancer->username) }}">
                                                <i class="ti ti-reload"></i> @lang('Load More')
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
                                    @for($i = count($ratingsCount); $i > 0; $i--)
                                        @php
                                            if ($freelancer->reviews_count) {
                                                $ratingPercentage = ($ratingsCount[$i] / $freelancer->reviews_count) * 100;
                                            } else {
                                                $ratingPercentage = 0;
                                            }
                                        @endphp

                                        <div class="user-profile__review-overview">
                                            <span class="user-profile__review-overview__name">{{ $i }} {{ $i > 1 ? trans('Stars') : trans('Star') }}</span>
                                            <div class="custom--progress progress" role="progressbar" aria-label="Rating" aria-valuenow="{{ $ratingPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar" style="width: {{ $ratingPercentage . '%' }}"></div>
                                            </div>
                                            <span class="user-profile__review-overview__count">{{ '(' . $ratingsCount[$i] . ')' }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                let numberOfReviewsToLoad = 10

                $('#loadMoreReview').on('click', function () {
                    $(this).addClass('btn-disabled').attr('disabled', true)

                    let _this = $(this)
                    let url = $(this).data('url')

                    $.get(url, { reviews_batch_size: numberOfReviewsToLoad })
                        .done(function (response) {
                            $('.user-profile__reviews').html(response.html)
                            _this.removeClass('btn-disabled').attr('disabled', false)
                            numberOfReviewsToLoad += 5

                            if (!response.reviewsLeft) _this.parent().remove()
                        })
                        .fail(function (jqXHR) {
                            if (jqXHR.status === 422) {
                                $.each(jqXHR.responseJSON.errors, function (key, errors) {
                                    errors.forEach(function (error) {
                                        showToasts('error', error)
                                    })
                                })
                            } else {
                                showToasts('error', jqXHR.responseJSON.error)
                            }

                            _this.removeClass('btn-disabled').attr('disabled', false)
                        })
                })
            })
        })(jQuery)
    </script>
@endpush
