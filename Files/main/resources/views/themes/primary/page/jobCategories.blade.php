@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="job-categories py-120">
        <div class="container">
            <div class="row g-4 row-cols-xxl-6 row-cols-xl-5 row-cols-lg-4 row-cols-sm-3 row-cols-2 job-category__row justify-content-center">
                @each($activeTheme . 'partials.basicCategory', $categories, 'category', $activeTheme . 'partials.basicNoData')
            </div>

            @if($categories->hasPages())
                {{ $categories->links() }}
            @endif
        </div>
    </div>
@endsection
