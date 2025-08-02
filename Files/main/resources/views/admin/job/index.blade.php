@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <table class="table table-borderless table--striped table--responsive--xl">
            <thead>
                <tr>
                    <th>@lang('Title') | @lang('Job Code')</th>
                    <th>@lang('Category')</th>
                    <th>@lang('Author')</th>
                    <th>@lang('Job Quantity')</th>
                    <th>@lang('Cost Per Work')</th>
                    <th>@lang('Total Budget')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobs as $job)
                    <tr>
                        <td>
                            <div>
                                <p class="fw-semibold">{{ strlen($job->title) > 25 ? __(strLimit($job->title, 25)) : __($job->title) }}</p>
                                <p class="text--base">{{ $job->job_code }}</p>
                            </div>
                        </td>
                        <td>{{ __($job->category->name) }}</td>
                        <td>
                            <div class="table-card-with-image__content">
                                <p class="fw-semibold">{{ $job->user->fullname }}</p>
                                <p class="fw-semibold">
                                    <a href="{{ appendQuery('search', $job->user->username) }}">
                                        <small>@</small>{{ $job->user->username }}
                                    </a>
                                </p>
                            </div>
                        </td>
                        <td>{{ $job->quantity }}</td>
                        <td>{{ $setting->cur_sym . showAmount($job->rate) }}</td>
                        <td>{{ $setting->cur_sym . showAmount($job->total_budget) }}</td>
                        <td>
                            @php echo $job->status_badge @endphp
                        </td>
                        <td>
                            <div class="custom--dropdown">
                                <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.job.show', $job) }}" class="dropdown-item text--info">
                                            <span class="dropdown-icon"><i class="ti ti-device-imac"></i></span> @lang('Details')
                                        </a>
                                    </li>

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

                                    @if($job->status != ManageStatus::JOB_PENDING && $job->status != ManageStatus::JOB_REJECTED)
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
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    @include('partials.noData')
                @endforelse
            </tbody>
        </table>

        @if ($jobs->hasPages())
            {{ paginateLinks($jobs) }}
        @endif
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search here..." />
@endpush
