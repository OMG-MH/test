@extends($activeTheme . 'layouts.frontend')

@php $bannerContent = getSiteData('banner.content', true) @endphp

@section('frontend')
<section class="banner-section">
    <div class="banner-section__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/banner/' . @$bannerContent->data_info->background_image, '1920x1160') }}"></div>

    <div class="container">
        <div class="row justify-content-lg-between justify-content-center align-items-center">
            <div class="col-lg-6">
                <div class="banner-content" >
                    <h4 class="banner-content__subtitle">{{ __(@$bannerContent->data_info->subtitle) }}</h4>
                    <h1 class="banner-content__title">{{ __(@$bannerContent->data_info->title_first_part) }} <span class="styled-title">{{ __(@$bannerContent->data_info->title_second_part) }}</span> {{ __(@$bannerContent->data_info->title_third_part) }}</h1>
                    <div class="custom--card banner-content__form d-xl-block d-none">
                        <div class="card-header">
                            <h3 class="title">@lang('Search your desired job')</h3>
                        </div>
                        <form action="{{ route('jobs') }}" method="get" class="card-body">
                            <div class="input--group">
                                <select class="form--control form-select select-2" name="category">
                                    <option value="" selected disabled>@lang('Select One')</option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}">{{ __($category->name) }}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="form--control" name="title" placeholder="@lang('Enter Job Title')...">
                                <button type="submit" class="btn btn--base">
                                    <i class="ti ti-search"></i> @lang('Search')
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="custom--card banner-content__form d-xl-none">
                        <div class="card-header">
                            <h3 class="title">@lang('Search your desired job')</h3>
                        </div>
                        <form action="{{ route('jobs') }}" method="get" class="card-body d-flex flex-column gap-3">
                            <select class="form--control form-select select-2" name="category">
                                <option value="" selected disabled>@lang('Select One')</option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}">{{ __($category->name) }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form--control" name="title" placeholder="@lang('Job Title')...">
                            <button type="submit" class="btn btn--base">
                                <i class="ti ti-search"></i> @lang('Search')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-sm-8 col-xsm-8 col-10">
                <div class="banner-thumb d-flex justify-content-end">
                    <a href="#" class="video-btn" data-video-id="{{ __(@$bannerContent->data_info->youtube_video_id) }}">
                        <i class="ti ti-player-play-filled"></i>
                        <div class="circle-txt">{{ __(@$bannerContent->data_info->video_button_name) }}</div>
                    </a>
                    <img src="{{ getImage($activeThemeTrue . 'images/site/banner/' . @$bannerContent->data_info->image, '790x785') }}" alt="image">
                </div>
            </div>
        </div>
    </div>
</section>

    @include($activeTheme . 'sections.partners')
    @include($activeTheme . 'sections.jobCategories')
    @include($activeTheme . 'sections.callToAction')
    @include($activeTheme . 'sections.latestJobs')
    @include($activeTheme . 'sections.whyChooseUs')
    @include($activeTheme . 'sections.performers')
    @include($activeTheme . 'sections.testimonials')
    @include($activeTheme . 'sections.counters')
    @include($activeTheme . 'sections.blog')
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/modal-video.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/slick.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset($activeThemeTrue . 'js/modal-video.min.js') }}"></script>
    <script src="{{ asset($activeThemeTrue . 'js/circleText.js') }}"></script>
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
    <script src="{{ asset($activeThemeTrue . 'js/slick.min.js') }}"></script>
@endpush
