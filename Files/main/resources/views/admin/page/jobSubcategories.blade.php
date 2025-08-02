@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <table class="table table-borderless table--striped table--responsive--xl">
            <thead>
                <tr>
                    <th>@lang('Name')</th>
                    <th>@lang('Description')</th>
                    <th>@lang('Category')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Jobs')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subcategories as $subcategory)
                    <tr>
                        <td>
                            <div class="table-card-with-image">
                                <div class="table-card-with-image__img bg--secondary p-1">
                                    <img src="{{ getImage(getFilePath('jobSubcategory') . '/' . $subcategory->image) }}" alt="image">
                                </div>
                                <div class="table-card-with-image__content">
                                    <p class="fw-semibold">{{ __($subcategory->name) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if(strlen($subcategory->description) <= 35)
                                <p>{{ __($subcategory->description) }}</p>
                            @else
                                <p>{{ __(strLimit($subcategory->description, 35)) }} <a href="#" class="see-more" data-description="{{ __($subcategory->description) }}">@lang('See More')</a></p>
                            @endif
                        </td>
                        <td>
                            <p class="fw-semibold">
                                <a href="{{ appendQuery('search', $subcategory->category->slug) }}">
                                    {{ __($subcategory->category->name) }}
                                </a>
                            </p>
                        </td>
                        <td>
                            @php echo $subcategory->statusBadge @endphp
                        </td>
                        <td>{{ $subcategory->jobs_count }}</td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn--sm btn-outline--base editBtn" data-image="{{ getImage(getFilePath('jobSubcategory') . '/' . $subcategory->image) }}" data-resource="{{ $subcategory }}" data-action="{{ route('admin.job.subcategories.store', $subcategory->id) }}">
                                    <i class="ti ti-edit"></i> @lang('Edit')
                                </button>

                                @if ($subcategory->status)
                                    <button type="button" class="btn btn--sm btn--warning decisionBtn" data-question="@lang('Are you sure to inactive this subcategory?')" data-action="{{ route('admin.job.subcategories.status', $subcategory->id) }}">
                                        <i class="ti ti-ban"></i> @lang('Inactive')
                                    </button>
                                @else
                                    <button type="button" class="btn btn--sm btn--success decisionBtn" data-question="@lang('Are you sure to active this subcategory?')" data-action="{{ route('admin.job.subcategories.status', $subcategory->id) }}">
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

        @if ($subcategories->hasPages())
            {{ paginateLinks($subcategories) }}
        @endif
    </div>

    {{-- Add Modal --}}
    <div class="custom--modal modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="addModalLabel">@lang('Add New Subcategory')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="{{ route('admin.job.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form--label required">@lang('Category')</label>
                                <select class="form--control form-select select-2" name="category_id" required>
                                    <option selected disabled>@lang('Select One')</option>

                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="upload__img mb-2">
                                    <label for="addImage" class="upload__img__btn" title="@lang('Subcategory Image')">
                                        <i class="ti ti-camera"></i>
                                    </label>
                                    <input type="file" id="addImage" class="image-upload" name="image" accept=".jpeg, .jpg, .png">
                                    <label class="upload__img-preview image-preview">
                                        <i class="ti ti-photo-up"></i>
                                    </label>
                                    <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                        <i class="ti ti-circle-x"></i>
                                    </button>
                                </div>
                                <label class="text-center small">
                                    @lang('Supported files'): <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Name')</label>
                                <input type="text" class="form--control" name="name" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Description')</label>
                                <textarea class="form--control" name="description" required></textarea>
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
                    <h2 class="modal-title" id="editModalLabel">@lang('Update Subcategory')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form--label required">@lang('Category')</label>
                                <select class="form--control form-select" name="category_id" required>
                                    <option disabled>@lang('Select One')</option>

                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="upload__img mb-2">
                                    <label for="editImage" class="upload__img__btn" title="@lang('Subcategory Image')">
                                        <i class="ti ti-camera"></i>
                                    </label>
                                    <input type="file" id="editImage" class="image-upload" name="image" accept=".jpeg, .jpg, .png">
                                    <label class="upload__img-preview image-preview">
                                        <img src="" alt="image">
                                    </label>
                                    <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none">
                                        <i class="ti ti-circle-x"></i>
                                    </button>
                                </div>
                                <label class="text-center small">
                                    @lang('Supported files'): <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Name')</label>
                                <input type="text" class="form--control" name="name" required>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Description')</label>
                                <textarea class="form--control" name="description" required></textarea>
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

    {{-- Subcategory Description Modal --}}
    <div class="custom--modal modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="descriptionModalLabel">@lang('Subcategory Description')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer gap-2">
                    <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Name" />

    <button type="button" class="btn btn--sm btn--base" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="ti ti-circle-plus"></i> @lang('Add New')
    </button>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $('.see-more').on('click', function (e) {
                e.preventDefault()

                let description = $(this).data('description')
                let descriptionModal = $('#descriptionModal')

                descriptionModal.find('p').text(description)
                descriptionModal.modal('show')
            })

            let editModal = $('#editModal')

            $('.editBtn').on('click', function() {
                let image = $(this).data('image')
                let resource = $(this).data('resource')
                let formAction = $(this).data('action')

                editModal.find('[name=category_id]').val(resource.job_category_id).select2({
                    containerCssClass: ":all:",
                    dropdownParent: editModal.find('[name=category_id]').parents('.modal'),
                })

                editModal.find('.image-preview img').attr("src", image)
                editModal.find('[name=name]').val(resource.name)
                editModal.find('[name=description]').val(resource.description)
                editModal.find('form').attr('action', formAction)
                editModal.modal('show')
            })
        })(jQuery)
    </script>
@endpush
