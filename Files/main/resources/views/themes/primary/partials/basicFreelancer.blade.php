@php $freelancer = $topFreelancer->userAssignedTo @endphp

<div class="freelancer__card">
    <div class="freelancer__card__img">
        <img src="{{ getImage(getFilePath('userProfile') . '/' . $freelancer->image, null, true) }}" alt="@lang('Freelancer')">
    </div>
    <div class="freelancer__card__txt">
        <div class="freelancer__card__title">
            <h3 class="freelancer__card__name">
                <a href="{{ route('freelancer.show', $freelancer->username) }}">{{ __($freelancer->fullname) }}</a>
            </h3>
            <span class="freelancer__card__designation">{{ __($freelancer->role) }}</span>
        </div>
        <ul class="freelancer__card__list">
            <li>@lang('Joining Date'): <span>{{ showDateTime($freelancer->created_at, 'M d, Y') }}</span></li>
            <li>@lang('Country'): <span>{{ __($freelancer->country_name) }}</span></li>
            <li>@lang('Jobs Completed'): <span>{{ $topFreelancer->completed_jobs_count }}</span></li>

            @php
                $review    = $freelancer->freelancerReviews->first();
                $avgRating = 0;

                if ($review) {
                    $averageRating = $review->average_rating;
                    $avgRating     = $averageRating == floor($averageRating) ? (int) $averageRating : number_format($averageRating, 1);
                }
            @endphp

            @if ($review )
                <li>@lang('Rating'): <span>{{ $avgRating . '/5' }} <small class="text--muted">{{ '(' . $review->reviews_count . ')' }}</small></span></li>
            @else
                <li>@lang('Rating'): <span>0/5 <small class="text--muted">0</small></span></li>
            @endif
        </ul>
    </div>
</div>
