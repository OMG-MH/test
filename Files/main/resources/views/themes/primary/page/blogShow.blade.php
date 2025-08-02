@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="blog-details py-120">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-8 col-lg-7">
                    <div class="blog-details__img">
                        <img src="{{ getImage($activeThemeTrue . 'images/site/blog/' . @$blogData->data_info->image, '975x600') }}" alt="Image">
                    </div>
                    <div class="blog__card__badge">
                        <span class="badge badge--base">
                            <i class="ti ti-calendar-month fz-3 transform-0"></i> {{ showDateTime(@$blogData->created_at) }}
                        </span>
                    </div>
                    <div class="blog-details__txt">
                        <h2 class="blog-details__title">{{ __(@$blogData->data_info->title) }}</h2>

                        @php echo @$blogData->data_info->description @endphp
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="post-sidebar">
                        <div class="post-sidebar__card">
                            <h3 class="post-sidebar__card__header">@lang('Share')</h3>
                            <div class="post-sidebar__card__body">
                                <div class="input--group mb-4">
                                    <input type="text" class="form--control" id="shareLink" value="" readonly>
                                    <button type="button" class="btn btn--base share-link__copy px-3">
                                        <i class="ti ti-copy"></i>
                                    </button>
                                </div>
                                <ul class="social-list">
                                    <li class="social-list__item">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="social-list__link flex-center" target="_blank">
                                            <i class="ti ti-brand-facebook"></i>
                                        </a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="https://twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}" class="social-list__link flex-center" target="_blank">
                                            <i class="ti ti-brand-x"></i>
                                        </a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="https://www.linkedin.com/shareArticle?url={{ urlencode(url()->current()) }}" class="social-list__link flex-center" target="_blank">
                                            <i class="ti ti-brand-linkedin"></i>
                                        </a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="https://pinterest.com/pin/create/bookmarklet/?media={{ $seoContents['image'] }}&url={{ urlencode(url()->current()) }}&is_video=[is_video]&description={{ __($seoContents['social_title']) }}" class="social-list__link flex-center" target="_blank">
                                            <i class="ti ti-brand-pinterest"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="post-sidebar__card">
                            <h3 class="post-sidebar__card__header">@lang('Recent Blog')</h3>
                            <div class="post-sidebar__card__body">
                                @if(count($recentBlogData))
                                    <ul class="post-sidebar__recent-post">
                                        @foreach($recentBlogData as $recentBlog)
                                            <li>
                                                <a href="{{ route('blog.show', [slug($recentBlog->data_info->title), @$recentBlog->id]) }}" class="post-sidebar__recent-post__link">
                                                    <span class="post-sidebar__recent-post__thumb">
                                                        <img src="{{ getImage($activeThemeTrue . 'images/site/blog/' . @$recentBlog->data_info->image, '975x600') }}" alt="image">
                                                    </span>
                                                    <span class="post-sidebar__recent-post__txt">{{ __(strLimit(@$recentBlog->data_info->title, 65)) }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-center">{{ __($emptyMessage) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
