@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="job-list py-120">
        <div class="container">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    <div class="post-sidebar job-list__sidebar scroll">
                        <div class="post-sidebar__card border-0 p-0 d-lg-none bg-transparent">
                            <button type="button" class="btn btn--sm btn--base close-sidebar">
                                <i class="ti ti-x"></i> @lang('Close')
                            </button>
                        </div>

                        <div class="post-sidebar__card">
                            <h3 class="post-sidebar__card__header">@lang('Search')</h3>
                            <div class="post-sidebar__card__body">
                                <form class="input--group">
                                    <input type="text" class="form--control form--control--sm" name="title" value="{{ request('title') }}" placeholder="@lang('Search')">
                                    <button class="btn btn--sm btn--base px-2" type="submit"><i class="ti ti-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="post-sidebar__card">
                            <h3 class="post-sidebar__card__header">@lang('Sort By Rate')</h3>
                            <div class="post-sidebar__card__body">
                                <select class="form--control form--control--sm form-select select-2 job-rate-by-sort" data-search="false">
                                    <option value="" selected @selected(request('sort_by') == '')>@lang('Default')</option>
                                    <option value="low-to-high" @selected(request('sort_by') == 'low-to-high')>@lang('Low to High')</option>
                                    <option value="high-to-low" @selected(request('sort_by') == 'high-to-low')>@lang('High to Low')</option>
                                </select>
                            </div>
                        </div>
                        <div class="post-sidebar__card">
                            <h3 class="post-sidebar__card__header">@lang('Filter By Time')</h3>
                            <div class="post-sidebar__card__body">
                                <select class="form--control form--control--sm form-select select-2 job-filter-by-date" data-search="false">
                                    <option value="" selected @selected(request('filter_by') == '')>@lang('All')</option>
                                    <option value="today" @selected(request('filter_by') == 'today')>@lang('Today')</option>
                                    <option value="weekly" @selected(request('filter_by') == 'weekly')>@lang('This Week')</option>
                                    <option value="monthly" @selected(request('filter_by') == 'monthly')>@lang('This Month')</option>
                                </select>
                            </div>
                        </div>
                        <div class="post-sidebar__card">
                            <h3 class="post-sidebar__card__header">Job Categories</h3>
                            <div class="post-sidebar__card__body">
                                @if (count($categories))
                                    <ul class="job-list__category">
                                        <li class="job-list__category__item">
                                            <a href="{{ route('jobs') }}" class="job-list__category__link">@lang('All')</a>
                                        </li>

                                        @php $selectedSubcategories = explode(',', request('subcategories', '')) @endphp

                                        @foreach ($categories as $category)
                                            <li class="job-list__category__item">
                                                <a href="#" @class([
                                                    'job-list__category__link',
                                                    'active' => request('category') == $category->slug,
                                                    'has-sub' => count($category->subcategories),
                                                ])>
                                                    {{ __($category->name) }}
                                                </a>

                                                @if (count($category->subcategories))
                                                    <ul class="job-list__category__submenu">
                                                        @foreach ($category->subcategories as $subcategory)
                                                            <li>
                                                                <div class="form--check">
                                                                    <input type="checkbox" class="form-check-input" value="{{ $subcategory->slug }}" id="{{ $subcategory->slug }}" @checked(in_array($subcategory->slug, $selectedSubcategories))>
                                                                    <label class="form-check-label" for="{{ $subcategory->slug }}">
                                                                        {{ __($subcategory->name) }}
                                                                    </label>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    @include($activeTheme . 'partials.basicNoData')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-9 col-lg-8">
                    <div class="job-list__filter d-lg-none">
                        <button type="button" class="btn btn--sm btn--base show-sidebar">
                            <i class="ti ti-adjustments-horizontal"></i> @lang('Filter')
                        </button>
                    </div>
                    <div class="row g-4 justify-content-lg-start justify-content-center">
                        @forelse($jobs as $job)
                            <div class="col-xxl-4 col-sm-6 col-xsm-9">
                                @include($activeTheme . 'partials.basicJob')
                            </div>
                        @empty
                            @include($activeTheme . 'partials.basicNoData')
                        @endforelse

                        @if ($jobs->hasPages())
                            <div class="col-12">
                                {{ paginateLinks($jobs) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('jobs') }}" method="get" class="d-none job-filter-form">
        <input type="hidden" id="subcategories" name="subcategories" value="{{ request('subcategories', '') }}">
        <input type="hidden" id="sortBy" name="sort_by" value="{{ request('sort_by', '') }}">
        <input type="hidden" id="filterBy" name="filter_by" value="{{ request('filter_by', '') }}">
    </form>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.min.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            'use strict'

            $(function() {
                $('.job-list__category__link').each(function() {
                    if ($(this).hasClass('has-sub')) {
                        $(this).removeAttr('href').attr('role', 'button')
                    }

                    $(this).on('click', function() {
                        if ($(this).hasClass('has-sub')) {
                            $(this).toggleClass('show')
                            $(this).siblings('.job-list__category__submenu').slideToggle()
                        }

                        $(this).parent().siblings('.job-list__category__item').find('.job-list__category__submenu').slideUp()
                        $(this).parent().siblings('.job-list__category__item').find('.job-list__category__link').removeClass('show')
                    })
                })

                $('.job-list__category__submenu input[type=checkbox]').on('change', function() {
                    const submenu = $(this).closest('.job-list__category__submenu')
                    const hasChecked = submenu.find('input[type=checkbox]:checked').length > 0

                    if (hasChecked) {
                        submenu.siblings('.job-list__category__link').addClass('active')
                    } else {
                        submenu.siblings('.job-list__category__link').removeClass('active')
                    }

                    // search job by subcategories
                    let selectedSubcategories = []

                    $('.job-list__category__submenu input[type=checkbox]:checked').each(function() {
                        selectedSubcategories.push($(this).val())
                    })

                    $('#subcategories').val(selectedSubcategories.join(','))
                    $('.job-filter-form').trigger('submit')
                })

                // sort job by rate
                $('.job-rate-by-sort').on('change', function() {
                    $('#sortBy').val($(this).val())
                    $('.job-filter-form').trigger('submit')
                })

                // filter job by day
                $('.job-filter-by-date').on('change', function() {
                    $('#filterBy').val($(this).val())
                    $('.job-filter-form').trigger('submit')
                })
            })

            $(window).on('load', function() {
                $('.job-list__category__link.has-sub').each(function() {
                    if ($(this).siblings('.job-list__category__submenu').find('input[type=checkbox]:checked').length > 0) {
                        $(this).addClass('show active')
                        $(this).siblings('.job-list__category__submenu').show()
                    }
                })
            })
        })(jQuery)
    </script>
@endpush
