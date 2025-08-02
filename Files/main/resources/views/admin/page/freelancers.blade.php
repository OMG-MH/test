@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <table class="table table--striped table-borderless table--responsive--lg">
            <thead>
                <tr>
                    <th>@lang('Freelancer')</th>
                    <th>@lang('Email') | @lang('Phone')</th>
                    <th>@lang('Country')</th>
                    <th>@lang('Role')</th>
                    <th>@lang('Skills')</th>
                    <th>@lang('Bio')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Balance')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($freelancers as $freelancer)
                    <tr>
                        <td>
                            <div class="table-card-with-image">
                                <div class="table-card-with-image__img">
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $freelancer->image, null, true) }}" alt="image">
                                </div>
                                <div class="table-card-with-image__content">
                                    <p class="fw-semibold">{{ $freelancer->fullname }}</p>
                                    <p class="fw-semibold">
                                        <a href="{{ route('admin.user.details', $freelancer->id) }}">
                                            <small>@</small>{{ $freelancer->username }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p>{{ $freelancer->email }}</p>
                                <p>{{ $freelancer->mobile }}</p>
                            </div>
                        </td>
                        <td>
                            <p class="fw-bold" title="{{ __(@$freelancer->country_name) }}">
                                {{ @$freelancer->country_code }}
                            </p>
                        </td>
                        <td>{{ __($freelancer->role) }}</td>
                        <td>
                            <a href="#" class="view-skills" data-modal_heading="{{ __($freelancer->fullname) }}@lang('\'s Skills')" data-skills="{{ json_encode($freelancer->skills) }}">
                                @lang('View Skills')
                            </a>
                        </td>
                        <td>
                            <a href="#" class="view-bio" data-modal_heading="{{ __($freelancer->fullname) }}@lang('\'s Bio')" data-bio="{{ __($freelancer->bio) }}">
                                @lang('View Bio')
                            </a>
                        </td>
                        <td>
                            @php echo $freelancer->freelancer_status_badge @endphp
                        </td>
                        <td>
                            <span class="fw-bold">{{ $setting->cur_sym . showAmount($freelancer->balance) }}</span>
                        </td>
                        <td>
                            <div class="custom--dropdown">
                                <button type="button" class="btn btn--icon btn--sm btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>

                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item btn-details" data-bs-toggle="offcanvas" data-bs-target="#freelancerOffcanvas" aria-controls="freelancerOffcanvas" @if($freelancer->freelancer_status == ManageStatus::FREELANCER_PENDING) data-kyf_data="{{ json_encode($freelancer->kyf_data) }}" @endif data-admin_feedback="@if($freelancer->freelancer_rejection_reason) {{ __($freelancer->freelancer_rejection_reason) }} @elseif($freelancer->freelancer_ban_reason) {{ __($freelancer->freelancer_ban_reason) }} @endif" data-job_applications_count="{{ $freelancer->job_applications_count }}" data-ongoing_jobs_count="{{ $freelancer->ongoing_jobs_count }}" data-completed_jobs_count="{{ $freelancer->completed_jobs_count }}" data-disputed_jobs_count="{{ $freelancer->disputed_jobs_count }}" data-settled_jobs_count="{{ $freelancer->settled_jobs_count }}" data-total_earning="{{ $setting->cur_sym . showAmount($freelancer->total_earning) }}" data-freelancer_status="{{ $freelancer->freelancer_status }}">
                                            <span class="dropdown-icon"><i class="ti ti-info-hexagon text--info"></i></span> @lang('Details')
                                        </button>
                                    </li>

                                    @if(request()->routeIs('admin.freelancer.pending'))
                                        <li>
                                            <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Do you want to accept this freelancer\'s application')?" data-action="{{ route('admin.freelancer.accept', $freelancer->id) }}">
                                                <span class="dropdown-icon"><i class="ti ti-circle-check text--success"></i></span> @lang('Accept')
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item btn-reject" data-action="{{ route('admin.freelancer.reject', $freelancer->id) }}">
                                                <span class="dropdown-icon"><i class="ti ti-circle-x text--danger"></i></span> @lang('Reject')
                                            </button>
                                        </li>
                                    @endif

                                    @if($freelancer->freelancer_status == ManageStatus::FREELANCER_ACTIVE)
                                        <li>
                                            <button type="button" class="dropdown-item btn-ban" data-action="{{ route('admin.freelancer.ban', $freelancer->id) }}">
                                                <span class="dropdown-icon"><i class="ti ti-user-cancel text--warning"></i></span> @lang('Ban Freelancer')
                                            </button>
                                        </li>
                                    @elseif($freelancer->freelancer_status == ManageStatus::FREELANCER_BANNED)
                                        <li>
                                            <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Do you want to unban this freelancer')?" data-action="{{ route('admin.freelancer.unban', $freelancer->id) }}">
                                                <span class="dropdown-icon"><i class="ti ti-user-check text--success"></i></span> @lang('Unban Freelancer')
                                            </button>
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

        @if ($freelancers->hasPages())
            {{ paginateLinks($freelancers) }}
        @endif
    </div>

    {{-- Skills Modal --}}
    <div class="col-12">
        <div class="custom--modal modal fade" id="skillsModal" tabindex="-1" aria-labelledby="skillsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="skillsModalLabel"></h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-wrap gap-2" id="freelancerSkills"></div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bio Modal --}}
    <div class="col-12">
        <div class="custom--modal modal fade" id="bioModal" tabindex="-1" aria-labelledby="bioModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="bioModalLabel"></h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="freelancerBio"></p>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Details Offcanvas --}}
    <div class="col-12">
        <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="freelancerOffcanvas" aria-labelledby="freelancerOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="freelancerOffcanvasLabel">@lang('Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="freelancer-info-container">
                    <h6 class="offcanvas__subtitle">@lang('Freelancer Information')</h6>
                    <table class="table table-borderless mb-4">
                        <tbody class="freelancer-info"></tbody>
                    </table>
                </div>
                <div class="admin-feedback-container">
                    <h6 class="offcanvas__subtitle">@lang('Admin Feedback')</h6>
                    <div class="custom--card h-auto mb-4">
                        <div class="card-body p-3">
                            <p class="admin-feedback"></p>
                        </div>
                    </div>
                </div>
                <div class="activity-report-container">
                    <h6 class="offcanvas__subtitle">@lang('Activity Report')</h6>
                    <table class="table table-borderless">
                        <tbody class="activity-report"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-decisionModal />

    @if(request()->routeIs('admin.freelancer.pending'))
        {{-- Application Reject Modal --}}
        <div class="col-12">
            <div class="custom--modal modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                        <div class="modal-body modal-alert">
                            <div class="text-center">
                                <div class="modal-thumb">
                                    <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                                </div>
                                <h2 class="modal-title" id="rejectModalLabel">@lang('Make Your Decision')</h2>
                                <p class="mb-3">@lang('Do you want to reject this freelancer\'s application')?</p>
                                <form action="" method="POST">
                                    @csrf
                                    <label class="form--label">@lang('Reason'):</label>
                                    <textarea class="form--control form--control--sm" name="rejection_reason" required></textarea>
                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('No')</button>
                                        <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Ban Modal --}}
    <div class="col-12">
        <div class="custom--modal modal fade" id="banModal" tabindex="-1" aria-labelledby="banModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                    <div class="modal-body modal-alert">
                        <div class="text-center">
                            <div class="modal-thumb">
                                <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                            </div>
                            <h2 class="modal-title" id="banModalLabel">@lang('Make Your Decision')</h2>
                            <p class="mb-3">@lang('Do you want to ban this freelancer')?</p>
                            <form action="" method="POST">
                                @csrf
                                <label class="form--label">@lang('Reason'):</label>
                                <textarea class="form--control form--control--sm" name="ban_reason" required></textarea>
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('No')</button>
                                    <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Username / Email" dateSearch="yes" />
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                $('.view-skills').on('click', function (event) {
                    event.preventDefault()

                    let heading = $(this).data('modal_heading')
                    let skills = $(this).data('skills')
                    let skillsHtml = ``

                    skills.forEach(function (skill) {
                        skillsHtml += `<span class="badge badge--secondary fs-16 fw-medium">${skill}</span>`
                    })

                    $('#skillsModalLabel').text(heading)
                    $('#freelancerSkills').html(skillsHtml)
                    $('#skillsModal').modal('show')
                })

                $('.view-bio').on('click', function (event) {
                    event.preventDefault()

                    let heading = $(this).data('modal_heading')
                    let bio = $(this).data('bio')

                    $('#bioModalLabel').text(heading)
                    $('#freelancerBio').html(bio)
                    $('#bioModal').modal('show')
                })

                $('.btn-details').on('click', function () {
                    let data = $(this).data()
                    let infoHtml = ``
                    let fileDownloadUrl = '{{ route("admin.file.download", ["filePath" => "verify"]) }}'

                    if (data.kyf_data && data.kyf_data.length > 0) {
                        data.kyf_data.forEach(element => {
                            if (!element.value) return

                            if (element.type !== 'file') {
                                infoHtml += `
                                    <tr>
                                        <td class="fw-bold">${element.name}:</td>
                                        <td>${element.value}</td>
                                    </tr>
                                `
                            } else {
                                infoHtml += `
                                    <tr>
                                        <td class="fw-bold">${element.name}:</td>
                                        <td>
                                            <a href="${fileDownloadUrl}&fileName=${element.value}" class="btn btn--sm btn-outline--secondary">
                                                <i class="ti ti-download"></i> @lang('Download')
                                            </a>
                                        </td>
                                    </tr>
                                `
                            }
                        })

                        $('.freelancer-info').html(infoHtml)
                        $('.freelancer-info-container').show()
                    } else {
                        $('.freelancer-info').html('')
                        $('.freelancer-info-container').hide()
                    }

                    // admin feedback
                    if (data.admin_feedback) {
                        $('.admin-feedback').html(data.admin_feedback)
                        $('.admin-feedback-container').show()
                    } else {
                        $('.admin-feedback').html('')
                        $('.admin-feedback-container').hide()
                    }

                    // activity report
                    if (data.freelancer_status === @json(ManageStatus::FREELANCER_ACTIVE)) {
                        let activityHtml = `
                            <tr>
                                <td class="fw-bold">@lang('Applied Jobs'):</td>
                                <td>${data.job_applications_count}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">@lang('Ongoing Jobs'):</td>
                                <td>${data.ongoing_jobs_count}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">@lang('Completed Jobs'):</td>
                                <td>${data.completed_jobs_count}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">@lang('Disputed Jobs'):</td>
                                <td>${data.disputed_jobs_count}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">@lang('Settled Jobs'):</td>
                                <td>${data.settled_jobs_count}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">@lang('Total Earning'):</td>
                                <td>${data.total_earning}</td>
                            </tr>
                        `

                        $('.activity-report').html(activityHtml)
                        $('.activity-report-container').show()
                    } else {
                        $('.activity-report').html('')
                        $('.activity-report-container').hide()
                    }
                })

                $('.btn-reject').on('click', function () {
                    let modal = $('#rejectModal')

                    modal.find('form').attr('action', $(this).data('action'))
                    modal.modal('show')
                })

                $('.btn-ban').on('click', function () {
                    let modal = $('#banModal')

                    modal.find('form').attr('action', $(this).data('action'))
                    modal.modal('show')
                })
            })
        })(jQuery)
    </script>
@endpush
