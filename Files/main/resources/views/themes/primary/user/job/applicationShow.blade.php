@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="job-request-details">
        <div class="row g-4">
            <div class="col-xl-4 col-md-5">
                <div class="custom--card h-auto mb-4">
                    <div class="card-header">
                        <h3 class="title">@lang('Job Info')</h3>
                    </div>
                    <div class="card-body">
                        @php $job = $jobApplication->job @endphp

                        <div class="job-request-details__job-info">
                            <div class="job-request-details__job-info__img">
                                <img src="{{ getImage(getFilePath('job') . '/' . $job->image, getFileSize('job')) }}" alt="Image">
                            </div>
                            <div class="job-request-details__job-info__txt">
                                <h3 class="job-request-details__job-info__title">{{ __(strLimit($job->title, 30)) }}</h3>
                                <ul class="job-request-details__job-info__list">
                                    <li><span class="fw-bold">@lang('Job Code'):</span> {{ $job->job_code }}</li>
                                    <li><span class="fw-bold">@lang('Job Quantity'):</span> {{ $job->quantity }}</li>
                                    <li><span class="fw-bold">@lang('Cost Per Work'):</span> {{ $setting->cur_sym . showAmount($job->rate) }}</li>
                                    <li><span class="fw-bold">@lang('Available Vacancy'):</span> {{ $job->vacancy }}</li>
                                    <li><span class="fw-bold">@lang('Total Budget'):</span> {{ $setting->cur_sym . showAmount($job->total_budget) }}</li>
                                    <li><span class="fw-bold">@lang('Published Date'):</span> {{ showDateTime($job->created_at, 'M d, Y - h:i a') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="custom--card h-auto">
                    <div class="card-header">
                        <h3 class="title">@lang('Job Applicant')</h3>
                    </div>
                    <div class="card-body">
                        @php $applicant = $jobApplication->user @endphp

                        <div class="job-request-details__job-requestor">
                            <div class="job-request-details__job-requestor__img">
                                <a href="{{ route('freelancer.show', ['username' => $applicant->username]) }}">
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $applicant->image, null, true) }}" alt="image">
                                </a>
                            </div>
                            <div class="job-request-details__job-requestor__txt">
                                <h3 class="job-request-details__job-requestor__name">
                                    <a href="{{ route('freelancer.show', ['username' => $applicant->username]) }}">{{ $applicant->fullname }}</a>
                                </h3>
                                <span class="job-request-details__job-requestor__rate"><strong>@lang('Joining Date'):</strong> {{ showDateTime($applicant->created_at, 'M d, Y') }}</span>

                                @if($jobApplication->status == ManageStatus::JOB_APPLICATION_PENDING)
                                    <div class="job-request-details__job-requestor__btn">
                                        <button type="button" class="btn btn--sm btn--success btn-accept" data-url="{{ route('user.job.application.accept', [$job, $jobApplication]) }}">
                                            <i class="ti ti-circle-check"></i> @lang('Accept')
                                        </button>
                                        <button type="button" class="btn btn--sm btn--danger ms-1 btn-reject" data-url="{{ route('user.job.application.reject', [$job, $jobApplication]) }}">
                                            <i class="ti ti-circle-x"></i> @lang('Reject')
                                        </button>
                                    </div>
                                @else
                                    <div class="job-request-details__job-requestor__btn">
                                        @if($jobApplication->status == ManageStatus::JOB_APPLICATION_APPROVED)
                                            <button type="button" class="btn btn--sm btn--success disabled">
                                                <i class="ti ti-circle-check"></i> @lang('Approved')
                                            </button>
                                        @else
                                            <button type="button" class="btn btn--sm btn--secondary disabled">
                                                <i class="ti ti-ban"></i> @lang('Rejected')
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-md-7">
                <div class="custom--card h-auto mb-4">
                    <div class="card-header">
                        <h3 class="title">@lang('Applicant Bio')</h3>
                    </div>
                    <div class="card-body">
                        @php echo $jobApplication->applicant_bio @endphp
                    </div>
                </div>

                @if($jobApplication->job_proof)
                    <div class="custom--card h-auto">
                        <div class="card-header">
                            <h3 class="title">@lang('Job Proof')</h3>
                        </div>
                        <div class="card-body">
                            <object class="job-request-details__attachment" data="{{ asset(getFilePath('jobProof') . '/' . $jobApplication->job_proof) }}" type="application/pdf"></object>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@include($activeTheme . 'partials.jobApplicationModal')

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/pdfobject.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            let pdfFIle = $('.job-request-details__attachment').attr('data')
            PDFObject.embed(pdfFIle, '.job-request-details__attachment')
        })(jQuery)
    </script>
@endpush
