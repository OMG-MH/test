@extends($activeTheme . 'layouts.app')

@php $userBanContent = getSiteData('user_ban.content', true) @endphp

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/user_ban/' . @$userBanContent->data_info->background_image, '1920x1080') }}"></div>
        <div class="account__form">
            @include($activeTheme . 'partials.basicAccountTop')

            <div class="account__form__content">
                <h3 class="account__form__title">{{ __(@$userBanContent->data_info->form_heading) }}</h3>
            </div>
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Reason')</h3>
                </div>
                <div class="card-body p-3">
                    <p class="card-text">{{ @$user->ban_reason }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
