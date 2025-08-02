@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="custom--card">
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data" class="row g-4">
                        @csrf
                        <div class="col-md-5">
                            <div class="profile-settings__sidebar">
                                <div class="profile-settings__sidebar__img">
                                    <div class="upload__img">
                                        <label class="form--label">@lang('Upload Image')</label>
                                        <label for="imageUpload" class="upload__img__btn"><i class="ti ti-camera"></i></label>
                                        <input type="file" id="imageUpload" name="image" accept=".jpeg, .jpg, .png">
                                        <div class="upload__img-preview image-preview">
                                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, null, true) }}" alt="image">
                                        </div>
                                    </div>
                                    <p class="text-center fst-italic small"><strong>@lang('Recommended'): {{ getFileSize('userProfile') . __('px') }}</strong> @lang('resolution with a transparent background.')</p>
                                </div>
                                <h6 class="profile-settings__name">{{ $user->fullname }}</h6>
                                <span class="profile-settings__username">{{ '@' . $user->username }}</span>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row gx-4 gy-3">
                                <div class="col-sm-6">
                                    <label class="form--label required">@lang('First Name')</label>
                                    <input type="text" class="form--control" name="firstname" value="{{ @$user->firstname }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label required">@lang('Last Name')</label>
                                    <input type="text" class="form--control" name="lastname" value="{{ @$user->lastname }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label">@lang('Email Address')</label>
                                    <input type="email" class="form--control" value="{{ @$user->email }}" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label">@lang('Phone Number')</label>
                                    <input type="tel" class="form--control" value="{{ @$user->mobile }}" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form--label">@lang('Address')</label>
                                    <input type="text" class="form--control" name="address" value="{{ @$user->address->address }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label">@lang('City')</label>
                                    <input type="text" class="form--control" name="city" value="{{ @$user->address->city }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label">@lang('State')</label>
                                    <input type="text" class="form--control" name="state" value="{{ @$user->address->state }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label">@lang('ZIP Code')</label>
                                    <input type="text" class="form--control" name="zip" value="{{ @$user->address->zip }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label">@lang('Country')</label>
                                    <input type="text" class="form--control" value="{{ @$user->country_name }}" readonly>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn--base w-100 mt-2">@lang('Submit')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
