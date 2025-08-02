@php
    $callToActionContent = getSiteData('call_to_action.content', true)
@endphp

<section class="call-to-action">
    <div class="row g-0">
        <div class="col-lg-6">
            <div class="find-job py-120 bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/call_to_action/' . @$callToActionContent->data_info->first_background_image, '960x640') }}">
                <div class="section-heading section-heading-light">
                    <p class="section-heading__subtitle">{{ __(@$callToActionContent->data_info->first_heading_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __(@$callToActionContent->data_info->first_heading_title) }}</h2>
                </div>
                <p class="call-to-action__desc">
                    {{ __(@$callToActionContent->data_info->first_short_description) }}
                </p>
                <div>
                    <a href="{{ route('jobs') }}" class="btn btn--base">
                        {{ __(@$callToActionContent->data_info->first_button_name) }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="post-job py-120 bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/call_to_action/' . @$callToActionContent->data_info->second_background_image, '960x640') }}">
                <div class="section-heading">
                    <p class="section-heading__subtitle">{{ __(@$callToActionContent->data_info->second_heading_subtitle) }}</p>
                    <h2 class="section-heading__title">{{ __(@$callToActionContent->data_info->second_heading_title) }}</h2>
                </div>
                <p class="call-to-action__desc">
                    {{ __(@$callToActionContent->data_info->second_short_description) }}
                </p>
                <div>
                    <a href="{{ route('user.job.create') }}" class="btn btn--base">
                        {{ __(@$callToActionContent->data_info->second_button_name) }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
