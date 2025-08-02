@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-sm-10">
            <div class="custom--card">
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="form--label required">@lang('Current Password')</label>
                            <div class="position-relative">
                                <input type="password" class="form--control" name="current_password" id="current-password" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#current-password"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form--label required">@lang('New Password')</label>
                            <div class="position-relative">
                                <input type="password" @class(['form--control', 'secure-password' => $setting->strong_pass]) name="password" id="new-password" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#new-password"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form--label required">@lang('Confirm Password')</label>
                            <div class="position-relative">
                                <input type="password" class="form--control" name="password_confirmation" id="confirm-password" required>
                                <span class="password-show-hide ti ti-eye toggle-password" id="#confirm-password"></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@pushIf($setting->strong_pass, 'page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/strongPassword.css') }}">
@endPushIf

@pushIf($setting->strong_pass, 'page-script-lib')
    <script src="{{ asset('assets/universal/js/strongPassword.js') }}"></script>
@endPushIf
