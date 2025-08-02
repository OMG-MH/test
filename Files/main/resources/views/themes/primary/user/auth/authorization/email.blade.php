@extends($activeTheme . 'layouts.app')

@php $emailConfirmContent = getSiteData('email_confirm.content', true) @endphp

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/email_confirm/' . @$emailConfirmContent->data_info->background_image, '1920x1080') }}"></div>
        <div class="account__form">
            @include($activeTheme . 'partials.basicAccountTop')

            <div class="account__form__content">
                <h3 class="account__form__title">{{ __(@$emailConfirmContent->data_info->form_heading) }}</h3>
            </div>
            <p class="mb-4 text-center">@lang('A six-digit verification code has been sent to') <b>{{ showEmailAddress($user->email) }}</b></p>
            <form action="{{ route('user.verify.email') }}" method="POST" class="verification-code-form">
                @csrf
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label class="form--label required">@lang('Verification Code')</label>

                        @include('partials.verificationCode')
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn--base w-100">
                            {{ __(@$emailConfirmContent->data_info->submit_button_text) }}
                        </button>
                    </div>
                    <div class="col-sm-12">
                        <div class="have-account text-center">
                            <p class="have-account__text">
                                @lang('Please check including your') <b>@lang('spam')</b> @lang('folder. If not found, then you can') <a href="{{ route('user.send.verify.code', 'email') }}" class="have-account__link text--base">@lang('Try Again.')</a>
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
