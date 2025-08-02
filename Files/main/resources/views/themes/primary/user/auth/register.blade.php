@extends($activeTheme . 'layouts.app')

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/register/' . @$registerContent->data_info->background_image, '1920x1080') }}"></div>
        <div class="account__form account__form-2">
            @include($activeTheme . 'partials.basicAccountTop')

            <div class="account__form__content">
                <h3 class="account__form__title">{{ __(@$registerContent->data_info->form_heading) }}</h3>
            </div>
            <form action="{{ route('user.register.form') }}" method="POST" class="verify-gcaptcha">
                @csrf
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form--label required">@lang('First Name')</label>
                        <input type="text" class="form--control" name="firstname" value="{{ old('firstname') }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form--label required">@lang('Last Name')</label>
                        <input type="text" class="form--control" name="lastname" value="{{ old('lastname') }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form--label required">@lang('Username')</label>
                        <input type="text" class="form--control checkUser" name="username" value="{{ old('username') }}" required>
                        <small class="text-danger usernameExist"></small>
                    </div>
                    <div class="col-sm-6">
                        <label class="form--label required">@lang('Email Address')</label>
                        <input type="email" class="form--control checkUser" name="email" value="{{ old('email') }}" required>
                        <small class="text-danger emailExist"></small>
                    </div>
                    <div class="col-sm-6">
                        <label class="form--label required">@lang('Country')</label>
                        <select name="country" class="form--control form-select select-2" required>
                            @foreach($countries as $key => $country)
                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">
                                    {{ __(@$country->country) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form--label required">@lang('Phone')</label>
                        <div class="input--group">
                            <span class="input-group-text input-group-text-light mobile-code"></span>
                            <input type="hidden" name="mobile_code">
                            <input type="hidden" name="country_code">
                            <input type="number" class="form--control checkUser" name="mobile" value="{{ old('mobile') }}" required>
                        </div>
                        <small class="text-danger mobileExist"></small>
                    </div>
                    <div class="col-sm-6">
                        <label class="form--label required">@lang('Password')</label>
                        <div class="position-relative">
                            <input type="password" @class(['form-control form--control', 'secure-password' => $setting->strong_pass]) name="password" id="your-password" required>
                            <span class="password-show-hide ti ti-eye toggle-password" id="#your-password"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form--label required">@lang('Confirm Password')</label>
                        <div class="position-relative">
                            <input type="password" class="form-control form--control" name="password_confirmation" id="confirm-password" required>
                            <span class="password-show-hide ti ti-eye toggle-password" id="#confirm-password"></span>
                        </div>
                    </div>

                    @if($setting->agree_policy)
                        <div class="col-sm-12">
                            <div class="form--check">
                                <input type="checkbox" class="form-check-input" name="agree" id="agree" @checked(old('agree')) required>
                                <label for="agree" class="form-check-label">
                                    @lang('I agree with') @foreach ($policyPages as $policy) <a href="{{ route('policy.pages', [slug($policy->data_info->title), $policy->id]) }}">{{ __($policy->data_info->title) }}</a>@if (!$loop->last), @endif @endforeach
                                </label>
                            </div>
                        </div>
                    @endif

                    <x-captcha />

                    <div class="col-sm-12">
                        <button type="submit" class="btn btn--base w-100" id="recaptcha">
                            {{ __(@$registerContent->data_info->submit_button_text) }}
                        </button>
                    </div>
                    <div class="col-sm-12">
                        <div class="have-account text-center">
                            <p class="have-account__text">@lang('Already have an account?') <a href="{{ route('user.login') }}" class="have-account__link text--base">@lang('Sign In')</a> @lang('here.')</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">

    @if($setting->strong_pass)
        <link rel="stylesheet" href="{{ asset('assets/universal/css/strongPassword.css') }}">
    @endif
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>

    @if($setting->strong_pass)
        <script src="{{ asset('assets/universal/js/strongPassword.js') }}"></script>
    @endif
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('[name=country]').on('change', function() {
                let selectedOption = $('[name=country] :selected');

                $('[name=mobile_code]').val(selectedOption.data('mobile_code'));
                $('[name=country_code]').val(selectedOption.data('code'));
                $('.mobile-code').text('+' + selectedOption.data('mobile_code'));
            });

            let initiallySelectedOption = $('[name=country] :selected')

            $('[name=mobile_code]').val(initiallySelectedOption.data('mobile_code'));
            $('[name=country_code]').val(initiallySelectedOption.data('code'));
            $('.mobile-code').text('+' + initiallySelectedOption.data('mobile_code'));

            $('.checkUser').on('focusout', function() {
                let url = '{{ route('user.check.user') }}';
                let value = $(this).val();
                let token = '{{ csrf_token() }}';
                let data;

                if ($(this).attr('name') === 'mobile') {
                    let mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    data = {
                        mobile: mobile,
                        _token: token
                    }
                }

                if ($(this).attr('name') === 'email') {
                    data = {
                        email: value,
                        _token: token
                    }
                }

                if ($(this).attr('name') === 'username') {
                    data = {
                        username: value,
                        _token: token
                    }
                }

                $.post(url, data, function(response) {
                    if (response.data !== false && (response.type === 'email' || response.type === 'username' || response.type === 'mobile')) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
