<?php

namespace App\Providers;

use App\Models\JobConversation;
use App\Models\JobPost;
use App\Models\User;
use App\Models\Contact;
use App\Models\Deposit;
use App\Models\SiteData;
use App\Models\Withdrawal;
use App\Constants\ManageStatus;
use App\Models\AdminNotification;
use App\Models\AssignedJob;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $setting                        = bs();
        $activeTheme                    = activeTheme();
        $shareToView['setting']         = $setting;
        $shareToView['activeTheme']     = $activeTheme;
        $shareToView['activeThemeTrue'] = activeTheme(true);
        $shareToView['emptyMessage']    = 'No data found';

        view()->share($shareToView);

        view()->composer('admin.partials.topbar', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::with('user')
                    ->where('is_read', ManageStatus::NO)
                    ->latest()
                    ->take(10)
                    ->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', ManageStatus::NO)->count(),
            ]);
        });

        view()->composer('admin.partials.sidebar', function ($view) {
            $view->with([
                'bannedUsersCount'            => User::banned()->count(),
                'emailUnconfirmedUsersCount'  => User::emailUnconfirmed()->count(),
                'mobileUnconfirmedUsersCount' => User::mobileUnconfirmed()->count(),
                'kycUnconfirmedUsersCount'    => User::kycUnconfirmed()->count(),
                'kycPendingUsersCount'        => User::kycPending()->count(),
                'pendingDepositsCount'        => Deposit::pending()->count(),
                'pendingWithdrawalsCount'     => Withdrawal::pending()->count(),
                'unansweredContactsCount'     => Contact::where('status', ManageStatus::NO)->count(),
                'pendingJobPostsCount'        => JobPost::pending()->count(),
                'disputedJobCount'            => AssignedJob::disputed()->count(),
                'pendingFreelancersCount'     => User::whereNotNull('kyf_data')->pendingFreelancer()->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = SiteData::where('data_key', 'seo.data')->first();

            $view->with([
                'seo' => $seo ? $seo->data_info : $seo,
            ]);
        });

        if ($setting->enforce_ssl) URL::forceScheme('https');

        Paginator::useBootstrapFour();

        view()->composer($activeTheme . 'layouts.frontend', function ($view) {
            $view->with([
                'breadcrumbContent' => Cache::remember('breadcrumb', 3600, function () {
                    return getSiteData('breadcrumb.content', true);
                }),
            ]);
        });

        Route::model('job', JobPost::class);
        Route::model('conversation', JobConversation::class);
    }
}
