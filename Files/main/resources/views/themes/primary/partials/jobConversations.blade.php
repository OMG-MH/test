@forelse($conversations as $conversation)
    @if($conversation->sender_id == auth()->id())
        <div class="job-chat__msg outgoing">
            <div class="job-chat__msg__content">
                <div class="job-chat__msg__txt">
                    @if($conversation->message)
                        <p>{{ __($conversation->message) }}</p>
                    @endif

                    @if($conversation->file)
                        <a href="{{ route('user.job.conversation.file', $conversation) }}" class="job-chat__msg__attachment">
                            <i class="ti ti-download"></i> {{ __($conversation->file_original_name) }}
                        </a>
                    @endif
                </div>
                <span class="job-chat__msg__date"><em>{{ showDateTime($conversation->created_at, 'M d, Y - h:i A') }}</em></span>
            </div>
            <div class="job-chat__msg__icon">
                <i class="ti ti-user-circle fz-0 transform-0"></i>
            </div>
        </div>
    @else
        <div class="job-chat__msg">
            @if($conversation->is_admin)
                <div class="job-chat__msg__icon">
                    <img src="{{ getImage(getFilePath('logoFavicon') . '/favicon.png') }}" alt="@lang('Admin')">
                </div>
            @else
                <div class="job-chat__msg__icon">
                    <i class="ti ti-user-circle fz-0 transform-0"></i>
                </div>
            @endif

            <div class="job-chat__msg__content">
                <div class="job-chat__msg__txt">
                    @if($conversation->message)
                        <p>{{ __($conversation->message) }}</p>
                    @endif

                    @if($conversation->file)
                        <a href="{{ route('user.job.conversation.file', $conversation) }}" class="job-chat__msg__attachment">
                            <i class="ti ti-download"></i> {{ __($conversation->file_original_name) }}
                        </a>
                    @endif
                </div>
                <span class="job-chat__msg__date"><em>{{ showDateTime($conversation->created_at, 'M d, Y - h:i A') }}</em></span>
            </div>
        </div>
    @endif
@empty
    <div class="no-data-found">
        <img src="{{ asset($activeThemeTrue . 'images/no-message.png') }}" alt="@lang('No interactions so far')">
        <span class="fs-4">@lang('No interactions so far')</span>
    </div>
@endforelse
