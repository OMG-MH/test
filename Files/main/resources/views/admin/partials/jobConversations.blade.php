@forelse($conversations as $conversation)
    @if($conversation->is_admin)
        <div class="admin-chat__msg outgoing">
            <div class="admin-chat__msg__content">
                <span class="admin-chat__msg__username">@lang('Admin')</span>
                <div class="admin-chat__msg__txt">
                    @if($conversation->message)
                        <p>{{ __($conversation->message) }}</p>
                    @endif

                    @if($conversation->file)
                        <a href="{{ route('admin.dispute.conversation.file', $conversation) }}" class="admin-chat__msg__attachment">
                            <i class="ti ti-download"></i> {{ __($conversation->file_original_name) }}
                        </a>
                    @endif
                </div>
                <span class="admin-chat__msg__date"><em>{{ showDateTime($conversation->created_at, 'M d, Y - h:i A') }}</em></span>
            </div>
            <div class="admin-chat__msg__icon">
                <i class="ti ti-user-circle"></i>
            </div>
        </div>
    @else
        <div class="admin-chat__msg">
            <div class="admin-chat__msg__icon">
                <i class="ti ti-user-circle"></i>
            </div>
            <div class="admin-chat__msg__content">
                <span class="admin-chat__msg__username">{{ '@' . __($conversation->user->username) }}</span>
                <div class="admin-chat__msg__txt">
                    @if($conversation->message)
                        <p>{{ __($conversation->message) }}</p>
                    @endif

                    @if($conversation->file)
                        <a href="{{ route('admin.dispute.conversation.file', $conversation) }}" class="admin-chat__msg__attachment">
                            <i class="ti ti-download"></i> {{ __($conversation->file_original_name) }}
                        </a>
                    @endif
                </div>
                <span class="admin-chat__msg__date"><em>{{ showDateTime($conversation->created_at, 'M d, Y - h:i A') }}</em></span>
            </div>
        </div>
    @endif
@empty
    <div class="no-data-found">
        <img src="{{ asset('assets/universal/images/no-message.png') }}" alt="@lang('No interactions so far')">
        <span class="fs-4">@lang('No interactions so far')</span>
    </div>
@endforelse
