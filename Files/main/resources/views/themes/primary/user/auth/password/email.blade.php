@extends($activeTheme . 'layouts.app')

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/forgot_password/' . @$forgotPasswordContent->data_info->background_image, '1920x1080') }}"></div>
        <div class="account__form">
            @include($activeTheme . 'partials.basicAccountTop')

            <div class="account__form__content">
                <h3 class="account__form__title">{{ __(@$forgotPasswordContent->data_info->form_heading) }}</h3>
            </div>
            <form action="" method="POST" class="verify-gcaptcha">
                @csrf
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label class="form--label required">@lang('Username or Email Address')</label>
                        <input type="text" class="form--control" name="value" value="{{ old('value') }}" required>
                    </div>

                    <x-captcha />

                    <div class="col-sm-12">
                        <button type="submit" class="btn btn--base w-100" id="recaptcha">
                            {{ __(@$forgotPasswordContent->data_info->submit_button_text) }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
