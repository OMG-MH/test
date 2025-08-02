@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="top-performer py-120">
        <div class="container">
            <div class="row g-4">
                @forelse($topFreelancers as $topFreelancer)
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        @include($activeTheme . 'partials.basicFreelancer')
                    </div>
                @empty
                    @include($activeTheme . 'partials.basicNoData')
                @endforelse

                @if($topFreelancers->hasPages())
                    <div class="col-12">
                        {{ $topFreelancers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
