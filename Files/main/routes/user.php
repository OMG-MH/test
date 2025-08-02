<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->namespace('User\Auth')->name('user.')->group(function () {
    // User Login and Logout Process
    Route::controller('LoginController')->group(function () {
        Route::get('login', 'loginForm')->name('login.form');
        Route::post('login', 'login')->name('login');
        Route::get('logout', 'logout')->withoutMiddleware('guest')->middleware('auth')->name('logout');
    });

    // User Registration Process
    Route::middleware('register.status')->controller('RegisterController')->group(function () {
        Route::get('register', 'registerForm')->name('register.form');
        Route::post('register', 'register');
        Route::post('check-user', 'checkUser')->withoutMiddleware('register.status')->name('check.user');
    });

    // Forgot Password
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('forgot', 'requestForm')->name('request.form');
        Route::post('forgot', 'sendResetCode');
        Route::get('verification/form', 'verificationForm')->name('code.verification.form');
        Route::post('verification/form', 'verificationCode');
    });

    // Reset Password
    Route::controller('ResetPasswordController')->prefix('password/reset')->name('password.')->group(function () {
        Route::get('form/{token}', 'resetForm')->name('reset.form');
        Route::post('/', 'resetPassword')->name('reset');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    Route::namespace('User')->group(function () {
        // Authorization
        Route::controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorizeForm')->name('authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
            Route::post('verify-email', 'emailVerification')->name('verify.email');
            Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
            Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
        });

        Route::middleware('authorize.status')->group(function () {
            Route::controller('UserController')->group(function () {
                // Dashboard
                Route::get('dashboard', 'home')->name('home');

                // KYC Check
                Route::prefix('kyc')->name('kyc.')->group(function () {
                    Route::get('data', 'kycData')->name('data');
                    Route::get('form', 'kycForm')->name('form');
                    Route::post('form', 'kycSubmit');
                });

                // Profile Update
                Route::get('profile', 'profile')->name('profile');
                Route::post('profile', 'profileUpdate');
                Route::get('freelancer-profile', 'freelancerProfile')->name('freelancer.profile');
                Route::post('freelancer-profile', 'freelancerProfileUpdate');

                // Password Change
                Route::get('change-password', 'password')->name('change.password');
                Route::post('change-password', 'passwordChange');

                // 2 Factor Authenticator
                Route::prefix('twofactor')->name('twofactor.')->group(function () {
                    Route::get('/', 'show2faForm')->name('form');
                    Route::post('enable', 'enable2fa')->name('enable');
                    Route::post('disable', 'disable2fa')->name('disable');
                });

                // Deposit
                Route::get('deposit', 'deposit')->name('deposit');
                Route::get('deposit-history', 'depositHistory')->name('deposit.history');

                // Transactions
                Route::get('transactions', 'transactions')->name('transactions');

                // File Download
                Route::get('file-download', 'fileDownload')->name('file.download');
            });

            // Job
            Route::controller('JobPostController')->prefix('job')->name('job.')->group(function () {
                Route::get('history', 'index')->name('history');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::get('{job}/edit', 'edit')->name('edit');
                Route::get('{job}/attachment', 'downloadAttachment')->name('attachment');
                Route::match(['get', 'post'], '{job}/update', 'update')->name('update');
                Route::get('{job}/applicants', 'applicants')->name('applicants');
                Route::match(['get', 'post'], '{job}/pause', 'pause')->name('pause');
                Route::match(['get', 'post'], '{job}/resume', 'resume')->name('resume');
            });

            Route::controller('JobApplicationController')
                ->prefix('job/{job}/application/{jobApplication}')
                ->name('job.application')
                ->group(function () {
                    Route::get('', 'applicationShow');
                    Route::match(['get', 'post'], 'accept', 'applicationAccept')->name('.accept');
                    Route::match(['get', 'post'], 'reject', 'applicationReject')->name('.reject');
                });

            // Assigned Jobs
            Route::controller('AssignedJobController')->group(function () {
                Route::get('assigned-jobs', 'index')->name('assigned.jobs');
                Route::prefix('assigned-job/{assignedJob}')->name('assigned.job.')->group(function () {
                    Route::get('', 'show')->name('show');
                    Route::match(['get', 'post'], 'complete', 'complete')->name('complete');
                    Route::match(['get', 'post'], 'dispute', 'dispute')->name('dispute');
                    Route::match(['get', 'post'], 'share-feedback', 'shareFeedback')->name('share.feedback');
                });
                Route::get('disputed-jobs', 'disputedJobs')->name('disputed.jobs');
                Route::prefix('disputed-job/{assignedJob}')->name('disputed.job.')->group(function () {
                    Route::get('', 'show')->name('show');
                    Route::match(['get', 'post'], 'share-feedback', 'shareFeedback')->name('share.feedback');
                });
            });

            // Job Conversation
            Route::controller('JobConversationController')->prefix('job')->name('job.')->group(function () {
                Route::post('send-message', 'sendMessage')->name('send.message');
                Route::get('conversation/{conversation}/file', 'downloadFile')->name('conversation.file');
                Route::get('fetch-message', 'fetchMessage')->name('fetch.message');
            });

            // Workspace
            Route::controller('WorkspaceController')
                ->prefix('workspace')
                ->name('workspace.')
                ->middleware('freelancer.ensure')
                ->group(function () {
                    Route::get('applied-jobs', 'appliedJobs')->name('applied.jobs');
                    Route::get('ongoing-jobs', 'ongoingJobs')->name('ongoing.jobs');
                    Route::post('ongoing-job/{id}/dispute', 'ongoingJobDispute')->name('ongoing.job.dispute');
                    Route::get('completed-jobs', 'completedJobs')->name('completed.jobs');
                    Route::get('disputed-jobs', 'disputedJobs')->name('disputed.jobs');
                    Route::get('job/{id}', 'jobShow')->name('job.show');
                });

            // Withdraw
            Route::prefix('withdraw')->controller('WithdrawController')->middleware('kyc.status')->group(function () {
                Route::get('', 'withdraw')->name('withdraw');
                Route::post('', 'store');
                Route::get('preview', 'preview')->name('withdraw.preview');
                Route::post('preview', 'submit');
                Route::get('withdraw-history', 'withdrawHistory')->name('withdraw.history')->withoutMiddleware('kyc.status');
            });
        });
    });

    // Deposit
    Route::middleware('authorize.status')
        ->prefix('deposit')
        ->name('deposit.')
        ->controller('Gateway\PaymentController')
        ->group(function () {
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::prefix('manual')->name('manual.')->group(function () {
                Route::get('', 'manualDepositConfirm')->name('confirm');
                Route::post('', 'manualDepositUpdate')->name('update');
            });
        });
});
