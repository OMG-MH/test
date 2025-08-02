@php
    $performersContent = getSiteData('performers.content', true)
@endphp

<section class="freelancer py-120">
    <div class="container">
        <div class="section-heading">
            <div class="row align-items-center">
                <div class="col-md-6" >
                    <p class="section-heading__subtitle">{{ __(@$performersContent->data_info->section_heading_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __(@$performersContent->data_info->section_heading_title) }}</h2>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end justify-content-center" >
                    <a href="{{ route('freelancers') }}" class="btn btn--base">{{ __(@$performersContent->data_info->section_button_name) }}</a>
                </div>
            </div>
        </div>

        @if(count($topFreelancers))
            <div class="freelancer__slider" >
                @foreach($topFreelancers as $topFreelancer)
                    @include($activeTheme . 'partials.basicFreelancer')
                @endforeach
            </div>
        @else
            @include($activeTheme . 'partials.basicNoData')
        @endif
    </div>
</section>
