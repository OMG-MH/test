@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <table class="table table-borderless table--striped table--responsive--xl">
            <thead>
                <tr>
                    <th>@lang('S.N.')</th>
                    <th>@lang('Applicant')</th>
                    <th>@lang('Email') | @lang('Phone')</th>
                    <th>@lang('Applied On')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applicants as $applicant)
                    <tr>
                        <td>{{ $applicants->firstItem() + $loop->index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold">{{ __($applicant->user->fullname) }}</p>
                                <p class="fw-semibold">
                                    <a href="{{ route('admin.user.details', $applicant->user->id) }}">
                                        <small>@</small>{{ $applicant->user->username }}
                                    </a>
                                </p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p class="fw-semibold">{{ $applicant->user->email }}</p>
                                <p class="fw-semibold">{{ $applicant->user->mobile }}</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p>{{ showDateTime($applicant->created_at) }}</p>
                                <p>{{ diffForHumans($applicant->created_at) }}</p>
                            </div>
                        </td>
                        <td>
                            @php echo $applicant->status_badge @endphp
                        </td>
                    </tr>
                @empty
                    @include('partials.noData')
                @endforelse
            </tbody>
        </table>

        @if ($applicants->hasPages())
            {{ $applicants->links() }}
        @endif
    </div>
@endsection
