@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row gy-lg-5 gy-4">
        @if(@$user->kc == ManageStatus::UNVERIFIED || @$user->kc == ManageStatus::PENDING)
            <div class="col-12">
                <div class="alert alert--warning" role="alert">
                    @if(@$user->kc == ManageStatus::UNVERIFIED)
                        <span class="alert__title">{{ __(@$kycContent->data_info->verification_required_heading) }}</span>
                        <p class="alert__desc">{{ __(@$kycContent->data_info->verification_required_details) }} <a href="{{ route('user.kyc.form') }}" class="alert__link">@lang('Click here')</a> @lang('to verify.')</p>
                    @elseif(@$user->kc == ManageStatus::PENDING)
                        <span class="alert__title">{{ __(@$kycContent->data_info->verification_pending_heading) }}</span>
                        <p class="alert__desc">{{ __(@$kycContent->data_info->verification_pending_details) }} <a href="{{ route('user.kyc.data') }}" class="alert__link">@lang('See')</a> @lang('kyc data.')</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="row g-lg-4 g-2 dashboard-card__row">
                <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('user.job.history') }}" class="dashboard-card">
                        <div class="dashboard-card__icon">
                            <i class="ti ti-briefcase fz-1 transform-1"></i>
                        </div>
                        <div class="dashboard-card__txt">
                            <span class="dashboard-card__title">@lang('Job Posts')</span>
                            <span class="dashboard-card__number">{{ $user->job_posts_count }}</span>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('user.workspace.completed.jobs') }}" class="dashboard-card">
                        <div class="dashboard-card__icon">
                            <i class="ti ti-list-check fz-1 transform-1"></i>
                        </div>
                        <div class="dashboard-card__txt">
                            <span class="dashboard-card__title">@lang('Completed Jobs')</span>
                            <span class="dashboard-card__number">{{ $user->completed_jobs_count }}</span>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('user.transactions', ['remark' => 'job_earning']) }}" class="dashboard-card">
                        <div class="dashboard-card__icon">
                            <i class="ti ti-coins fz-1 transform-1"></i>
                        </div>
                        <div class="dashboard-card__txt">
                            <span class="dashboard-card__title">@lang('Total Earning')</span>
                            <span class="dashboard-card__number">{{ $setting->cur_sym . showAmount($totalEarning) }}</span>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ $user->freelancer_status == ManageStatus::FREELANCER_ACTIVE ? route('freelancer.show', ['username' => $user->username]) : route('user.freelancer.profile') }}" class="dashboard-card">
                        <div class="dashboard-card__icon">
                            <i class="ti ti-star fz-1 transform-1"></i>
                        </div>
                        <div class="dashboard-card__txt">
                            <span class="dashboard-card__title">@lang('Rating')</span>

                            @php
                                $averageRating = $user->average_rating;
                                $avgRating     = $averageRating == floor($averageRating) ? (int) $averageRating : number_format($averageRating, 1)
                            @endphp

                            <span class="dashboard-card__number">{{ $avgRating . '/5' }}</span>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('user.home') }}" class="dashboard-card">
                        <div class="dashboard-card__icon">
                            <i class="ti ti-cash fz-1 transform-1"></i>
                        </div>
                        <div class="dashboard-card__txt">
                            <span class="dashboard-card__title">@lang('Balance')</span>
                            <span class="dashboard-card__number">{{ $setting->cur_sym . showAmount($user->balance) }}</span>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('user.deposit.history') }}" class="dashboard-card">
                        <div class="dashboard-card__icon">
                            <i class="ti ti-wallet fz-1 transform-1"></i>
                        </div>
                        <div class="dashboard-card__txt">
                            <span class="dashboard-card__title">@lang('Deposit')</span>
                            <span class="dashboard-card__number">{{ $setting->cur_sym . showAmount($depositAmount) }}</span>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('user.withdraw.history') }}" class="dashboard-card">
                        <div class="dashboard-card__icon">
                            <i class="ti ti-building-bank fz-1 transform-1"></i>
                        </div>
                        <div class="dashboard-card__txt">
                            <span class="dashboard-card__title">@lang('Withdraw')</span>
                            <span class="dashboard-card__number">{{ $setting->cur_sym . showAmount($withdrawalAmount) }}</span>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('user.transactions') }}" class="dashboard-card">
                        <div class="dashboard-card__icon">
                            <i class="ti ti-arrows-right-left fz-1 transform-1"></i>
                        </div>
                        <div class="dashboard-card__txt">
                            <span class="dashboard-card__title">@lang('Transactions')</span>
                            <span class="dashboard-card__number">{{ $user->transactions_count }}</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Completed Jobs Overview') {{ '(' . date('Y') . ')' }}</h3>
                </div>
                <div class="card-body p-0">
                    <div id="jobsCompleted"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <table class="table no-shadow table--striped table-borderless table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('Job Code')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Date')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEarnings->sortByDesc('id') as $recentEarning)
                        <tr>
                            <td>{{ $recentEarning->job->job_code }}</td>
                            <td>
                                @php echo $recentEarning->status_badge @endphp
                            </td>
                            <td>
                                @if($recentEarning->status == ManageStatus::ASSIGNED_JOB_COMPLETED)
                                    @php $compensation = $recentEarning->job->quantity * $recentEarning->job->rate @endphp

                                    <span class="fw-bold">{{ $setting->cur_sym . showAmount($compensation) }}</span>
                                @else
                                    <span class="fw-bold">{{ $setting->cur_sym . showAmount($recentEarning->settled_freelancer_amount) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($recentEarning->status == ManageStatus::ASSIGNED_JOB_COMPLETED)
                                    {{ showDateTime($recentEarning->completed_at, 'd M, Y') }}
                                @else
                                    {{ showDateTime($recentEarning->settled_at, 'd M, Y') }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/apexcharts.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                if ($('#jobsCompleted').length) {
                    const currentYear = new Date().getFullYear()
                    const monthLabels = []

                    for (let i = 0; i < 12; i++) {
                        const monthName = new Date(currentYear, i).toLocaleString('en-US', {month: 'short'})
                        monthLabels.push(`${monthName}`)
                    }

                    const baseColor = getComputedStyle(document.documentElement).getPropertyValue('--base')

                    let jobCompletedOptions = {
                        series: [{
                            name: "Completed Jobs",
                            color: 'hsl(' + baseColor + ')',
                            data: @json($completedJobs)
                        }],
                        chart: {
                            height: 450,
                            type: 'line',
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        grid: {
                            row: {
                                colors: ['#f3f3f3', 'transparent'],
                                opacity: 0.5
                            },
                        },
                        xaxis: {
                            categories: monthLabels
                        }
                    }

                    let chart = new ApexCharts(document.querySelector('#jobsCompleted'), jobCompletedOptions)
                    chart.render()
                }
            })
        })(jQuery)
    </script>
@endpush
