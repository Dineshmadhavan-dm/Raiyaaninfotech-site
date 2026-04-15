@php
    $isOwnMessage = $message->sender_id === Auth::user()->emp_id;
@endphp

<div class="message-bubble {{ $isOwnMessage ? 'own-message' : 'other-message' }} mb-3">
    @if(!$isOwnMessage)
        <div class="d-flex align-items-start">
            @if($message->sender->image)
                <img src="{{ asset('storage/' . $message->sender->image) }}" class="rounded-circle me-2" width="32" height="32"
                    alt="{{ $message->sender->fullname }}" data-bs-toggle="tooltip" title="{{ $message->sender->fullname }}">
            @else
                <div class="avatar-sm me-2" data-bs-toggle="tooltip" title="{{ $message->sender->fullname }}">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                        {{ substr($message->sender->fullname, 0, 1) }}
                    </div>
                </div>
            @endif
            <div class="message-content p-3">
                <div class="message-text">{{ $message->message }}</div>
                <div class="message-time mt-1" style="opacity: 0.8; font-size: 0.75rem;">
                    {{ $message->created_at->format('h:i A') }}
                </div>
            </div>
        </div>
    @else
        <div class="message-content p-3">
            <div class="message-text">{{ $message->message }}</div>
            <div class="message-time text-end mt-1" style="opacity: 0.8; font-size: 0.75rem;">
                {{ $message->created_at->format('h:i A') }}
            </div>
        </div>
    @endif
</div>