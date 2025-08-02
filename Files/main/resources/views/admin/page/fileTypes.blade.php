@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <table class="table table-borderless table--striped table--responsive--xl">
            <thead>
                <tr>
                    <th>@lang('S.N.')</th>
                    <th>@lang('Type')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fileTypes as $fileType)
                    <tr>
                        <td>{{ $fileTypes->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="fw-semibold">{{ $fileType->type }}</span>
                        </td>
                        <td>
                            @php echo $fileType->statusBadge @endphp
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn--sm btn-outline--base editBtn" data-file_type="{{ $fileType->type }}" data-action="{{ route('admin.file.types.store', $fileType->id) }}">
                                    <i class="ti ti-edit"></i> @lang('Edit')
                                </button>

                                @if ($fileType->status)
                                    <button type="button" class="btn btn--sm btn--warning decisionBtn" data-question="@lang('Are you sure to inactive this file type?')" data-action="{{ route('admin.file.types.status', $fileType->id) }}">
                                        <i class="ti ti-ban"></i> @lang('Inactive')
                                    </button>
                                @else
                                    <button type="button" class="btn btn--sm btn--success decisionBtn" data-question="@lang('Are you sure to active this file type?')" data-action="{{ route('admin.file.types.status', $fileType->id) }}">
                                        <i class="ti ti-circle-check"></i> @lang('Active')
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    @include('partials.noData')
                @endforelse
            </tbody>
        </table>

        @if ($fileTypes->hasPages())
            {{ paginateLinks($fileTypes) }}
        @endif
    </div>

    {{-- Add Modal --}}
    <div class="custom--modal modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="addModalLabel">@lang('Add New File Type')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="{{ route('admin.file.types.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form--label required">@lang('Type')</label>
                                <input type="text" class="form--control" name="type" placeholder="@lang('.pdf')" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--sm btn--base">@lang('Add')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="custom--modal modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="editModalLabel">@lang('Update File Type')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form--label required">@lang('Type')</label>
                                <input type="text" class="form--control" name="type" placeholder="@lang('.pdf')" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--sm btn--base">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Type" />

    <button type="button" class="btn btn--sm btn--base" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="ti ti-circle-plus"></i> @lang('Add New')
    </button>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            let editModal = $('#editModal')

            $('.editBtn').on('click', function() {
                let type = $(this).data('file_type')
                let formAction = $(this).data('action')

                editModal.find('[name=type]').val(type)
                editModal.find('form').attr('action', formAction)
                editModal.modal('show')
            })
        })(jQuery)
    </script>
@endpush
