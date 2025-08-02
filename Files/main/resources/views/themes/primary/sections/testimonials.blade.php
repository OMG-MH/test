@php
    $testimonialsContent  = getSiteData('testimonials.content', true);
    $testimonialsElements = getSiteData('testimonials.element', false, null, true)
@endphp

<section class="testimonial py-120 section-bg">
    <div class="container">
        <div class="row justify-content-between align-items-center" >
            <div class="col-md-5">
                <div class="section-heading">
                    <p class="section-heading__subtitle">{{ __(@$testimonialsContent->data_info->section_heading_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __(@$testimonialsContent->data_info->section_heading_title) }}</h2>
                </div>
                <div class="testimonial__arrow">
                    <button type="button" class="slick-arrow testimonial__arrow__prev">
                        <img src="{{ asset($activeThemeTrue . '/images/arrow-left.png') }}" alt="P">
                    </button>
                    <button type="button" class="slick-arrow testimonial__arrow__next">
                        <img src="{{ asset($activeThemeTrue . '/images/arrow-right.png') }}" alt="N">
                    </button>
                </div>
            </div>
            <div class="col-xl-6 col-md-7" >
                @if(count($testimonialsElements))
                    <div class="testimonial-txt-slider">
                        @foreach($testimonialsElements as $testimonial)
                            <div class="testimonial-card">
                                <span class="testimonial-card__icon"><i class="ti ti-quote"></i></span>
                                <p class="testimonial-card__desc">
                                    {{ __(@$testimonial->data_info->client_review) }}
                                </p>
                                <div class="testimonial-card__user">
                                    <div class="testimonial-card__user__img">
                                        <img src="{{ getImage($activeThemeTrue . 'images/site/testimonials/' . @$testimonial->data_info->client_image, '60x60') }}" alt="image">
                                    </div>
                                    <div class="testimonial-card__user__txt">
                                        <h5 class="testimonial-card__name">{{ __(@$testimonial->data_info->client_name) }}</h5>
                                        <span class="testimonial-card__designation">{{ __(@$testimonial->data_info->client_designation) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    @include($activeTheme . 'partials.basicNoData')
                @endif
            </div>
        </div>
    </div>
</section>
