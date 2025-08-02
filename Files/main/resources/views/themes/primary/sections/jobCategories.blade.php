@php
    $categoriesContent = getSiteData('categories.content', true)
@endphp

<section class="job-category py-120">
    <div class="container">
        <div class="section-heading">
            <div class="row align-items-center gy-2">
                <div class="col-lg-7 col-md-6" >
                    <p class="section-heading__subtitle">{{ __(@$categoriesContent->data_info->section_heading_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __(@$categoriesContent->data_info->section_heading_title) }}</h2>
                </div>
                <div class="col-lg-5 col-md-6 d-flex justify-content-md-end justify-content-center" >
                    <a href="{{ route('job.categories') }}" class="btn btn--base">
                        {{ __(@$categoriesContent->data_info->section_button_name) }}
                    </a>
                </div>
            </div>
        </div>
        <div class="row g-4 row-cols-xl-6 row-cols-lg-4 row-cols-sm-3 row-cols-2 job-category__row justify-content-center">
            @each($activeTheme . 'partials.basicCategory', $featuredCategories, 'category', $activeTheme . 'partials.basicNoData')
        </div>
    </div>
</section>
