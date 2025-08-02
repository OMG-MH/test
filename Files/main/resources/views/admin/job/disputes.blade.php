@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <table class="table table-borderless table--striped table--responsive--xl">
            <thead>
                <tr>
                    <th>@lang('S.N.')</th>
                    <th>@lang('Disputant')</th>
                    <th>@lang('Person Type')</th>
                    <th>@lang('Email') | @lang('Phone')</th>
                    <th>@lang('Disputed On')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($disputes as $dispute)
                    <tr>
                        <td>{{ $disputes->firstItem() + $loop->index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold">{{ __($dispute->disputant->fullname) }}</p>
                                <p class="fw-semibold">
                                    <a href="{{ route('admin.user.details', $dispute->disputant->id) }}">
                                        <small>@</small>{{ $dispute->disputant->username }}
                                    </a>
                                </p>
                            </div>
                        </td>
                        <td>
                            @if($dispute->disputant->id == $dispute->assigned_by)
                                <span class="badge badge--base">@lang('Job Author')</span>
                            @else
                                <span class="badge badge--primary">@lang('Freelancer')</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <p class="fw-semibold">{{ $dispute->disputant->email }}</p>
                                <p class="fw-semibold">{{ $dispute->disputant->mobile }}</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p>{{ showDateTime($dispute->disputed_at) }}</p>
                                <p>{{ diffForHumans($dispute->disputed_at) }}</p>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.job.dispute.show', [$job, $dispute->id]) }}" class="btn btn--sm btn-outline--base">
                                <i class="ti ti-info-square-rounded"></i> @lang('Details')
                            </a>
                        </td>
                    </tr>
                @empty
                    @include('partials.noData')
                @endforelse
            </tbody>
        </table>

        @if ($disputes->hasPages())
            {{ $disputes->links() }}
        @endif
    </div>
@endsection
