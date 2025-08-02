@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="job-details py-120">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-8 col-lg-7">
                    <div class="job-details__img">
                        <img src="{{ getImage(getFilePath('job') . '/' . $job->image, getFileSize('job')) }}" alt="@lang('image')">
                    </div>
                    <div class="blog-details__txt">
                        <h2 class="blog-details__title">{{ __($job->title) }}</h2>
                        <div class="styled-list-parent">
                            @php echo $job->description @endphp
                        </div>
                    </div>

                    @if($job->job_attachment)
                        <div class="job-details__document">
                            <h3 class="blog-details__subtitle">@lang('Job Attachment')</h3>
                            <object class="job-details__attachment" data="{{ asset(getFilePath('jobAttachment') . '/' . $job->job_attachment) }}" type="application/pdf"></object>
                        </div>
                    @endif

                    @if(auth()->check())
                        @if($jobApplicationExists)
                            <div class="alert alert--info mt-4" role="alert">
                                <span class="alert__title">@lang('Application Already Submitted')</span>
                                <p class="alert__desc">@lang('It looks like you have already applied for this job.') <a href="{{ route('user.workspace.applied.jobs') }}" class="alert__link">@lang('Click here')</a> @lang('to view your applied jobs.')</p>
                            </div>
                        @elseif( ($job->user->id != auth()->id()) && (auth()->user()->freelancer_status == ManageStatus::FREELANCER_ACTIVE) )
                            <div class="blog-details__post-comment">
                                <h3 class="blog-details__subtitle">@lang('Apply Now')</h3>
                                <form action="{{ route('job.apply', $job) }}" method="post" class="row g-4" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-12">
                                        <label class="form--label required">@lang('Describe Yourself')</label>
                                        <textarea class="form--control ck-editor" name="applicant_bio">{{ old('applicant_bio') }}</textarea>
                                    </div>

                                    @php $jobProofTypes = implode(', ', $job->file_types) @endphp

                                    <div class="col-12">
                                        <label class="form--label">@lang('Job Proof') @if($jobProofTypes) <small>{{ '(' . $jobProofTypes . ')' }}</small> @endif</label>
                                        <input type="file" class="form--control form--control--sm" name="job_proof" accept="{{ $jobProofTypes }}">
                                    </div>
                                    <div class="col-12 d-flex justify-content-center">
                                        <button type="submit" class="btn btn--sm btn--base px-3">@lang('Submit Application')</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('user.login.form', ['redirect' => url()->current()]) }}" class="btn btn--base px-3">@lang('Login to Apply')</a>
                        </div>
                    @endif
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="post-sidebar">
                        <div class="post-sidebar__card">
                            <h3 class="post-sidebar__card__header">@lang('Job Information')</h3>
                            <div class="post-sidebar__card__body">
                                <ul class="post-sidebar__job-information">
                                    <li>
                                        <span class="post-sidebar__job-information__icon">
                                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $job->user->image, null, true) }}" alt="image">
                                        </span>
                                        <div class="post-sidebar__job-information__txt">
                                            <span class="post-sidebar__job-information__name">@lang('Job posted by') {{ $job->user->fullname }}</span>
                                            <span class="post-sidebar__job-information__info">@lang('Job Code'): {{ $job->job_code }}</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="post-sidebar__job-information__icon">
                                            <i class="ti ti-cash-banknote fz-2 transform-0"></i>
                                        </span>
                                        <div class="post-sidebar__job-information__txt">
                                            <span class="post-sidebar__job-information__name">@lang('You will earn from this job')</span>
                                            <span class="post-sidebar__job-information__info">{{ $setting->cur_sym . showAmount($job->rate) }} <small>@lang('per job')</small></span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="post-sidebar__job-information__icon">
                                            <i class="ti ti-users-group fz-2 transform-0"></i>
                                        </span>
                                        <div class="post-sidebar__job-information__txt">
                                            <span class="post-sidebar__job-information__name">@lang('Available Vacancy')</span>
                                            <span class="post-sidebar__job-information__info">{{ $job->vacancy }}</span>
                                        </div>
                                    </li>
                                    <li>
                                        <span class="post-sidebar__job-information__icon">
                                            <i class="ti ti-calendar-month fz-2 transform-0"></i>
                                        </span>
                                        <div class="post-sidebar__job-information__txt">
                                            <span class="post-sidebar__job-information__name">@lang('Published Date')</span>
                                            <span class="post-sidebar__job-information__info">{{ showDateTime($job->created_at) }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="post-sidebar__card">
                            <h3 class="post-sidebar__card__header">@lang('Share')</h3>
                            <div class="post-sidebar__card__body">
                                <div class="input--group mb-4">
                                    <input type="text" class="form--control" id="shareLink" value="" readonly>
                                    <button type="button" class="btn btn--base share-link__copy px-3">
                                        <i class="ti ti-copy"></i>
                                    </button>
                                </div>
                                <ul class="social-list">
                                    <li class="social-list__item">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="social-list__link flex-center" target="_blank">
                                            <i class="ti ti-brand-facebook"></i>
                                        </a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="https://twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}" class="social-list__link flex-center" target="_blank">
                                            <i class="ti ti-brand-x"></i>
                                        </a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="https://www.linkedin.com/shareArticle?url={{ urlencode(url()->current()) }}" class="social-list__link flex-center" target="_blank">
                                            <i class="ti ti-brand-linkedin"></i>
                                        </a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="https://pinterest.com/pin/create/bookmarklet/?media={{ $seoContents['image'] }}&url={{ urlencode(url()->current()) }}&is_video=[is_video]&description={{ __($seoContents['social_title']) }}" class="social-list__link flex-center" target="_blank">
                                            <i class="ti ti-brand-pinterest"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
    <style>
        .styled-list-parent ul {
            padding-left: 2rem;
            list-style: disc;
        }

        .styled-list-parent ol {
            padding-left: 2rem;
            list-style: decimal;
        }
    </style>
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/pdfobject.js') }}"></script>
    <script src="{{ asset('assets/universal/js/ckEditor.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            let pdfFIle = $('.job-details__attachment').attr('data')
            PDFObject.embed(pdfFIle, '.job-details__attachment')
        })(jQuery)
    </script>
@endpush
