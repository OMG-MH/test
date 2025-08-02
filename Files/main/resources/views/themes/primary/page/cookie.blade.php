@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="cookie-policy py-120">
        <div class="container">
            <div class="row gy-5 justify-content-center align-items-center">
                <div class="col-lg-10">
                    <div class="card custom--card" >
                        <div class="card-header">
                            <h3 class="title">@lang('Read Our') {{ __($pageTitle) }}</h3>
                        </div>
                        <div class="card-body">
                            @php echo $cookie->data_info->details @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
