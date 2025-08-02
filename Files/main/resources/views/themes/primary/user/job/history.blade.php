@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="d-flex justify-content-end mb-3">
        <form action="" method="get" class="input--group">
            <input type="text" class="form--control form--control--sm" name="search" placeholder="@lang('Search here')..." value="{{ request('search') }}">
            <button type="submit" class="btn btn--sm btn--base"><i class="ti ti-search"></i></button>
        </form>
    </div>
    <table class="table table-borderless table--striped table--responsive--xl">
        <thead>
            <tr>
                <th>@lang('Job Code')</th>
                <th>@lang('Job Title')</th>
                <th>@lang('Quantity')</th>
                <th>@lang('Rate')</th>
                <th>@lang('Total Budget')</th>
                <th>@lang('Status')</th>
                <th>@lang('Initiate')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
                <tr>
                    <td>{{ $job->job_code }}</td>
                    <td>{{ __($job->title) }}</td>
                    <td>{{ $job->quantity }}</td>
                    <td>{{ $setting->cur_sym . showAmount($job->rate) }}</td>
                    <td>{{ $setting->cur_sym . showAmount($job->total_budget) }}</td>
                    <td>
                        @php echo $job->status_badge @endphp
                    </td>
                    <td>
                        <span class="d-block">
                            <span class="d-block">{{ showDateTime($job->created_at) }}</span>
                            <span class="d-block">{{ diffForHumans($job->created_at) }}</span>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-2">
                            @if($job->status != ManageStatus::JOB_REJECTED && $job->status != ManageStatus::JOB_UNAVAILABLE)
                                <a href="{{ route('user.job.edit', $job) }}" class="btn btn--sm btn--icon btn-outline--base" title="@lang('Edit')" data-bs-custom-class="tooltip-sm">
                                    <i class="ti ti-edit transform-1"></i>
                                </a>
                            @endif

                            <a href="{{ route('user.job.applicants', $job) }}" class="btn btn--sm btn--icon btn-outline--info" title="@lang('Applicants')" data-bs-custom-class="tooltip-sm">
                                <i class="ti ti-user-search transform-1"></i>

                                @if($job->applications_count)
                                    <span class="badge bg--danger rounded-2 notify-badge">
                                        {{ $job->applications_count > 9 ? '9+' : $job->applications_count }}
                                    </span>
                                @endif
                            </a>

                            @if($job->status != ManageStatus::JOB_REJECTED && $job->status != ManageStatus::JOB_UNAVAILABLE)
                                @if($job->status == ManageStatus::JOB_PAUSED)
                                    <a href="{{ route('user.job.resume', $job) }}" class="btn btn--sm btn--icon btn-outline--success btn-resume" title="@lang('Resume')" data-bs-custom-class="tooltip-sm">
                                        <i class="ti ti-player-play transform-1"></i>
                                    </a>
                                @else
                                    <a href="{{ route('user.job.pause', $job) }}" class="btn btn--sm btn--icon btn-outline--warning btn-pause" title="@lang('Pause')" data-bs-custom-class="tooltip-sm">
                                        <i class="ti ti-player-pause transform-1"></i>
                                    </a>
                                @endif
                            @endif
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
@endsection

@push('user-panel-modal')
    {{-- Pause Confirmation Modal --}}
    <div class="custom--modal modal fade" id="pauseConfirmationModal" tabindex="-1" aria-labelledby="pauseConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="pauseConfirmationModalLabel">@lang('Pause Job')</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @lang('Are you sure you want to pause this job?')
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Resume Confirmation Modal --}}
    <div class="custom--modal modal fade" id="resumeConfirmationModal" tabindex="-1" aria-labelledby="resumeConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="resumeConfirmationModalLabel">@lang('Resume Job')</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @lang('Are you sure you want to resume this job?')
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $('.btn-pause').on('click', function (event) {
                event.preventDefault()
                let pauseModal = $('#pauseConfirmationModal')

                pauseModal.find('form').attr('action', $(this).attr('href'))
                pauseModal.modal('show')
            })

            $('.btn-resume').on('click', function (event) {
                event.preventDefault()
                let resumeModal = $('#resumeConfirmationModal')

                resumeModal.find('form').attr('action', $(this).attr('href'))
                resumeModal.modal('show')
            })
        })(jQuery)
    </script>
@endpush
