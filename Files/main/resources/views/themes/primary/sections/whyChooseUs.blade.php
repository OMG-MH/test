@php
    $whyChooseUsContent  = getSiteData('why_choose_us.content', true);
    $whyChooseUsElements = getSiteData('why_choose_us.element', false, null, true);
@endphp

<section class="why-choose py-120 section-bg">
    <div class="container">
        <div class="row g-4">
            <div class="col-xl-6 col-lg-7">
                <div class="section-heading" >
                    <h6 class="section-heading__subtitle">{{ __(@$whyChooseUsContent->data_info->section_heading_subtitle) }}</h6>
                    <h2 class="section-heading__title">{{ __(@$whyChooseUsContent->data_info->section_heading_title) }}</h2>
                </div>
                <div class="why-choose__list">
                    @forelse($whyChooseUsElements as $whyChooseUs)
                        <div class="why-choose__card" >
                            <div class="why-choose__card__txt">
                                <h3 class="why-choose__card__title">{{ __(@$whyChooseUs->data_info->title) }}</h3>
                                <p class="why-choose__card__desc">{{ __(@$whyChooseUs->data_info->short_description) }}</p>
                            </div>
                            <span class="why-choose__card__icon">
                                <img src="{{ getImage($activeThemeTrue . 'images/site/why_choose_us/' . @$whyChooseUs->data_info->image) }}" alt="Icon">
                            </span>
                        </div>
                    @empty
                        @include($activeTheme . 'partials.basicNoData')
                    @endforelse
                </div>
            </div>
            <div class="col-xl-6 col-lg-5">
                <div class="why-choose__thumb">
                    <img src="{{ getImage($activeThemeTrue . 'images/site/why_choose_us/' . @$whyChooseUsContent->data_info->image, '725x860') }}" alt="image">
                </div>
            </div>
        </div>
    </div>
</section>
