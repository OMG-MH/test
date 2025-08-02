<?php

use Illuminate\Support\Facades\Route;

Route::middleware('admin.guest')->namespace('Auth')->group(function () {
    // Admin Login and Logout Process
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'loginForm')->name('login.form');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->withoutMiddleware('admin.guest')->middleware('admin')->name('logout');
    });

    // Admin Forgot Password and Verification Process
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('forgot', 'requestForm')->name('request.form');
        Route::post('forgot', 'sendResetCode');
        Route::get('verification/form', 'verificationForm')->name('code.verification.form');
        Route::post('verification/form', 'verificationCode');
    });

    // Admin Reset Password
    Route::controller('ResetPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset/form/{email}/{code}', 'resetForm')->name('reset.form');
        Route::post('reset', 'resetPassword')->name('reset');
    });
});

// Operations for Admin
Route::middleware('admin')->group(function () {
    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate');
        Route::post('password', 'passwordChange')->name('password.update');

        // Notification
        Route::name('system.notification.')->prefix('notification')->group(function () {
            Route::get('all', 'allNotification')->name('all');
            Route::get('{id}/read', 'notificationRead')->name('read');
            Route::post('read-all', 'notificationReadAll')->name('read.all');
            Route::post('{id}/remove', 'notificationRemove')->name('remove');
            Route::post('remove-all', 'notificationRemoveAll')->name('remove.all');
        });

        // Transactions
        Route::get('transaction', 'transaction')->name('transaction.index');

        // File Download
        Route::get('file-download', 'fileDownload')->name('file.download');
    });

    // User Management
    Route::controller('UserController')->name('user.')->prefix('user')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('banned', 'banned')->name('banned');
        Route::get('kyc-pending', 'kycPending')->name('kyc.pending');
        Route::get('kyc-unconfirmed', 'kycUnConfirmed')->name('kyc.unconfirmed');
        Route::get('email-unconfirmed', 'emailUnConfirmed')->name('email.unconfirmed');
        Route::get('mobile-unconfirmed', 'mobileUnConfirmed')->name('mobile.unconfirmed');

        // User KYC Operation
        Route::post('{id}/kyc-approve', 'kycApprove')->name('kyc.approve');
        Route::post('{id}/kyc-cancel', 'kycCancel')->name('kyc.cancel');

        // User Details Operation
        Route::get('{id}/details', 'details')->name('details');
        Route::post('{id}/update', 'update')->name('update');
        Route::get('{id}/login', 'login')->name('login');
        Route::post('{id}/balance-update', 'balanceUpdate')->name('add.sub.balance');
        Route::post('{id}/status', 'status')->name('status');
    });

    // Freelancer Management
    Route::controller('FreelancerController')->prefix('freelancer')->name('freelancer.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('pending', 'pending')->name('pending');
        Route::get('active', 'active')->name('active');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('banned', 'banned')->name('banned');
        Route::prefix('{id}')->group(function () {
            Route::post('accept', 'accept')->name('accept');
            Route::post('reject', 'reject')->name('reject');
            Route::post('ban', 'ban')->name('ban');
            Route::post('unban', 'unban')->name('unban');
        });
    });

    // Job Category
    Route::controller('JobCategoryController')->prefix('job-categories')->name('job.categories.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('{id}/status', 'status')->name('status');
        Route::post('{id}/featured', 'updateFeatured')->name('featured');
    });

    // Job Subcategory
    Route::controller('JobSubcategoryController')->prefix('job-subcategories')->name('job.subcategories.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('{id}/status', 'status')->name('status');
    });

    // File Types
    Route::controller('FileTypeController')->prefix('file-types')->name('file.types.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('{id}/status', 'status')->name('status');
    });

    // Job
    Route::controller('JobPostController')->group(function () {
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('pending', 'pending')->name('pending');
            Route::get('approved', 'approved')->name('approved');
            Route::get('rejected', 'rejected')->name('rejected');
            Route::get('vacancy-full', 'unavailable')->name('unavailable');
            Route::get('disputed', 'disputedJobs')->name('disputed');
        });
        
        Route::prefix('job/{job}')->name('job.')->group(function () {
            Route::get('', 'show')->name('show');
            Route::match(['get', 'post'], 'approve', 'approve')->name('approve');
            Route::match(['get', 'post'], 'reject', 'reject')->name('reject');
            Route::get('applicants', 'applicants')->name('applicants');
        });
    });

    // Job Dispute
    Route::controller('JobDisputeController')->group(function () {
        Route::prefix('job/{job}')->name('job.')->group(function () {
            Route::get('disputes', 'disputes')->name('disputes');
            Route::get('dispute/{id}', 'disputeDetails')->name('dispute.show');
        });
        Route::prefix('dispute')->name('dispute.')->group(function () {
            Route::post('send-message', 'sendMessage')->name('send.message');
            Route::get('conversation/{conversation}/file', 'downloadFile')->name('conversation.file');
            Route::get('fetch-message', 'fetchMessage')->name('fetch.message');
            Route::post('{id}/take-action', 'takeAction')->name('take.action');
        });
    });

    // Payment Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {
        // Automated Gateway
        Route::controller('AutomatedGatewayController')->prefix('automated')->name('automated.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });

        // Manual Gateway
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'new')->name('new');
            Route::post('store/{id?}', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    // Deposit Management
    Route::controller('DepositController')->prefix('deposits')->name('deposits.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('pending', 'pending')->name('pending');
        Route::get('done', 'done')->name('done');
        Route::get('cancelled', 'cancelled')->name('cancelled');
        Route::post('approve/{id}', 'approve')->name('approve');
        Route::post('reject/{id}', 'reject')->name('reject');
    });

    // Withdrawal Management
    Route::name('withdraw.')->prefix('withdraw')->group(function () {
        // Withdraw Method
        Route::controller('WithdrawMethodController')->prefix('method')->name('method.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'new')->name('new');
            Route::post('store/{id?}', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('status/{id}', 'status')->name('status');
        });

        // Withdrawal
        Route::controller('WithdrawController')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('pending', 'pending')->name('pending');
            Route::get('done', 'done')->name('done');
            Route::get('cancelled', 'cancelled')->name('cancelled');
            Route::post('approve', 'approve')->name('approve');
            Route::post('reject', 'reject')->name('reject');
        });
    });

    // Contact
    Route::controller('ContactController')->group(function () {
        Route::prefix('contact')->name('contact.')->group(function () {
            Route::get('/', 'contactIndex')->name('index');
            Route::post('remove/{id}', 'contactRemove')->name('remove');
            Route::post('status/{id}', 'contactStatus')->name('status');
        });
    });

    // Setting
    Route::controller('SettingController')->group(function () {
        Route::prefix('setting')->group(function () {
            // Basic Setting
            Route::prefix('basic-settings')->group(function () {
                Route::get('', 'basic')->name('basic.setting');
                Route::post('', 'basicUpdate');
                Route::post('system', 'systemUpdate')->name('basic.setting.system');
                Route::post('logo-favicon', 'logoFaviconUpdate')->name('basic.setting.logo.favicon');
            });

            // Plugin Setting
            Route::name('plugin.')->group(function () {
                Route::get('plugin-settings', 'plugin')->name('setting');
                Route::post('plugin/{id}/update', 'pluginUpdate')->name('update');
                Route::post('plugin/{id}/status', 'pluginStatus')->name('status');
            });

            // SEO Setting
            Route::get('seo-settings', 'seo')->name('seo.setting');

            // KYC Setting
            Route::get('kyc-settings', 'kyc')->name('kyc.setting');
            Route::post('kyc-settings', 'kycUpdate');

            // Know Your Freelancer
            Route::get('kyf-settings', 'kyf')->name('kyf.settings');
            Route::post('kyf-settings', 'kyfUpdate');
        });

        // Cookie
        Route::get('cookie-policy', 'cookie')->name('cookie.setting');
        Route::post('cookie-policy', 'cookieUpdate');

        // Maintenance
        Route::get('maintenance-mode', 'maintenance')->name('maintenance.setting');
        Route::post('maintenance-mode', 'maintenanceUpdate');

        // Cache Clear
        Route::get('cache-clear', 'cacheClear')->name('cache.clear');
    });

    // Email & SMS Setting
    Route::controller('NotificationController')->prefix('notification')->name('notification.')->group(function () {
        // Template Setting
        Route::get('universal', 'universal')->name('universal');
        Route::post('universal', 'universalUpdate');
        Route::get('templates', 'templates')->name('templates');
        Route::get('template/{id}/edit', 'templateEdit')->name('template.edit');
        Route::post('template/{id}/update', 'templateUpdate')->name('template.update');

        // Email Setting
        Route::get('email-settings', 'email')->name('email');
        Route::post('email-settings', 'emailUpdate');
        Route::post('email-test', 'testEmail')->name('email.test');

        // SMS Setting
        Route::get('sms-settings', 'sms')->name('sms');
        Route::post('sms-settings', 'smsUpdate');
        Route::post('sms-test', 'testSMS')->name('sms.test');
    });

    // Language Setting
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('keywords', 'keywords')->name('keywords');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('{id}/status', 'status')->name('status');
        Route::post('{id}/delete', 'delete')->name('delete');
        Route::get('{id}/translate-keyword', 'translateKeyword')->name('translate.keyword');
        Route::post('import', 'languageImport')->name('import.lang');
        Route::post('{id}/store-key', 'languageKeyStore')->name('store.key');
        Route::post('{id}/update-key', 'languageKeyUpdate')->name('update.key');
        Route::post('{id}/delete-key', 'languageKeyDelete')->name('delete.key');
    });

    // Frontend
    Route::controller('SiteController')->prefix('site')->name('site.')->group(function () {
        Route::get('themes', 'themes')->name('themes');
        Route::post('themes', 'makeActive');
        Route::get('sections/{key}', 'sections')->name('sections');
        Route::post('content/{key}', 'content')->name('sections.content');
        Route::get('element/{key}/{id?}', 'element')->name('sections.element');
        Route::post('{id}/remove', 'remove')->name('remove');
    });
});
