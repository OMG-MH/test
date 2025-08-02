@php
    $partnerContent = getSiteData('partner.content', true);
    $partnerElements = getSiteData('partner.element', false, null, true);
@endphp

@if (count($partnerElements))
    <div class="partner section-bg">
        <div class="partner__slider">
            @foreach ($partnerElements as $partner)
                <div class="partner__slide">
                    <img src="{{ getImage($activeThemeTrue . 'images/site/partner/' . @$partner->data_info->image) }}" alt="Image">
                </div>
            @endforeach
        </div>
    </div>
@endif

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/bxslider.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset($activeThemeTrue . 'js/bxslider.min.js') }}"></script>
@endpush