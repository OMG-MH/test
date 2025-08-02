@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row justify-content-center">
        @if($user->freelancer_status == ManageStatus::FREELANCER_NOT)
            <div class="col-xl-10">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Start Your Freelancing Journey')</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" class="row g-4" enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <label class="form--label">@lang('Role')</label>
                                <input type="text" class="form--control" name="role" placeholder="@lang('e.g. Web Developer')" value="{{ $user->role }}">
                            </div>
                            <div class="col-12">
                                <label class="form--label">@lang('Skills') <span title="@lang('Enter your preferred skills, then press Enter.')"><i class="ti ti-info-circle"></i></span></label>
                                <select class="form--control form-select select-2" multiple name="skills[]">
                                    @if(count($user->skills))
                                        @foreach($user->skills as $skill)
                                            <option value="{{ $skill }}" selected>{{ __($skill) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form--label">@lang('About Yourself')</label>
                                <textarea class="form--control" name="bio" rows="7">{{ $user->bio }}</textarea>
                            </div>

                            <x-phinixForm identifier="act" identifierValue="kyf" />

                            <div class="col-12">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="col-lg-6 col-sm-10">
                <div class="custom--card">
                    <div class="card-header">
                        <h3 class="title">@lang('Your Provided Information')</h3>
                    </div>
                    <div class="card-body">
                        <div class="row gx-4 gy-3">
                            @if($user->kyf_data)
                                <div class="col-12">
                                    <table class="table table-borderless table-light no-shadow">
                                        <tbody>
                                            <tr>
                                                <td>@lang('Role')</td>
                                                <td>{{ __($user->role) }}</td>
                                            </tr>
                                            <tr>
                                                <td>@lang('Skills')</td>
                                                <td>
                                                    <a href="#" id="viewSkills" data-skills="{{ json_encode($user->skills) }}">
                                                        @lang('View Skills')
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>@lang('About Myself')</td>
                                                <td>
                                                    @if(strlen($user->bio) <= 30)
                                                        {{ __($user->bio) }}
                                                    @else
                                                        {{ __(strLimit($user->bio, 30)) }} <a href="#" id="viewBio" data-bio="{{ __($user->bio) }}">@lang('See More')</a>
                                                    @endif
                                                </td>
                                            </tr>

                                            @foreach($user->kyf_data as $val)
                                                @continue(!$val->value)

                                                <tr>
                                                    <td>{{ __($val->name) }}</td>
                                                    <td>
                                                        @if($val->type == 'checkbox')
                                                            {{ implode(',', $val->value) }}
                                                        @elseif($val->type == 'file')
                                                            <a href="{{ route('user.file.download', ['filePath' => 'verify', 'fileName' => $val->value]) }}">
                                                                <i class="ti ti-download"></i> @lang('Download')
                                                            </a>
                                                        @else
                                                            {{ __($val->value) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td>@lang('Status')</td>
                                                <td>
                                                    @php echo $user->freelancer_status_badge @endphp
                                                </td>
                                            </tr>

                                            @php
                                                if ($user->freelancer_status == ManageStatus::FREELANCER_REJECTED) {
                                                    $adminFeedback = $user->freelancer_rejection_reason;
                                                } elseif ($user->freelancer_status == ManageStatus::FREELANCER_BANNED) {
                                                    $adminFeedback = $user->freelancer_ban_reason;
                                                }
                                            @endphp

                                            @isset($adminFeedback)
                                                <tr>
                                                    <td class="text--danger">@lang('Admin Feedback')</td>
                                                    <td>
                                                        @if(strlen($adminFeedback) <= 30)
                                                            {{ __($adminFeedback) }}
                                                        @else
                                                            {{ __(strLimit($adminFeedback, 30)) }} <a href="#" id="viewFeedback" data-feedback="{{ __($adminFeedback) }}">@lang('See More')</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endisset
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @include($activeTheme . 'partials.basicNoData')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('user-panel-modal')
    {{-- Skills Modal --}}
    <div class="custom--modal modal fade" id="skillsModal" tabindex="-1" aria-labelledby="skillsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="skillsModalLabel">@lang('My Skills')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap gap-2" id="freelancerSkills"></div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bio Modal --}}
    <div class="custom--modal modal fade" id="bioModal" tabindex="-1" aria-labelledby="bioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bioModalLabel">@lang('About Myself')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="freelancerBio"></p>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Feedback Modal --}}
    <div class="custom--modal modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="feedbackModalLabel">@lang('Admin Feedback')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="adminFeedback"></p>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                $('#viewSkills').on('click', function (event) {
                    event.preventDefault()

                    let skills = $(this).data('skills')
                    let skillsHtml = ``

                    skills.forEach(function (skill) {
                        skillsHtml += `<span class="badge badge--secondary fs-16 fw-medium">${skill}</span>`
                    })

                    $('#freelancerSkills').html(skillsHtml)
                    $('#skillsModal').modal('show')
                })

                $('#viewBio').on('click', function (event) {
                    event.preventDefault()

                    $('#freelancerBio').html($(this).data('bio'))
                    $('#bioModal').modal('show')
                })

                $('#viewFeedback').on('click', function (event) {
                    event.preventDefault()

                    $('#adminFeedback').html($(this).data('feedback'))
                    $('#feedbackModal').modal('show')
                })
            })
        })(jQuery)
    </script>
@endpush
