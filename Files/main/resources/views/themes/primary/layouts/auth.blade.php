@extends($activeTheme . 'layouts.app')

@section('content')
    <header class="header-2">
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="logo">
            </a>
            <button type="button" class="sidebar-toggler">
                <span id="hiddenNav"><i class="ti ti-menu-2 fz-1 transform-1"></i></span>
            </button>
        </div>
        <div class="header-menu">
            <div class="account-info">
                <span class="page-name">{{ __($pageTitle) }}</span>
            </div>
            <div class="header-2__user d-md-flex align-items-center gap-4 d-none">
                @if($user->freelancer_status == ManageStatus::FREELANCER_NOT)
                    <a href="{{ route('user.freelancer.profile') }}" class="btn btn--sm btn--secondary py-1">
                        @lang('Start Freelancing')
                    </a>
                @endif

                <div class="d-flex align-items-center gap-2">
                    <div class="header-2__user__txt">
                        @if($user->freelancer_status == ManageStatus::FREELANCER_ACTIVE)
                            <a href="{{ route('freelancer.show', ['username' => $user->username]) }}" class="header-2__user__username">
                                {{ '@' . $user->username }}
                            </a>
                        @else
                            <span class="header-2__user__username">{{ '@' . $user->username }}</span>
                        @endif

                        <span class="header-2__user__balance">@lang('Balance'): <strong>{{ showAmount($user->balance) . ' ' . $setting->site_cur }}</strong></span>
                    </div>
                    <div class="header-2__user__img">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, null, true) }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="dashboard">
        <div class="sidebar-overlay-2"></div>
        <div class="main-sidebar">
            <div class="main-sidebar__user-wrap d-md-none">
                <div class="main-sidebar__user">
                    <div class="main-sidebar__user__img">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, null, true) }}" alt="image">
                    </div>
                    <div class="main-sidebar__user__txt">
                        @if($user->freelancer_status == ManageStatus::FREELANCER_ACTIVE)
                            <a href="{{ route('freelancer.show', ['username' => $user->username]) }}">
                                <span class="main-sidebar__user__username">{{ '@' . $user->username }}</span>
                            </a>
                        @else
                            <span class="main-sidebar__user__username">{{ '@' . $user->username }}</span>
                        @endif

                        <span class="main-sidebar__user__balance">@lang('Balance'): <strong>{{ showAmount($user->balance) . ' ' . $setting->site_cur }}</strong></span>
                    </div>
                </div>

                @if($user->freelancer_status == ManageStatus::FREELANCER_NOT)
                    <a href="{{ route('user.freelancer.profile') }}" class="btn btn--sm btn--secondary py-1 d-md-none">
                        @lang('Start Freelancing')
                    </a>
                @endif
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="{{ route('user.home') }}" class="sidebar-link">
                        <span class="nav-icon"><i class="ti ti-layout-dashboard"></i></span>
                        <span class="sidebar-txt">@lang('Dashboard')</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a role="button" class="sidebar-link has-sub">
                        <span class="nav-icon"><i class="ti ti-briefcase"></i></span>
                        <span class="sidebar-txt">@lang('Job')</span>
                    </a>
                    <ul class="sidebar-dropdown-menu">
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.job.create') }}" class="sidebar-link">@lang('Create Job')</a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.job.history') }}" class="sidebar-link">@lang('Job History')</a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.assigned.jobs') }}" class="sidebar-link">@lang('Assigned Jobs')</a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.disputed.jobs') }}" class="sidebar-link">@lang('Disputed Jobs')</a>
                        </li>
                    </ul>
                </li>

                @if($user->freelancer_status == ManageStatus::FREELANCER_ACTIVE)
                    <li class="sidebar-item">
                        <a role="button" class="sidebar-link has-sub">
                            <span class="nav-icon"><i class="ti ti-devices-pc"></i></span>
                            <span class="sidebar-txt">@lang('Workspace')</span>
                        </a>
                        <ul class="sidebar-dropdown-menu">
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.workspace.applied.jobs') }}" class="sidebar-link">@lang('Applied Jobs')</a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.workspace.ongoing.jobs') }}" class="sidebar-link">@lang('Ongoing Jobs')</a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.workspace.completed.jobs') }}" class="sidebar-link">@lang('Completed Jobs')</a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('user.workspace.disputed.jobs') }}" class="sidebar-link">@lang('Disputed Jobs')</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="sidebar-item">
                    <a role="button" class="sidebar-link has-sub">
                        <span class="nav-icon"><i class="ti ti-wallet"></i></span>
                        <span class="sidebar-txt">@lang('Deposit')</span>
                    </a>
                    <ul class="sidebar-dropdown-menu">
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.deposit') }}" class="sidebar-link">@lang('Deposit Money')</a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.deposit.history') }}" class="sidebar-link">@lang('Deposit History')</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a role="button" class="sidebar-link has-sub">
                        <span class="nav-icon"><i class="ti ti-building-bank"></i></span>
                        <span class="sidebar-txt">@lang('Withdraw')</span>
                    </a>
                    <ul class="sidebar-dropdown-menu">
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.withdraw') }}" class="sidebar-link">@lang('Withdraw Money')</a>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="{{ route('user.withdraw.history') }}" class="sidebar-link">@lang('Withdraw History')</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('user.transactions') }}" class="sidebar-link">
                        <span class="nav-icon"><i class="ti ti-arrows-right-left"></i></span>
                        <span class="sidebar-txt">@lang('Transactions')</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('user.profile') }}" class="sidebar-link">
                        <span class="nav-icon"><i class="ti ti-user-square-rounded"></i></span>
                        <span class="sidebar-txt">@lang('Profile Settings')</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('user.freelancer.profile') }}" class="sidebar-link">
                        <span class="nav-icon"><i class="ti ti-user-screen"></i></span>
                        <span class="sidebar-txt">@lang('Freelancer Profile')</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('user.change.password') }}" class="sidebar-link">
                        <span class="nav-icon"><i class="ti ti-key"></i></span>
                        <span class="sidebar-txt">@lang('Change Password')</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('user.twofactor.form') }}" class="sidebar-link">
                        <span class="nav-icon"><i class="ti ti-user-shield"></i></span>
                        <span class="sidebar-txt">@lang('2FA Settings')</span>
                    </a>
                </li>
            </ul>
            <a href="{{ route('user.logout') }}" class="logout-btn btn btn--sm">
                <i class="ti ti-logout"></i> @lang('Log Out')
            </a>
        </div>
        <div class="main-content">
            @yield('auth')
        </div>
    </div>

    @stack('user-panel-modal')
@endsection
