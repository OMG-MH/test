<?php

use Illuminate\Support\Facades\Route;

Route::controller('WebsiteController')->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('job-categories', 'jobCategories')->name('job.categories');
    Route::get('jobs', 'jobs')->name('jobs');
    Route::get('job/{job}', 'jobShow')->name('job.show');
    Route::match(['get', 'post'], 'job/{job}/apply', 'jobApply')->name('job.apply');
    Route::get('freelancers', 'freelancers')->name('freelancers');
    Route::get('freelancer/{username}', 'freelancerShow')->name('freelancer.show');
    Route::get('freelancer/{username}/fetch-reviews', 'freelancerReviews')->name('freelancer.reviews');
    Route::get('blog', 'blog')->name('blog');
    Route::get('blog/{name}/{id}', 'blogShow')->name('blog.show');
    Route::get('faq', 'faq')->name('faq');
    Route::get('contact', 'contact')->name('contact');
    Route::post('contact', 'contactStore');

    // Cookie
    Route::get('cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    // Language
    Route::get('change-language/{lang?}', 'changeLanguage')->name('lang');

    // Policy Details
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
});
