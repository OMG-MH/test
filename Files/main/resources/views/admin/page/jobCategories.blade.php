@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <table class="table table-borderless table--striped table--responsive--xl">
            <thead>
                <tr>
                    <th>@lang('Name')</th>
                    <th>@lang('Description')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Featured')</th>
                    <th>@lang('Jobs')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>
                            <div class="table-card-with-image">
                                <div class="table-card-with-image__img bg--secondary p-1">
                                    <img src="{{ getImage(getFilePath('jobCategory') . '/' . $category->image) }}" alt="image">
                                </div>
                                <div class="table-card-with-image__content">
                                    <p class="fw-semibold">{{ __($category->name) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if(strlen($category->description) <= 35)
                                <p>{{ __($category->description) }}</p>
                            @else
                                <p>{{ __(strLimit($category->description, 35)) }} <a href="#" class="see-more" data-description="{{ __($category->description) }}">@lang('See More')</a></p>
                            @endif
                        </td>
                        <td>
                            @php echo $category->statusBadge @endphp
                        </td>
                        <td>
                            @php echo $category->featuredBadge @endphp
                        </td>
                        <td>{{ $category->jobs_count }}</td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn--sm btn-outline--base editBtn" data-image="{{ getImage(getFilePath('jobCategory') . '/' . $category->image) }}" data-resource="{{ $category }}" data-action="{{ route('admin.job.categories.store', $category->id) }}">
                                    <i class="ti ti-edit"></i> @lang('Edit')
                                </button>

                                <div class="custom--dropdown">
                                    <button type="button" class="btn btn--sm btn--icon btn--base" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            @if ($category->status)
                                                <button type="button" class="dropdown-item text--warning decisionBtn" data-question="@lang('Are you sure to inactive this category?')" data-action="{{ route('admin.job.categories.status', $category->id) }}">
                                                    <span class="dropdown-icon"><i class="ti ti-ban"></i></span> @lang('Inactive')
                                                </button>
                                            @else
                                                <button type="button" class="dropdown-item text--success decisionBtn" data-question="@lang('Are you sure to active this category?')" data-action="{{ route('admin.job.categories.status', $category->id) }}">
                                                    <span class="dropdown-icon"><i class="ti ti-circle-check"></i></span> @lang('Active')
                                                </button>
                                            @endif
                                        </li>
                                        <li>
                                            @if ($category->is_featured)
                                                <button type="button" class="dropdown-item text--warning decisionBtn" data-question="@lang('Are you sure you want to remove this category from being featured?')" data-action="{{ route('admin.job.categories.featured', $category->id) }}">
                                                    <span class="dropdown-icon"><i class="ti ti-clipboard-x"></i></span> @lang('Remove Featured')
                                                </button>
                                            @else
                                                <button type="button" class="dropdown-item text--success decisionBtn" data-question="@lang('Are you sure you want to mark this category as featured?')" data-action="{{ route('admin.job.categories.featured', $category->id) }}">
                                                    <span class="dropdown-icon"><i class="ti ti-clipboard-check"></i></span> @lang('Make Featured')
                                                </button>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    @include('partials.noData')
                @endforelse
            </tbody>
        </table>

        @if ($categories->hasPages())
            {{ paginateLinks($categories) }}
        @endif
    </div>

    {{-- Add Modal --}}
    <div class="custom--modal modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="addModalLabel">@lang('Add New Category')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="{{ route('admin.job.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="upload__img mb-2">
                                    <label for="addImage" class="upload__img__btn" title="@lang('Category Image')">
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
                    <h2 class="modal-title" id="editModalLabel">@lang('Update Category')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="upload__img mb-2">
                                    <label for="editImage" class="upload__img__btn" title="@lang('Category Image')">
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

    {{-- Category Description Modal --}}
    <div class="custom--modal modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="descriptionModalLabel">@lang('Category Description')</h2>
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
            "use strict"

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

                editModal.find('.image-preview img').attr("src", image)
                editModal.find('[name=name]').val(resource.name)
                editModal.find('[name=description]').val(resource.description)
                editModal.find('form').attr('action', formAction)
                editModal.modal('show')
            })
        })(jQuery)
    </script>
@endpush
