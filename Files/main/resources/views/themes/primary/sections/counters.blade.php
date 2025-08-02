@php
    $countersContent  = getSiteData('counters.content', true);
    $countersElements = getSiteData('counters.element', false, null, true);
@endphp

<section class="counter-section py-120">
    <div class="container">
        <div class="section-heading text-center">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-6" >
                    <p class="section-heading__subtitle mx-auto">{{ __(@$countersContent->data_info->section_heading_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __(@$countersContent->data_info->section_heading_title) }}</h2>
                </div>
            </div>
        </div>
        <div class="row counter-section__row g-4 justify-content-center">
            @forelse($countersElements as $counter)
                <div class="col-xl-4 col-md-6 col-sm-10">
                    <div class="counter-section__card h-100">
                        <div class="counter-section__card__top">
                            <div class="counter-section__title">
                                <h3 class="counter-section__number odometer" data-count="{{ showAmount(@$counter->data_info->counter_number) }}">0</h3>
                                <p class="counter-section__name">{{ __(@$counter->data_info->title) }}</p>
                            </div>
                            <div class="counter-section__icon">
                                <img src="{{ getImage($activeThemeTrue . 'images/site/counters/' . @$counter->data_info->image) }}" alt="image">
                            </div>
                        </div>
                        <p class="counter-section__desc">{{ __(@$counter->data_info->short_description) }}</p>
                    </div>
                </div>
            @empty
                @include($activeTheme . 'partials.basicNoData')
            @endforelse
        </div>
    </div>
</section>

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/odometer.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset($activeThemeTrue . 'js/odometer.min.js') }}"></script>
@endpush
