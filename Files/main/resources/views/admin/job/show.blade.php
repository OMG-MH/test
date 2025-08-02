@extends('admin.layouts.master')

@section('master')
    <div class="col-lg-5">
        <div class="custom--card h-auto">
            <div class="card-header">
                <h3 class="title">@lang('Job Information')</h3>
            </div>
            <div class="card-body">
                <table class="table table-flush">
                    <tbody>
                        <tr>
                            <td><strong>@lang('Author')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                <p class="fw-semibold d-flex align-items-center gap-2">
                                    {{ $job->user->fullname }}
                                    <a href="{{ route('admin.user.details', $job->user->id) }}">
                                        <small>@</small>{{ $job->user->username }}
                                    </a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Category')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ __($job->category->name) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Subcategory')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $job->subcategory ? __($job->subcategory->name) : trans('None') }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Job Code')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $job->job_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Job Quantity')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $job->quantity }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Cost Per Work')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ showAmount($job->rate) . ' ' . $setting->site_cur }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Total Budget')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ showAmount($job->total_budget) . ' ' . $setting->site_cur }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Vacancy')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $job->vacancy }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Job Proof')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                @if($job->has_job_proof == ManageStatus::JOB_PROOF_REQUIRED)
                                    <span class="badge badge--info">@lang('Required')</span>
                                @else
                                    <span class="badge badge--secondary">@lang('Optional')</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Status')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">@php echo $job->status_badge @endphp</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Initiated')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                <p class="d-flex align-items-center gap-2">
                                    {{ showDateTime($job->created_at) }}
                                    <span class="text--muted">{{ diffForHumans($job->created_at) }}</span>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($job->job_attachment)
            <div class="custom--card h-auto mt-4 overflow-hidden">
                <div class="card-header">
                    <h3 class="title">@lang('Job Attachment')</h3>
                </div>
                <object class="job-details__attachment w-100" data="{{ asset(getFilePath('jobAttachment') . '/' . $job->job_attachment) }}" type="application/pdf"></object>
            </div>
        @endif
    </div>
    <div class="col-lg-7">
        <div class="custom--card h-auto">
            <div class="card-header">
                <h3 class="title">@lang('More Information')</h3>
            </div>
            <div class="card-body">
                <div class="rounded-2 overflow-hidden border border--secondary-subtle mb-3">
                    <img src="{{ getImage(getFilePath('job') . '/' . $job->image, getFileSize('job')) }}" alt="@lang('Job')">
                </div>
                <h5 class="border-bottom pb-2 mb-3">{{ __($job->title) }}</h5>
                <div class="styled-list-parent">
                    @php echo $job->description @endphp
                </div>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <div class="custom--dropdown">
        <button type="button" class="btn btn--sm btn--icon btn--base" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ti ti-dots-vertical"></i>
        </button>
        <div class="dropdown-menu">
            @if($job->status == ManageStatus::JOB_PENDING)
                <li>
                    <button type="button" class="dropdown-item text--success decisionBtn" data-question="@lang('Are you sure to approve this job?')" data-action="{{ route('admin.job.approve', $job) }}">
                        <span class="dropdown-icon"><i class="ti ti-circle-check"></i></span> @lang('Approve')
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item text--danger decisionBtn" data-question="@lang('Are you sure to reject this job?')" data-action="{{ route('admin.job.reject', $job) }}">
                        <span class="dropdown-icon"><i class="ti ti-circle-x"></i></span> @lang('Reject')
                    </button>
                </li>
            @endif

            <li>
                <a href="{{ route('admin.job.applicants', $job) }}" class="dropdown-item text--base">
                    <span class="dropdown-icon"><i class="ti ti-user-search"></i></span> @lang('Applicants')
                </a>
            </li>
            <li>
                <a href="{{ route('admin.job.disputes', $job) }}" class="dropdown-item text--warning">
                    <span class="dropdown-icon"><i class="ti ti-message-exclamation"></i></span> @lang('Disputes')
                </a>
            </li>
        </div>
    </div>
@endpush

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

        .job-details__attachment {
            aspect-ratio: 1 / 1;
            vertical-align: middle;
        }
    </style>
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/pdfobject.js') }}"></script>
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
