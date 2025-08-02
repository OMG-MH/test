<div class="main-sidebar">
    <form class="header__search d-md-none">
        <span class="header__search__icon"><i class="ti ti-search"></i></span>
        <input type="search" class="header__search__input" placeholder="@lang('Search')..." id="searchInput" autocomplete="off">
        <ul class="search-list d-none"></ul>
    </form>
    <ul class="sidebar-menu scroll">
        <li class="sidebar-item">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ navigationActive('admin.dashboard', 2) }}">
                <span class="nav-icon"><i class="ti ti-dashboard"></i></span>
                <span class="sidebar-txt">@lang('Dashboard')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.job.categories.index') }}" class="sidebar-link {{ navigationActive('admin.job.categories.index', 2) }}">
                <span class="nav-icon"><i class="ti ti-category"></i></span>
                <span class="sidebar-txt">@lang('Job Categories')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.job.subcategories.index') }}" class="sidebar-link {{ navigationActive('admin.job.subcategories.index', 2) }}">
                <span class="nav-icon"><i class="ti ti-sitemap"></i></span>
                <span class="sidebar-txt">@lang('Job Subcategories')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.file.types.index') }}" class="sidebar-link {{ navigationActive('admin.file.types.index', 2) }}">
                <span class="nav-icon"><i class="ti ti-tag"></i></span>
                <span class="sidebar-txt">@lang('File Types')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.jobs*', 2) }}">
                <span class="nav-icon">
                    <i class="ti ti-briefcase"></i>

                    @if($pendingJobPostsCount || $disputedJobCount)
                        <span class="badge bg--danger py-1 px-1"></span>
                    @endif
                </span>
                <span class="sidebar-txt">@lang('Jobs')</span>
            </a>
            <ul class="sidebar-dropdown-menu">
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.jobs.index') }}" class="sidebar-link {{ navigationActive('admin.jobs.index', 1) }}">
                        @lang('All')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.jobs.pending') }}" class="sidebar-link {{ navigationActive('admin.jobs.pending', 1) }}">
                        @lang('Pending')

                        @if($pendingJobPostsCount)
                            <span class="badge badge--danger rounded-1">{{ $pendingJobPostsCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.jobs.approved') }}" class="sidebar-link {{ navigationActive('admin.jobs.approved', 1) }}">
                        @lang('Approved')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.jobs.rejected') }}" class="sidebar-link {{ navigationActive('admin.jobs.rejected', 1) }}">
                        @lang('Rejected')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.jobs.unavailable') }}" class="sidebar-link {{ navigationActive('admin.jobs.unavailable', 1) }}">
                        @lang('Vacancy full')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.jobs.disputed') }}" class="sidebar-link {{ navigationActive('admin.jobs.disputed', 1) }}">
                        @lang('Disputed')

                        @if($disputedJobCount)
                            <span class="badge badge--danger rounded-1">{{ $disputedJobCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.gateway*', 2) }}">
                <span class="nav-icon"><i class="ti ti-credit-card"></i></span>
                <span class="sidebar-txt">@lang('Payment Methods')</span>
            </a>
            <ul class="sidebar-dropdown-menu">
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.gateway.automated.index') }}" class="sidebar-link {{ navigationActive('admin.gateway.automated*', 1) }}">
                        @lang('Automated')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.gateway.manual.index') }}" class="sidebar-link {{ navigationActive('admin.gateway.manual*', 1) }}">
                        @lang('Manual')
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.user*', 2) }}">
                <span class="nav-icon">
                    <i class="ti ti-users"></i>

                    @if($bannedUsersCount || $emailUnconfirmedUsersCount || $mobileUnconfirmedUsersCount || $kycUnconfirmedUsersCount || $kycPendingUsersCount)
                        <span class="badge bg--danger py-1 px-1"></span>
                    @endif
                </span>
                <span class="sidebar-txt">@lang('Users')</span>
            </a>
            <ul class="sidebar-dropdown-menu">
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.user.index') }}" class="sidebar-link {{ navigationActive('admin.user.index', 1) }}">
                        @lang('All')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.user.active') }}" class="sidebar-link {{ navigationActive('admin.user.active', 1) }}">
                        @lang('Active')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.user.banned') }}" class="sidebar-link {{ navigationActive('admin.user.banned', 1) }}">
                        @lang('Banned')

                        @if ($bannedUsersCount)
                            <span class="badge badge--danger rounded-1">{{ $bannedUsersCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.user.kyc.pending') }}" class="sidebar-link {{ navigationActive('admin.user.kyc.pending', 1) }}">
                        @lang('KYC Pending')

                        @if ($kycPendingUsersCount)
                            <span class="badge badge--danger rounded-1">{{ $kycPendingUsersCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.user.kyc.unconfirmed') }}" class="sidebar-link {{ navigationActive('admin.user.kyc.unconfirmed', 1) }}">
                        @lang('KYC Unconfirmed')

                        @if ($kycUnconfirmedUsersCount)
                            <span class="badge badge--danger rounded-1">{{ $kycUnconfirmedUsersCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.user.email.unconfirmed') }}" class="sidebar-link {{ navigationActive('admin.user.email.unconfirmed', 1) }}">
                        @lang('Email Unconfirmed')

                        @if ($emailUnconfirmedUsersCount)
                            <span class="badge badge--danger rounded-1">{{ $emailUnconfirmedUsersCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.user.mobile.unconfirmed') }}" class="sidebar-link {{ navigationActive('admin.user.mobile.unconfirmed', 1) }}">
                        @lang('Mobile Unconfirmed')

                        @if ($mobileUnconfirmedUsersCount)
                            <span class="badge badge--danger rounded-1">{{ $mobileUnconfirmedUsersCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.freelancer*', 2) }}">
                <span class="nav-icon">
                    <i class="ti ti-user-screen"></i>

                    @if($pendingFreelancersCount)
                        <span class="badge bg--danger py-1 px-1"></span>
                    @endif
                </span>
                <span class="sidebar-txt">@lang('Freelancers')</span>
            </a>
            <ul class="sidebar-dropdown-menu">
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.freelancer.index') }}" class="sidebar-link {{ navigationActive('admin.freelancer.index', 1) }}">
                        @lang('All')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.freelancer.pending') }}" class="sidebar-link {{ navigationActive('admin.freelancer.pending', 1) }}">
                        @lang('Pending')

                        @if($pendingFreelancersCount)
                            <span class="badge badge--danger rounded-1">{{ $pendingFreelancersCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.freelancer.active') }}" class="sidebar-link {{ navigationActive('admin.freelancer.active', 1) }}">
                        @lang('Active')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.freelancer.rejected') }}" class="sidebar-link {{ navigationActive('admin.freelancer.rejected', 1) }}">
                        @lang('Rejected')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.freelancer.banned') }}" class="sidebar-link {{ navigationActive('admin.freelancer.banned', 1) }}">
                        @lang('Banned')
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.deposits*', 2) }}">
                <span class="nav-icon">
                    <i class="ti ti-wallet"></i>

                    @if($pendingDepositsCount)
                        <span class="badge bg--danger py-1 px-1"></span>
                    @endif
                </span>
                <span class="sidebar-txt">@lang('Deposits')</span>
            </a>
            <ul class="sidebar-dropdown-menu">
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.deposits.index') }}" class="sidebar-link {{ navigationActive('admin.deposits.index', 1) }}">
                        @lang('All')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.deposits.pending') }}" class="sidebar-link {{ navigationActive('admin.deposits.pending', 1) }}">
                        @lang('Pending')

                        @if($pendingDepositsCount)
                            <span class="badge badge--danger rounded-1">{{ $pendingDepositsCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.deposits.done') }}" class="sidebar-link {{ navigationActive('admin.deposits.done', 1) }}">
                        @lang('Done')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.deposits.cancelled') }}" class="sidebar-link {{ navigationActive('admin.deposits.cancelled', 1) }}">
                        @lang('Cancelled')
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.withdraw*', 2) }}">
                <span class="nav-icon">
                    <i class="ti ti-building-bank"></i>

                    @if($pendingWithdrawalsCount)
                        <span class="badge bg--danger py-1 px-1"></span>
                    @endif
                </span>
                <span class="sidebar-txt">@lang('Withdrawals')</span>
            </a>
            <ul class="sidebar-dropdown-menu">
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.withdraw.method.index') }}" class="sidebar-link {{ navigationActive('admin.withdraw.method.index', 1) }}">
                        @lang('Methods')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.withdraw.index') }}" class="sidebar-link {{ navigationActive('admin.withdraw.index', 1) }}">
                        @lang('All')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.withdraw.pending') }}" class="sidebar-link {{ navigationActive('admin.withdraw.pending', 1) }}">
                        @lang('Pending')

                        @if($pendingWithdrawalsCount)
                            <span class="badge badge--danger rounded-1">{{ $pendingWithdrawalsCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.withdraw.done') }}" class="sidebar-link {{ navigationActive('admin.withdraw.done', 1) }}">
                        @lang('Done')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.withdraw.cancelled') }}" class="sidebar-link {{ navigationActive('admin.withdraw.cancelled', 1) }}">
                        @lang('Cancelled')
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.transaction.index') }}" class="sidebar-link {{ navigationActive('admin.transaction.index', 2) }}">
                <span class="nav-icon"><i class="ti ti-arrows-right-left"></i></span>
                <span class="sidebar-txt">@lang('Transactions')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.contact.index') }}" class="sidebar-link {{ navigationActive('admin.contact.index', 2) }}">
                <span class="nav-icon">
                    <i class="ti ti-id"></i>

                    @if($unansweredContactsCount)
                        <span class="badge bg--danger py-1 px-1"></span>
                    @endif
                </span>
                <span class="sidebar-txt">@lang('Contacts')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.basic.setting') }}" class="sidebar-link {{ navigationActive('admin.basic.setting', 2) }}">
                <span class="nav-icon"><i class="ti ti-settings"></i></span>
                <span class="sidebar-txt">@lang('Basic Settings')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.notification*', 2) }}">
                <span class="nav-icon"><i class="ti ti-mail"></i></span>
                <span class="sidebar-txt">@lang('Email & SMS')</span>
            </a>
            <ul class="sidebar-dropdown-menu">
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.notification.universal') }}" class="sidebar-link {{ navigationActive('admin.notification.universal', 1) }}">
                        @lang('Universal Template')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.notification.email') }}" class="sidebar-link {{ navigationActive('admin.notification.email', 1) }}">
                        @lang('Email Config')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.notification.sms') }}" class="sidebar-link {{ navigationActive('admin.notification.sms', 1) }}">
                        @lang('SMS Config')
                    </a>
                </li>
                <li class="sidebar-dropdown-item">
                    <a href="{{ route('admin.notification.templates') }}" class="sidebar-link {{ navigationActive('admin.notification.templates', 1) }}">
                        @lang('All Templates')
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.plugin.setting') }}" class="sidebar-link {{ navigationActive('admin.plugin.setting', 2) }}">
                <span class="nav-icon"><i class="ti ti-plug"></i></span>
                <span class="sidebar-txt">@lang('Plugins')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.language.index') }}" class="sidebar-link {{ navigationActive('admin.language*', 2) }}">
                <span class="nav-icon"><i class="ti ti-language"></i></span>
                <span class="sidebar-txt">@lang('Language')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.seo.setting') }}" class="sidebar-link {{ navigationActive('admin.seo.setting', 2) }}">
                <span class="nav-icon"><i class="ti ti-seo"></i></span>
                <span class="sidebar-txt">@lang('SEO')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.kyc.setting') }}" class="sidebar-link {{ navigationActive('admin.kyc*', 2) }}">
                <span class="nav-icon"><i class="ti ti-user-scan"></i></span>
                <span class="sidebar-txt">@lang('KYC')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.kyf.settings') }}" class="sidebar-link {{ navigationActive('admin.kyf*', 2) }}">
                <span class="nav-icon"><i class="ti ti-user-hexagon"></i></span>
                <span class="sidebar-txt">@lang('KYF')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.site.themes') }}" class="sidebar-link {{ navigationActive('admin.site.themes', 2) }}">
                <span class="nav-icon"><i class="ti ti-template"></i></span>
                <span class="sidebar-txt">@lang('Themes')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a role="button" class="sidebar-link has-sub {{ navigationActive('admin.site.sections*', 2) }}">
                <span class="nav-icon"><i class="ti ti-layout-grid-add"></i></span>
                <span class="sidebar-txt">@lang('Site Content')</span>
            </a>
            <ul class="sidebar-dropdown-menu">
                @php $lastSegment = collect(request()->segments())->last() @endphp

                @foreach(getPageSections(true) as $key => $section)
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.site.sections', $key) }}" @class(['sidebar-link', 'active' => $lastSegment == $key])>
                            {{ __($section['name']) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.cookie.setting') }}" class="sidebar-link {{ navigationActive('admin.cookie.setting', 2) }}">
                <span class="nav-icon"><i class="ti ti-cookie"></i></span>
                <span class="sidebar-txt">@lang('GDPR Cookie')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('admin.maintenance.setting') }}" class="sidebar-link {{ navigationActive('admin.maintenance.setting', 2) }}">
                <span class="nav-icon"><i class="ti ti-tool"></i></span>
                <span class="sidebar-txt">@lang('Maintenance')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#cacheClearModal" class="sidebar-link" data-bs-toggle="modal">
                <span class="nav-icon"><i class="ti ti-eraser"></i></span>
                <span class="sidebar-txt">@lang('Cache Clear')</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#systemInfoModal" class="sidebar-link" data-bs-toggle="modal">
                <span class="nav-icon"><i class="ti ti-info-square-rounded"></i></span>
                <span class="sidebar-txt">@lang('System Info')</span>
            </a>
        </li>
    </ul>
</div>

<div class="custom--modal modal fade" id="cacheClearModal" tabindex="-1" aria-labelledby="cacheClearModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="cacheClearModalLabel">@lang('Flush System Cache')</h2>
                <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <form action="{{ route('admin.cache.clear') }}" method="GET">
                <div class="modal-body">
                    <ul class="cache-clear-list">
                        <li>@lang('The cache containing compiled views will be removed')</li>
                        <li>@lang('The cache containing application will be removed')</li>
                        <li>@lang('The cache containing route will be removed')</li>
                        <li>@lang('The cache containing configuration will be removed')</li>
                        <li>@lang('The cache containing system will be removed')</li>
                        <li>@lang('Clearing out the compiled service and package files')</li>
                    </ul>
                </div>
                <div class="modal-footer gap-2">
                    <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--sm btn--base">@lang('Clear')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="custom--modal modal fade" id="systemInfoModal" tabindex="-1" aria-labelledby="systemInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="systemInfoModalLabel">@lang('System Information')</h2>
                <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <nav>
                    <div class="custom--tab nav nav-tabs flex-nowrap mb-3" role="tablist">
                        <button class="nav-link w-100 active" id="nav-application-tab" data-bs-toggle="tab" data-bs-target="#nav-application" type="button" role="tab" aria-controls="nav-application" aria-selected="true">
                            @lang('Application')
                        </button>
                        <button class="nav-link w-100" id="nav-server-tab" data-bs-toggle="tab" data-bs-target="#nav-server" type="button" role="tab" aria-controls="nav-server" aria-selected="false">
                            @lang('Server')
                        </button>
                    </div>
                </nav>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="nav-application" role="tabpanel" aria-labelledby="nav-application-tab" tabindex="0">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">{{ systemDetails()['name'] }} @lang('Version')</td>
                                    <td>{{ systemDetails()['version'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Build Version')</td>
                                    <td>{{ systemDetails()['build_version'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Laravel Version')</td>
                                    <td>{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Timezone')</td>
                                    <td>{{ config('app.timezone') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="nav-server" role="tabpanel" aria-labelledby="nav-server-tab" tabindex="0">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">@lang('PHP Version')</td>
                                    <td>{{ phpversion() }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Server Software')</td>
                                    <td>{{ @$_SERVER['SERVER_SOFTWARE'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Server IP Address')</td>
                                    <td>{{ @$_SERVER['SERVER_ADDR'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Server Protocol')</td>
                                    <td>{{ @$_SERVER['SERVER_PROTOCOL'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('HTTP Host')</td>
                                    <td>{{ @$_SERVER['HTTP_HOST'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">@lang('Server Port')</td>
                                    <td>{{ @$_SERVER['SERVER_PORT'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
