@extends('layouts.app')

@section('title', 'Chat - Lost & Found')

@section('styles')
<style>
    .chat-list-item {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    
    .chat-list-item:hover {
        background-color: var(--bg-light);
    }
    
    .chat-list-item.active {
        background-color: rgba(79, 70, 229, 0.05);
        border-left-color: var(--primary-color);
    }
    
    .chat-list-item.unread {
        background-color: rgba(79, 70, 229, 0.03);
    }
    
    .chat-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .last-message {
        color: var(--text-muted);
        font-size: 0.875rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .chat-time {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    
    .unread-badge {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: white;
        font-size: 0.6875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .item-thumbnail {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active">Chat</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h4 class="section-title mb-0">
                        <i class="bi bi-chat-dots me-2"></i>Pesan Saya
                    </h4>
                </div>
                
                <div class="card-body p-0">
                    @if($chats->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($chats as $chat)
                                @php
                                    $otherUserId = $chat->user_one == Auth::id()
                                        ? $chat->user_two
                                        : $chat->user_one;

                                    $otherUser = \App\Models\User::find($otherUserId);

                                    // ambil pesan terakhir (kalau di controller sudah with messages latest, ini aman)
                                    $lastMessage = $chat->messages->first();

                                    $unreadCount = 0;
                                @endphp
                      
                                <a href="{{ route('chat.show', $chat) }}" 
                                   class="list-group-item list-group-item-action chat-list-item p-3 {{ $unreadCount > 0 ? 'unread' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <!-- User Avatar -->
                                        <div class="chat-avatar bg-primary bg-opacity-10 text-primary me-3 flex-shrink-0">
                                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                        </div>
                                        
                                        <!-- Chat Info -->
                                        <div class="flex-grow-1 min-width-0">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0 text-truncate" style="max-width: 200px;">
                                                    {{ $otherUser->name }}
                                                </h6>
                                                <span class="chat-time flex-shrink-0 ms-2">
                                                    {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : $chat->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            
                                            <!-- Item Info -->
                                            <div class="d-flex align-items-center mb-1">
                                                @if($chat->item->image)
                                                    <img src="{{ asset('storage/' . $chat->item->image) }}" class="item-thumbnail me-2" alt="">
                                                @else
                                                    <div class="item-thumbnail bg-light d-flex align-items-center justify-content-center me-2">
                                                        <i class="bi bi-image text-muted small"></i>
                                                    </div>
                                                @endif
                                                <small class="text-muted text-truncate" style="max-width: 200px;">
                                                    {{ $chat->item->title }}
                                                </small>
                                            </div>
                                            
                                            <!-- Last Message -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="last-message mb-0 flex-grow-1">
                                                    @if($lastMessage)
                                                        @if($lastMessage->sender_id == Auth::id())
                                                            <span class="text-muted">Anda:</span>
                                                        @endif
                                                        {{ Str::limit($lastMessage->message, 50) }}
                                                    @else
                                                        <span class="text-muted fst-italic">Belum ada pesan</span>
                                                    @endif
                                                </p>
                                                @if($unreadCount > 0)
                                                    <span class="unread-badge flex-shrink-0 ms-2">{{ $unreadCount }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="empty-state py-5">
                            <i class="bi bi-chat-square-text"></i>
                            <h5>Belum ada percakapan</h5>
                            <p class="mb-3">Mulai chat dari halaman detail barang untuk menghubungi pemiliknya</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Cari Barang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
