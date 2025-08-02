@extends($activeTheme . 'layouts.auth')

@section('auth')
    <table class="table table-borderless table--striped table--responsive--md">
        <thead>
            <tr>
                <th>@lang('S.N.')</th>
                <th>@lang('Job Code')</th>
                <th>@lang('Job Title')</th>
                <th>@lang('Job Quantity')</th>
                <th>@lang('Cost Per Work')</th>
                <th>@lang('Status')</th>
                <th>@lang('Applied On')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
                @php $job = $application->job @endphp

                <tr>
                    <td>{{ $applications->firstItem() + $loop->index }}</td>
                    <td>{{ $job->job_code }}</td>
                    <td>
                        <a href="{{ route('job.show', $job->job_code) }}">
                            {{ __($job->title) }}
                        </a>
                    </td>
                    <td>{{ $job->quantity }}</td>
                    <td><strong>{{ $setting->cur_sym . showAmount($job->rate) }}</strong></td>
                    <td>
                        @php echo $application->status_badge @endphp
                    </td>
                    <td>
                        <span class="d-block">
                            <span class="d-block">{{ showDateTime($application->created_at) }}</span>
                            <span class="d-block">{{ diffForHumans($application->created_at) }}</span>
                        </span>
                    </td>
                </tr>
            @empty
                @include('partials.noData')
            @endforelse
        </tbody>
    </table>

    @if ($applications->hasPages())
        {{ $applications->links() }}
    @endif
@endsection
