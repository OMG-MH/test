@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="contact py-120">
        <div class="container">
            <div class="row gy-5 justify-content-lg-between justify-content-center align-items-center">
                @if (count($contactElements))
                    <div class="col-12">
                        <div class="row g-4 justify-content-center">
                            @foreach ($contactElements as $contact)
                                <div class="col-lg-4 col-sm-6 col-xsm-9">
                                    <div class="custom--card contact-card" >
                                        <div class="contact-card__icon">
                                            @php echo $contact->data_info->icon @endphp
                                        </div>
                                        <div class="card-body p-4">
                                            <h3 class="contact-card__title">
                                                {{ __(@$contact->data_info->heading) }}
                                            </h3>
                                            <p>{{ __(@$contact->data_info->data) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="col-lg-6">
                    <div class="contact__thumb" >
                        <img src="{{ getImage($activeThemeTrue . 'images/site/contact_us/' . @$contactContent->data_info->image, '725x520') }}" alt="image">
                    </div>
                </div>
                <div class="col-lg-6 col-md-10">
                    <div class="card custom--card" >
                        <div class="card-header">
                            <h3 class="title">@lang('We\'re waiting to hear from you')</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" class="row g-3">
                                @csrf
                                <div class="col-sm-6">
                                    <label class="form--label required">@lang('Your Full Name')</label>
                                    <input type="text" name="name" class="form--control" value="{{ old('name', @$user->fullname) }}" @readonly(@$user) required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label required">@lang('Your Email')</label>
                                    <input type="email" name="email" class="form--control" value="{{ old('email', @$user->email) }}" @readonly(@$user) required>
                                </div>
                                <div class="col-12">
                                    <label class="form--label required">@lang('Subject')</label>
                                    <input type="text" name="subject" class="form--control" value="{{ old('subject') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form--label required">@lang('Message')</label>
                                    <textarea name="message" class="form--control" rows="4" required>{{ old('message') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn--base">@lang('Send Message')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="custom--card" >
                        <div class="card-body">
                            <div class="contact__map">
                                <iframe src="https://maps.google.com/maps?hl=en&amp;q={{ @$contactContent->data_info->latitude }},%20{{ @$contactContent->data_info->longitude }}+({{ @$setting->site_name }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" loading="lazy"
                                    allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
