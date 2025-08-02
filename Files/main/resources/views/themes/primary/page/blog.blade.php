@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="blog py-120">
        <div class="container">
            <div class="row g-4">
                @forelse($blogElements as $blog)
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog__card">
                            @include($activeTheme . 'partials.basicBlog')
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        @include($activeTheme . 'partials.basicNoData')
                    </div>
                @endforelse

                @if($blogElements->hasPages())
                    <div class="col-12">
                        {{ $blogElements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
