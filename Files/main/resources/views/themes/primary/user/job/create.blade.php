@extends($activeTheme . 'layouts.auth')

@section('auth')
    <form action="{{ route('user.job.store') }}" method="post" enctype="multipart/form-data" class="row g-4">
        @csrf
        <div class="col-xxxl-6">
            <div class="custom--card">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-6 col-sm-5">
                            <div class="upload__img">
                                <label class="form--label required">@lang('Job Image')</label>
                                <label for="imageUpload" class="upload__img__btn"><i class="ti ti-camera transform-0"></i></label>
                                <input type="file" id="imageUpload" name="image" accept=".jpeg, .jpg, .png" required>
                                <div class="upload__img-preview image-preview">+</div>
                            </div>
                            <span class="d-block text-center"><em><small>@lang('Recommended'): {{ getFileSize('job') . __('px') }}</small></em></span>
                        </div>
                        <div class="col-lg-6 col-sm-7">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form--label required">@lang('Category')</label>
                                    <select class="form--control form-select select-2" id="jobCategory" name="category" required>
                                        <option value="" selected disabled>@lang('Select One')</option>

                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('category') == $category->id) data-subcategories="{{ $category->subcategories }}">
                                                {{ __($category->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form--label required">@lang('Subcategory')</label>
                                    <select class="form--control form-select select-2" id="jobSubcategory" name="subcategory" required>
                                        <option value="" selected>@lang('Select One')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form--label required">@lang('Job Title')</label>
                            <input type="text" class="form--control" name="title" value="{{ old('title') }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form--label">
                                @lang('Job Quantity') <span class="text--danger">*</span> <span title="@lang('The quantity will be applied per person.')"><i class="ti ti-info-circle"></i></span>
                            </label>
                            <input type="number" min="1" class="form--control" id="jobQuantity" name="quantity" value="{{ old('quantity') }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form--label">
                                @lang('Cost Per Work') <span class="text--danger">*</span> <span title="@lang('The amount will be earned per job.')"><i class="ti ti-info-circle"></i></span>
                            </label>
                            <div class="input--group">
                                <input type="number" step="any" min="0" class="form--control" id="costPerWork" name="rate" value="{{ old('rate') }}" required>
                                <span class="input-group-text">{{ $setting->site_cur }}</span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form--label required">@lang('Vacancy')</label>
                            <input type="number" min="1" class="form--control" id="jobVacancy" name="vacancy" value="{{ old('vacancy') }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form--label">
                                @lang('Total Budget') <span title="@lang('Total budget is based on job quantity, cost per work, and vacancies.')"><i class="ti ti-info-circle"></i></span>
                            </label>
                            <div class="input--group">
                                <input type="number" class="form--control" id="totalBudget" name="total_budget" value="{{ old('total_budget') }}" readonly>
                                <span class="input-group-text">{{ $setting->site_cur }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form--label required">@lang('Job Proof')</label>
                            <select class="form--control wide" id="jobProof" name="job_proof" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                <option value="{{ ManageStatus::JOB_PROOF_OPTIONAL }}" @selected(old('job_proof') == ManageStatus::JOB_PROOF_OPTIONAL)>
                                    @lang('Optional')
                                </option>
                                <option value="{{ ManageStatus::JOB_PROOF_REQUIRED }}" @selected(old('job_proof') == ManageStatus::JOB_PROOF_REQUIRED)>
                                    @lang('Required')
                                </option>
                            </select>
                        </div>
                        <div @class(['col-12', 'supported-file-types', 'd-none' => old('job_proof') != ManageStatus::JOB_PROOF_REQUIRED])>
                            <label class="form--label">@lang('Supported File Types') <span title="@lang('Ensure uploaded file is in one of the following formats.')"><i class="ti ti-info-circle"></i></span></label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form--check">
                                    <input type="checkbox" class="form-check-input" id="typeAll" name="file_types[]" value="all" @checked(in_array('all', old('file_types', [])))>
                                    <label class="form-check-label" for="typeAll">@lang('Select All')</label>
                                </div>

                                @foreach($fileTypes as $fileType)
                                    <div class="form--check">
                                        <input type="checkbox" class="form-check-input file-types" id="{{ 'type' . $loop->iteration }}" name="file_types[]" value="{{ $fileType->type }}" @checked(in_array($fileType->type, old('file_types', [])))>
                                        <label class="form-check-label" for="{{ 'type' . $loop->iteration }}">{{ __($fileType->type) }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form--label">@lang('Job Attachment')</label>
                            <input type="file" class="form--control" name="job_attachment" accept=".pdf">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxxl-6">
            <div class="custom--card">
                <div class="card-body job-description">
                    <label class="form--label required">@lang('Job Description')</label>
                    <textarea class="form--control ck-editor" name="description">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn--sm btn--base w-100">@lang('Create Job')</button>
        </div>
    </form>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/universal/js/ckEditor.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            const oldSubcategory = @json(old('subcategory'));
            const oldSubcategories = @json($categories->find(old('category'))->subcategories ?? []);

            function populateSubcategories(subcategories) {
                let subcategorySelector = $('#jobSubcategory')

                subcategorySelector.empty().append('<option value="" selected disabled>@lang('Select One')</option>')

                subcategories && subcategories.forEach(subcategory => {
                    subcategorySelector.append(
                        `<option value="${subcategory.id}" ${subcategory.id === parseInt(oldSubcategory) ? 'selected' : ''}>
                            ${subcategory.name}
                        </option>`
                    )
                })
            }

            $('#jobCategory').on('change', function () {
                let subcategories = $(this).find(':selected').data('subcategories')

                $('#jobSubcategory').val('').trigger('change')
                populateSubcategories(subcategories)
            })

            @if($errors->any())
                populateSubcategories(oldSubcategories)
            @endif

            function calculateTotalBudget(quantity, cost, vacancy) {
                let totalBudget = quantity * cost * vacancy

                $('#totalBudget').val(totalBudget.toFixed(2))
            }

            $('#jobQuantity').on('keyup', function () {
                let quantity = parseInt($(this).val())
                if (!quantity) quantity = 0

                let cost = parseFloat($('#costPerWork').val())
                if (!cost) cost = 0

                let vacancy = parseInt($('#jobVacancy').val())
                if (!vacancy) vacancy = 0

                calculateTotalBudget(quantity, cost, vacancy)
            })

            $('#costPerWork').on('keyup', function () {
                let cost = parseFloat($(this).val())
                if (!cost) cost = 0

                let quantity = parseInt($('#jobQuantity').val())
                if (!quantity) quantity = 0

                let vacancy = parseInt($('#jobVacancy').val())
                if (!vacancy) vacancy = 0

                calculateTotalBudget(quantity, cost, vacancy)
            })

            $('#jobVacancy').on('keyup', function () {
                let vacancy = parseInt($(this).val())
                if (!vacancy) vacancy = 0

                let quantity = parseInt($('#jobQuantity').val())
                if (!quantity) quantity = 0

                let cost = parseFloat($('#costPerWork').val())
                if (!cost) cost = 0

                calculateTotalBudget(quantity, cost, vacancy)
            })

            $('#jobProof').on('change', function () {
                if (parseInt($(this).val()) === 2) $('.supported-file-types').removeClass('d-none')
                else $('.supported-file-types').addClass('d-none')
            })

            $('#typeAll').on('change', function () {
                let isTypeAllChecked = $(this).is(':checked')

                if (isTypeAllChecked) $('.file-types').prop('checked', true)
                else $('.file-types').prop('checked', false)
            })
        })(jQuery)
    </script>
@endpush
