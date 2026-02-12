@extends('layouts.app')

@section('title', 'Chat - ' . $otherUser->name . ' - Lost & Found')

@section('styles')
<style>
    .chat-container {
        height: calc(100vh - 250px);
        min-height: 400px;
    }
    
    .chat-messages {
        height: calc(100% - 80px);
        overflow-y: auto;
        padding: 1.5rem;
        background-color: #f8fafc;
    }
    
    .message-bubble {
        max-width: 75%;
        padding: 0.875rem 1.125rem;
        border-radius: 18px;
        font-size: 0.9375rem;
        line-height: 1.5;
        word-wrap: break-word;
    }
    
    .message-sent {
        background-color: var(--primary-color);
        color: white;
        border-bottom-right-radius: 4px;
        margin-left: auto;
    }
    
    .message-received {
        background-color: white;
        color: var(--text-dark);
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    
    .message-time {
        font-size: 0.6875rem;
        margin-top: 0.25rem;
        opacity: 0.7;
    }
    
    .message-sent .message-time {
        color: rgba(255,255,255,0.8);
        text-align: right;
    }
    
    .message-received .message-time {
        color: var(--text-muted);
    }
    
    .chat-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    
    .chat-input-area {
        height: 80px;
        padding: 1rem 1.5rem;
        background-color: white;
        border-top: 1px solid var(--border-color);
    }
    
    .chat-input {
        border-radius: 25px;
        padding: 0.75rem 1.25rem;
        border: 1px solid var(--border-color);
        background-color: #f8fafc;
    }
    
    .chat-input:focus {
        background-color: white;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .btn-send {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .item-info-bar {
        background-color: white;
        padding: 0.75rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .item-thumbnail {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
    }
    
    .date-separator {
        text-align: center;
        margin: 1.5rem 0;
    }
    
    .date-separator span {
        background-color: #e2e8f0;
        color: var(--text-muted);
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
    }
    
    .message-status {
        font-size: 0.6875rem;
        margin-left: 0.25rem;
    }
    
    .status-read {
        color: #34d399;
    }
    
    .status-delivered {
        color: rgba(255,255,255,0.6);
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('chat.index') }}" class="text-decoration-none">Chat</a></li>
            <li class="breadcrumb-item active">{{ $otherUser->name }}</li>
        </ol>
    </nav>

    <div class="card chat-container">
        <!-- Chat Header -->
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a href="{{ route('chat.index') }}" class="btn btn-link text-muted p-0 me-3 d-lg-none">
                        <i class="bi bi-arrow-left" style="font-size: 1.25rem;"></i>
                    </a>
                    <div class="chat-avatar bg-primary bg-opacity-10 text-primary me-3">
                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $otherUser->name }}</h6>
                        <small class="text-muted">
                            @if($otherUser->last_seen_at && $otherUser->last_seen_at->diffInMinutes() < 5)
                                <span class="text-success">● Online</span>
                            @else
                                Terakhir dilihat {{ $otherUser->last_seen_at ? $otherUser->last_seen_at->diffForHumans() : 'lama' }}
                            @endif
                        </small>
                    </div>
                </div>
                <a href="{{ route('items.show', $chat->item) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-box-seam me-1"></i>Lihat Barang
                </a>
            </div>
        </div>

        <!-- Item Info Bar -->
        <div class="item-info-bar">
            <div class="d-flex align-items-center">
                @if($chat->item->image)
                    <img src="{{ asset('storage/' . $chat->item->image) }}" class="item-thumbnail me-3" alt="">
                @else
                    <div class="item-thumbnail bg-light d-flex align-items-center justify-content-center me-3">
                        <i class="bi bi-image text-muted"></i>
                    </div>
                @endif
                <div class="flex-grow-1">
                    <small class="text-muted d-block">Percakapan tentang:</small>
                    <span class="fw-medium">{{ Str::limit($chat->item->title, 50) }}</span>
                </div>
                <span class="badge {{ $chat->item->type == 'found' ? 'badge-found' : 'badge-lost' }}">
                    {{ $chat->item->type == 'found' ? 'Ditemukan' : 'Hilang' }}
                </span>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="chat-messages" id="chatMessages">
            @if($messages->count() > 0)
                @php
                    $currentDate = null;
                @endphp
                
                @foreach($messages as $message)
                    @php
                        $messageDate = $message->created_at->format('Y-m-d');
                        $showDate = $messageDate != $currentDate;
                        $currentDate = $messageDate;
                    @endphp
                    
                    @if($showDate)
                        <div class="date-separator">
                            <span>{{ $message->created_at->format('d M Y') }}</span>
                        </div>
                    @endif
                    
                    <div class="mb-3 {{ $message->sender_id == Auth::id() ? 'd-flex justify-content-end' : '' }}">
                        @if($message->sender_id != Auth::id())
                            <div class="chat-avatar bg-secondary bg-opacity-10 text-secondary me-2 align-self-end">
                                {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                            </div>
                        @endif
                        
                        <div class="message-bubble {{ $message->sender_id == Auth::id() ? 'message-sent' : 'message-received' }}">
                            <div>{{ $message->message }}</div>
                            <div class="message-time d-flex align-items-center {{ $message->sender_id == Auth::id() ? 'justify-content-end' : '' }}">
                                {{ $message->created_at->format('H:i') }}
                                @if($message->sender_id == Auth::id())
                                    <span class="message-status {{ $message->is_read ? 'status-read' : 'status-delivered' }}">
                                        @if($message->is_read)
                                            <i class="bi bi-check2-all"></i>
                                        @else
                                            <i class="bi bi-check2"></i>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-chat-dots text-muted" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted">Mulai percakapan</h6>
                    <p class="text-muted small">Kirim pesan pertama untuk memulai percakapan</p>
                </div>
            @endif
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
            <form action="{{ route('chat.send', $chat) }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="text" name="message" class="form-control chat-input" placeholder="Tulis pesan..." autocomplete="off" required autofocus>
                <button type="submit" class="btn btn-primary btn-send">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Scroll to bottom of chat
    function scrollToBottom() {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Scroll on page load
    document.addEventListener('DOMContentLoaded', scrollToBottom);
    
    // Auto scroll on new message (if using AJAX/Pusher)
    // Uncomment if implementing real-time chat
    // setInterval(scrollToBottom, 100);
</script>
@endsection
