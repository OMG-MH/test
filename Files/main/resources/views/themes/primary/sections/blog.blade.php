@php
    $blogContent  = getSiteData('blog.content', true);
    $blogElements = getSiteData('blog.element', false, 4, true)
@endphp

<section class="blog py-120 section-bg">
    <div class="container">
        <div class="row justify-content-md-between align-items-md-center">
            <div class="col-xl-4 d-xl-block d-none">
                <div class="section-heading" >
                    <p class="section-heading__subtitle">{{ __(@$blogContent->data_info->section_heading_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __(@$blogContent->data_info->section_heading_title) }}</h2>
                </div>
                <div>
                    <a href="{{ route('blog') }}" class="btn btn--base">{{ __(@$blogContent->data_info->section_button_name) }}</a>
                </div>
            </div>
            <div class="col-md-12 d-xl-none d-block">
                <div class="section-heading">
                    <div class="row align-items-center">
                        <div class="col-md-6" >
                            <p class="section-heading__subtitle">{{ __(@$blogContent->data_info->section_heading_subtitle) }}</p>
                            <h2 class="section-heading__title">{{ __(@$blogContent->data_info->section_heading_title) }}</h2>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end justify-content-center">
                            <a href="{{ route('blog') }}" class="btn btn--base">{{ __(@$blogContent->data_info->section_button_name) }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="row g-4">
                    @forelse($blogElements as $blog)
                        <div class="col-sm-6">
                            <div class="blog__card" >
                                @include($activeTheme . 'partials.basicBlog')
                            </div>
                        </div>
                    @empty
                        @include($activeTheme . 'partials.basicNoData')
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
