@extends($activeTheme . 'layouts.auth')

@section('auth')
    <table class="table table-borderless table--striped table--responsive--md">
        <thead>
            <tr>
                <th>@lang('S.N.')</th>
                <th>@lang('Job Code')</th>
                <th>@lang('Job Title')</th>
                <th>@lang('Author')</th>
                <th>@lang('Completed On')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($completedJobs as $completedJob)
                <tr>
                    <td>{{ $completedJobs->firstItem() + $loop->index }}</td>
                    <td>{{ $completedJob->job->job_code }}</td>
                    <td>
                        <a href="{{ route('job.show', $completedJob->job->job_code) }}">
                            {{ __($completedJob->job->title) }}
                        </a>
                    </td>
                    <td>{{ __($completedJob->userAssignedBy->fullname) }}</td>
                    <td>
                        <span class="d-block">
                            <span class="d-block">{{ showDateTime($completedJob->completed_at) }}</span>
                            <span class="d-block">{{ diffForHumans($completedJob->completed_at) }}</span>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.workspace.job.show', $completedJob->id) }}" class="btn btn--sm btn--icon btn-outline--info" title="@lang('Details')" data-bs-custom-class="tooltip-sm">
                                <i class="ti ti-device-desktop transform-1"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                @include('partials.noData')
            @endforelse
        </tbody>
    </table>

    @if ($completedJobs->hasPages())
        {{ $completedJobs->links() }}
    @endif
@endsection
