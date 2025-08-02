@extends($activeTheme . 'layouts.auth')

@section('auth')
    <table class="table table-borderless table--striped table--responsive--md">
        <thead>
            <tr>
                <th>@lang('Job Title')</th>
                <th>@lang('Author')</th>
                <th>@lang('Disputant')</th>
                <th>@lang('Disputed On')</th>
                <th>@lang('Status')</th>
                <th>@lang('Settled On')</th>
                <th>@lang('Settled Amount')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($disputedJobs->sortByDesc('status') as $disputedJob)
                <tr>
                    <td>
                        <a href="{{ route('job.show', $disputedJob->job->job_code) }}">
                            {{ __($disputedJob->job->title) }}
                        </a>
                    </td>
                    <td>{{ __($disputedJob->userAssignedBy->fullname) }}</td>
                    <td>
                        {{ auth()->id() == $disputedJob->disputant->id ? trans('Me') : __($disputedJob->disputant->fullname) }}
                    </td>
                    <td>
                        <span class="d-block">
                            <span class="d-block">{{ showDateTime($disputedJob->disputed_at) }}</span>
                            <span class="d-block">{{ diffForHumans($disputedJob->disputed_at) }}</span>
                        </span>
                    </td>
                    <td>
                        @php echo $disputedJob->status_badge @endphp
                    </td>
                    <td>
                        @if($disputedJob->settled_at)
                            <span class="d-block">
                                <span class="d-block">{{ showDateTime($disputedJob->settled_at) }}</span>
                                <span class="d-block">{{ diffForHumans($disputedJob->settled_at) }}</span>
                            </span>
                        @else
                            <p>@lang('Not Yet')</p>
                        @endif
                    </td>
                    <td>{{ $setting->cur_sym . showAmount($disputedJob->settled_freelancer_amount) }}</td>
                    <td>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.workspace.job.show', $disputedJob->id) }}" class="btn btn--sm btn--icon btn-outline--info" title="@lang('Details')" data-bs-custom-class="tooltip-sm">
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

    @if ($disputedJobs->hasPages())
        {{ $disputedJobs->links() }}
    @endif
@endsection
