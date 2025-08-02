<div class="job-card">
    <div class="job-card__thumb">
        <img src="{{ getImage(getFilePath('job') . '/' . $job->image, getFileSize('job')) }}" alt="image">
    </div>
    <div class="job-card__apply-btn bg-img" data-background-image="{{ asset($activeThemeTrue . '/images/apply-btn-bg.svg') }}">
        <a href="{{ route('job.show', $job) }}">
            <i class="ti ti-arrow-narrow-right"></i>
        </a>
    </div>
    <div class="job-card__txt">
        <div class="job-card__badge">
            <span class="badge badge--base gap-1"><i class="ti ti-circle-dot fz-3 transform-1"></i> @lang('Available')</span>
            <span class="badge badge--warning gap-1"><i class="ti ti-briefcase fz-3 transform-1"></i> {{ $job->vacancy }}</span>
            <span class="badge badge--info gap-1"><i class="ti ti-calendar-month fz-3 transform-1"></i> {{ showDateTime($job->created_at, 'M j, Y') }}</span>
        </div>
        <h3 class="job-card__title">
            <a href="{{ route('job.show', $job) }}">{{ __(strLimit($job->title, 40)) }}</a>
        </h3>
        <span class="job-card__price">{{ $setting->cur_sym . showAmount($job->rate) }} <span>@lang('per job')</span></span>
    </div>
</div>
