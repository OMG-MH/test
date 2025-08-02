<div class="blog__card__img">
    <img src="{{ getImage($activeThemeTrue . 'images/site/blog/' . @$blog->data_info->image, '975x600') }}" alt="blog">
</div>
<div class="blog__card__btn bg-img" data-background-image="{{ asset($activeThemeTrue . '/images/apply-btn-bg.svg') }}">
    <a href="{{ route('blog.show', [slug($blog->data_info->title), $blog->id]) }}">
        <i class="ti ti-arrow-narrow-right"></i>
    </a>
</div>
<div class="blog__card__txt">
    <div class="blog__card__badge">
        <span class="badge badge--base gap-1">
            <i class="ti ti-calendar-month fz-3 transform-1"></i> {{ showDateTime(@$blog->created_at, 'M d, Y') }}
        </span>
    </div>
    <h3 class="blog__card__title">
        <a href="{{ route('blog.show', [slug($blog->data_info->title), $blog->id]) }}">
            {{ __(strLimit(@$blog->data_info->title, 50)) }}
        </a>
    </h3>
</div>
