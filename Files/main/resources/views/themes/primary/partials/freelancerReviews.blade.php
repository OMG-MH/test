@foreach($reviews as $review)
    @php $reviewer = $review->author @endphp

    <div class="user-profile__review">
        <div class="user-profile__review__img">
            <img src="{{ getImage(getFilePath('userProfile') . '/' . $reviewer->image, null, true) }}" alt="@lang('User')">
        </div>
        <div class="user-profile__review__txt">
            <h5 class="user-profile__review__name">{{ __($reviewer->fullname) }}</h5>
            <span class="user-profile__review__country">{{ __($reviewer->country_name) }}</span>
            <div class="user-profile__review__star rating-list">
                <div class="rating-list__item">
                    @for($i = 0; $i < $review->rating; $i++)
                        <i class="ti ti-star-filled rated"></i>
                    @endfor

                    @for($j = 5 - $review->rating; $j > 0; $j--)
                        <i class="ti ti-star-filled"></i>
                    @endfor
                </div>
                <span class="rating-list__text">{{ '(' . $review->rating . ')' }}</span>
            </div>
            <p class="user-profile__review__desc">{{ __($review->review) }}</p>
        </div>
    </div>
@endforeach
