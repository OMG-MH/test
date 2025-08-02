@extends($activeTheme . 'layouts.app')

@php $tfaConfirmContent = getSiteData('2fa_confirm.content', true) @endphp

@section('content')
    <section class="account">
        <div class="account__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/2fa_confirm/' . @$tfaConfirmContent->data_info->background_image, '1920x1080') }}"></div>
        <div class="account__form">
            @include($activeTheme . 'partials.basicAccountTop')

            <div class="account__form__content">
                <h3 class="account__form__title">{{ __(@$tfaConfirmContent->data_info->form_heading) }}</h3>
            </div>
            <p class="mb-4 text-center">{{ __(@$tfaConfirmContent->data_info->form_text) }}</p>
            <form action="{{ route('user.go2fa.verify') }}" method="POST" class="verification-code-form">
                @csrf
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label class="form--label required">@lang('Verification Code')</label>

                        @include('partials.verificationCode')
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn--base w-100">
                            {{ __(@$tfaConfirmContent->data_info->submit_button_text) }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
