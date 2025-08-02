@extends($activeTheme . 'layouts.app')

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/login/' . @$loginContent->data_info->background_image, '1920x1080') }}"></div>
        <div class="account__form">
            @include($activeTheme . 'partials.basicAccountTop')

            <div class="account__form__content">
                <h3 class="account__form__title">{{ __(@$loginContent->data_info->form_heading) }}</h3>
            </div>
            <form action="" method="POST" class="verify-gcaptcha">
                @csrf
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label class="form--label required">@lang('Username or Email Address')</label>
                        <input type="text" class="form--control" name="username" value="{{ old('username') }}" required>
                    </div>
                    <div class="col-sm-12">
                        <label class="form--label required">@lang('Password')</label>
                        <div class="position-relative">
                            <input type="password" id="your-password" class="form-control form--control" name="password" required>
                            <span class="password-show-hide ti ti-eye toggle-password" id="#your-password"></span>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="form--check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" @checked(old('remember'))>
                                <label class="form-check-label" for="remember">@lang('Remember me')</label>
                            </div>
                            <a href="{{ route('user.password.request.form') }}" class="forgot-password text--base">
                                @lang('Forgot Your Password?')
                            </a>
                        </div>
                    </div>

                    <x-captcha />

                    <div class="col-sm-12">
                        <button type="submit" class="btn btn--base w-100" id="recaptcha">
                            {{ __(@$loginContent->data_info->submit_button_text) }}
                        </button>
                    </div>
                    <div class="col-sm-12">
                        <div class="have-account text-center">
                            <p class="have-account__text">
                                @lang('Don\'t have any account?') <a href="{{ route('user.register.form') }}" class="have-account__link text--base">@lang('Create Account')</a>
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
