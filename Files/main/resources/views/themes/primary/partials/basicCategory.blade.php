<div class="job-category__card" data-bs-placement="bottom" title="{{ __($category->description) }}">
    <a href="{{ route('jobs', ['category' => $category->slug]) }}" class="job-category__card__icon">
        <img src="{{ getImage(getFilePath('jobCategory') . '/' . $category->image) }}" alt="Category">
    </a>
    <h3 class="job-category__card__title">
        <a href="{{ route('jobs', ['category' => $category->slug]) }}">
            {{ __($category->name) }}<span class="badge badge--danger px-2">{{ $category->jobs_count }}</span>
        </a>
    </h3>
</div>
