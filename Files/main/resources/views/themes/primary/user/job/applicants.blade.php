@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row g-4">
        <div class="col-12">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Job Info')</h3>
                </div>
                <div class="card-body">
                    <div class="row gx-3 gy-2">
                        <div class="col-md-4 col-sm-6">
                            <p><span class="fw-bold">@lang('Job Code'):</span> {{ $job->job_code }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <p><span class="fw-bold">@lang('Total Budget'):</span> {{ showAmount($job->total_budget) . ' ' . $setting->site_cur }}</p>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <p><span class="fw-bold">@lang('Available Vacancy'):</span> {{ $job->vacancy }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <table class="table table-borderless table--striped table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Applied On')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobApplications as $jobApplication)
                        <tr>
                            <td>{{ $jobApplications->firstItem() + $loop->index }}</td>
                            <td>
                                @php
                                    $applicant = $jobApplication->user;
                                    $averageRating = $applicant->average_rating;
                                    $avgRating = $averageRating == floor($averageRating) ? (int) $averageRating : number_format($averageRating, 1)
                                @endphp

                                <a href="{{ route('freelancer.show', ['username' => $applicant->username]) }}" class="tooltip-disable-sm" title='<div class="freelancer__card">
                                    <div class="freelancer__card__img">
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $applicant->image, null, true) }}" alt="freelancer">
                                    </div>
                                    <div class="freelancer__card__txt">
                                        <div class="freelancer__card__title">
                                            <h3 class="freelancer__card__name">{{ __($applicant->fullname) }}</h3>
                                            <span class="freelancer__card__designation">{{ __($applicant->role) }}</span>
                                        </div>
                                        <ul class="freelancer__card__list">
                                            <li>@lang('Joining Date'): <span>{{ showDateTime($applicant->created_at, 'M d, Y') }}</span></li>
                                            <li>@lang('Country'): <span>{{ __($applicant->country_name) }}</span></li>
                                            <li>@lang('Jobs Completed'): <span>{{ $applicant->completed_jobs_count }}</span></li>
                                            <li>@lang('Rating'): <span>{{ $avgRating . '/5' }} <small class="text--muted">{{ '(' . $applicant->reviews_count . ')' }}</small></span></li>
                                        </ul>
                                    </div>
                                </div>' data-bs-custom-class="tooltip-lg" data-bs-html="true">
                                    {{ $applicant->fullname }}
                                </a>
                            </td>
                            <td>
                                <span class="d-block">
                                    <span class="d-block">{{ showDateTime($jobApplication->created_at, 'M d, Y - h:i A') }}</span>
                                    <span class="d-block">{{ diffForHumans($jobApplication->created_at) }}</span>
                                </span>
                            </td>
                            <td>
                                @php echo $jobApplication->status_badge @endphp
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('user.job.application', [$job, $jobApplication]) }}" class="btn btn--sm btn--icon btn-outline--info" title="@lang('View')" data-bs-custom-class="tooltip-sm">
                                        <i class="ti ti-eye transform-1"></i>
                                    </a>

                                    @if($jobApplication->status == ManageStatus::JOB_APPLICATION_PENDING)
                                        <a href="#" class="btn btn--sm btn--icon btn-outline--success btn-accept" title="@lang('Accept')" data-bs-custom-class="tooltip-sm" data-url="{{ route('user.job.application.accept', [$job, $jobApplication]) }}">
                                            <i class="ti ti-check transform-1"></i>
                                        </a>
                                        <a href="#" class="btn btn--sm btn--icon btn-outline--danger btn-reject" title="@lang('Reject')" data-bs-custom-class="tooltip-sm" data-url="{{ route('user.job.application.reject', [$job, $jobApplication]) }}">
                                            <i class="ti ti-x transform-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>

            @if ($jobApplications->hasPages())
                {{ $jobApplications->links() }}
            @endif
        </div>
    </div>
@endsection

@include($activeTheme . 'partials.jobApplicationModal')
