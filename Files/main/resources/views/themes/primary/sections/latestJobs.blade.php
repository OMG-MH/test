@php
    $latestJobsContent = getSiteData('latest_jobs.content', true)
@endphp

<div class="latest-job py-120">
    <div class="container">
        <div class="section-heading">
            <div class="row align-items-center">
                <div class="col-md-6" >
                    <p class="section-heading__subtitle">{{ __(@$latestJobsContent->data_info->section_heading_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __(@$latestJobsContent->data_info->section_heading_title) }}</h2>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end justify-content-center" >
                    <a href="{{ route('jobs') }}" class="btn btn--base">
                        {{ __(@$latestJobsContent->data_info->section_button_name) }}
                    </a>
                </div>
            </div>
        </div>
        <div class="row g-4 latest-job__row justify-content-lg-start justify-content-center">
            @forelse($latestJobs as $job)
                <div class="col-xxl-3 col-lg-4 col-sm-6 col-xsm-9" >
                    @include($activeTheme . 'partials.basicJob')
                </div>
            @empty
                @include($activeTheme . 'partials.basicNoData')
            @endforelse
        </div>
    </div>
</div>
