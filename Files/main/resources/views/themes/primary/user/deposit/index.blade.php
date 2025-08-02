@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Deposit Money To Your Account')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.deposit.insert') }}" method="post" class="row g-4">
                        @csrf
                        <input type="hidden" name="currency">
                        <div class="col-12">
                            <label class="form--label required">@lang('Gateway')</label>
                            <select class="form--control form-select select-2" name="gateway" required>
                                <option value="0" selected disabled>@lang('Select Gateway')</option>

                                @foreach($gatewayCurrencies as $data)
                                    <option value="{{ $data->method_code }}" @selected(old('gateway') == $data->method_code) data-gateway="{{ $data }}">
                                        {{ __(@$data->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form--label">@lang('Amount')</label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" name="amount" class="form--control" placeholder="@lang('Enter Amount')" value="{{ old('amount') }}">
                                <span class="input-group-text">{{ $setting->site_cur }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <table class="table table-borderless table-light no-shadow">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('Limit'):</span>
                                        </td>
                                        <td>
                                            <span class="min">0</span> {{ __($setting->site_cur) }} - <span class="max">0</span> {{ __($setting->site_cur) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('Charge'):</span>
                                        </td>
                                        <td>
                                            <span class="charge">0</span> {{ __($setting->site_cur) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('Payable'):</span>
                                        </td>
                                        <td>
                                            <span class="payable">0</span> {{ __($setting->site_cur) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('Conversion Rate'):</span>
                                        </td>
                                        <td>
                                            1 {{ __($setting->site_cur) }} = <span class="rate">1</span> <span class="method-currency">{{ __($setting->site_cur) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">@lang('In') <span class="method-currency">{{ __($setting->site_cur) }}</span>:</span>
                                        </td>
                                        <td>
                                            <span class="in-method-cur">0</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn--base w-100">@lang('Deposit Now')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            function reset() {
                $('.min').text('0')
                $('.max').text('0')
                $('.charge').text('0')
                $('.payable').text('0')
                $('.rate').text('1')
                $('.method-currency').text('{{ $setting->site_cur }}')
                $('.in-method-cur').text('0')
            }

            $('[name=gateway]').on('change', function() {
                if (!$('[name=gateway]').val()) {
                    reset()

                    return false
                }

                let resource       = $('[name=gateway] option:selected').data('gateway')
                let fixed_charge   = parseFloat(resource.fixed_charge)
                let percent_charge = parseInt(resource.percent_charge)
                let rate           = parseFloat(resource.rate)
                let toFixedDigit   = resource.method.crypto ? 8 : 2
                let amount         = parseFloat($('[name=amount]').val())

                if (!amount) {
                    reset()

                    return false
                }

                $('.min').text(parseFloat(resource.min_amount).toFixed(2))
                $('.max').text(parseFloat(resource.max_amount).toFixed(2))

                let charge = parseFloat(fixed_charge + (amount * percent_charge / 100))
                $('.charge').text(charge.toFixed(2))

                let payable = amount + charge
                $('.payable').text(payable.toFixed(2))

                let finalAmount = payable * rate

                $('.rate').text(rate)
                $('.method-currency').text(resource.currency)
                $('.in-method-cur').text(finalAmount.toFixed(toFixedDigit))

                $('[name=currency]').val(resource.currency)
            })

            $('[name=amount]').on('input', function() {
                $('[name=gateway]').trigger('change')
            })
        })(jQuery)
    </script>
@endpush
