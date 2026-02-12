@extends('layouts.app')

@section('title', 'Kelola Profil')

@section('content')
<style>
    /* ===== BASE STYLES ===== */
    .profile-container {
        padding: 2rem 0;
    }
    
    /* ===== GRADIENTS ===== */
    .bg-gradient-violet {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    /* ===== SHADOWS ===== */
    .shadow-violet {
        box-shadow: 0 10px 40px -10px rgba(139, 92, 246, 0.2);
    }
    
    .shadow-violet-lg {
        box-shadow: 0 20px 50px -12px rgba(139, 92, 246, 0.25);
    }
    
    .shadow-red {
        box-shadow: 0 10px 40px -10px rgba(239, 68, 68, 0.2);
    }
    
    /* ===== TAB NAVIGATION ===== */
    .tab-nav {
        display: flex;
        flex-direction: row;
        gap: 0.5rem;
    }
    
    .tab-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        text-align: left;
        flex: 1;
        border: none;
        background: transparent;
        cursor: pointer;
        color: #6b7280;
    }
    
    .tab-btn:hover {
        background: #f9fafb;
    }
    
    .tab-btn.active {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 10px 30px -10px rgba(139, 92, 246, 0.4);
    }
    
    .tab-btn.active-red {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        box-shadow: 0 10px 30px -10px rgba(220, 38, 38, 0.4);
    }
    
    .tab-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        flex-shrink: 0;
    }
    
    .tab-btn.active .tab-icon,
    .tab-btn.active-red .tab-icon {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .tab-title {
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .tab-desc {
        font-size: 0.75rem;
        color: #9ca3af;
    }
    
    .tab-btn.active .tab-desc,
    .tab-btn.active-red .tab-desc {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .tab-chevron {
        margin-left: auto;
        transition: transform 0.2s ease;
    }
    
    .tab-btn.active .tab-chevron,
    .tab-btn.active-red .tab-chevron {
        transform: rotate(90deg);
    }
    
    /* ===== TAB CONTENT ===== */
    .tab-content {
        display: none;
        animation: fadeInUp 0.3s ease-out;
    }
    
    .tab-content.active {
        display: block;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* ===== CARDS ===== */
    .card-modern {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.08);
    }
    
    .card-header-gradient {
        background: linear-gradient(to right, rgba(139, 92, 246, 0.05), transparent);
    }
    
    .card-header-gradient-amber {
        background: linear-gradient(to right, rgba(245, 158, 11, 0.05), transparent);
    }
    
    .card-header-gradient-red {
        background: linear-gradient(to right, rgba(239, 68, 68, 0.05), transparent);
    }
    
    /* ===== PROFILE CARD ===== */
    .profile-card-header {
        height: 7rem;
        position: relative;
    }
    
    .profile-avatar-container {
        position: relative;
        margin-top: -3.5rem;
    }
    
    .profile-avatar {
        width: 7rem;
        height: 7rem;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        background: #ede9fe;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-avatar-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.875rem;
        font-weight: 700;
        color: #7c3aed;
    }
    
    .avatar-upload-btn {
        position: absolute;
        bottom: 0.25rem;
        right: 0.25rem;
        width: 2rem;
        height: 2rem;
        background: #7c3aed;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }
    
    .avatar-upload-btn:hover {
        background: #6d28d9;
        transform: scale(1.1);
    }
    
    /* ===== STATS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: background 0.2s ease;
    }
    
    .stat-item:hover {
        background: rgba(139, 92, 246, 0.05);
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .stat-number.violet { color: #7c3aed; }
    .stat-number.amber { color: #d97706; }
    .stat-number.green { color: #059669; }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    /* ===== ICON BACKGROUNDS ===== */
    .icon-bg-violet {
        background: rgba(139, 92, 246, 0.1);
    }
    
    .icon-bg-amber {
        background: rgba(245, 158, 11, 0.1);
    }
    
    .icon-bg-red {
        background: rgba(239, 68, 68, 0.1);
    }
    
    /* ===== QUICK ACTIONS ===== */
    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 0.75rem;
        border: 2px solid;
        transition: all 0.2s ease;
        text-decoration: none;
        width: 100%;
    }
    
    .quick-action-btn.violet {
        border-color: #ede9fe;
    }
    
    .quick-action-btn.violet:hover {
        border-color: #c4b5fd;
        background: #f5f3ff;
    }
    
    .quick-action-btn.amber {
        border-color: #fef3c7;
    }
    
    .quick-action-btn.amber:hover {
        border-color: #fcd34d;
        background: #fffbeb;
    }
    
    .quick-action-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }
    
    .quick-action-btn:hover .quick-action-icon {
        transform: scale(1.1);
    }
    
    .quick-action-arrow {
        margin-left: auto;
        color: #9ca3af;
        transition: transform 0.2s ease;
    }
    
    .quick-action-btn:hover .quick-action-arrow {
        transform: translateX(4px);
    }
    
    /* ===== BADGES ===== */
    .badge-verified {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        background: #dcfce7;
        color: #15803d;
    }
    
    /* ===== SEPARATOR ===== */
    .separator {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 1.25rem 0;
    }
    /* ===== FORM OVERRIDE (LARAVEL BREEZE FIX) ===== */
    .profile-container form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Label */
    .profile-container label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    /* Input & Select */
    .profile-container input[type="text"],
    .profile-container input[type="email"],
    .profile-container input[type="password"],
    .profile-container input[type="file"],
    .profile-container select {
        width: 100%;
        padding: 0.65rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        font-size: 0.875rem;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    /* Focus */
    .profile-container input:focus,
    .profile-container select:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
    }

    /* Disabled */
    .profile-container input:disabled {
        background: #f9fafb;
        color: #9ca3af;
    }

    /* Error text */
    .profile-container .text-red-600,
    .profile-container .text-sm.text-red-600 {
        font-size: 0.75rem;
        color: #dc2626;
    }

    /* ===== BUTTONS ===== */
    .profile-container button[type="submit"] {
        align-self: flex-start;
        padding: 0.6rem 1.5rem;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 8px 20px -8px rgba(139, 92, 246, 0.5);
    }

    /* Hover */
    .profile-container button[type="submit"]:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 30px -10px rgba(139, 92, 246, 0.6);
    }

    /* Disabled Button */
    .profile-container button[disabled] {
        opacity: 0.5;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Delete button khusus */
    .profile-container button.bg-red-600,
    .profile-container button.text-red-600 {
        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        color: white !important;
        box-shadow: 0 8px 20px -8px rgba(220, 38, 38, 0.5);
    }

</style>

<div class="profile-container">
    <div class="container">
        <div class="row">
            
            {{-- Left Sidebar --}}
            <div class="col-lg-4 mb-4">
                
                {{-- Profile Card --}}
                <div class="card-modern shadow-violet-lg mb-4">
                    <div class="profile-card-header bg-gradient-violet"></div>
                    <div class="px-4 pb-4">
                        <div style="display: flex; flex-direction: column; align-items: center; margin-top: -3.5rem;">
                            <div class="profile-avatar-container">
                                <div class="profile-avatar">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}">
                                    @else
                                        <div class="profile-avatar-fallback">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <button onclick="document.getElementById('avatar-input').click()" class="avatar-upload-btn">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                                <input type="file" id="avatar-input" class="d-none" accept="image/*">
                            </div>
                            
                            <h3 style="margin-top: 1rem; font-size: 1.25rem; font-weight: 700; color: #111827;">{{ auth()->user()->name }}</h3>
                            
                            <p style="color: #6b7280; font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; margin-top: 0.25rem;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ auth()->user()->email }}
                            </p>
                            
                            @if(auth()->user()->email_verified_at)
                                <span class="badge-verified" style="margin-top: 0.75rem;">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Terverifikasi
                                </span>
                            @endif
                        </div>
                        
                        <hr class="separator">
                        
                        {{-- Stats --}}
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number violet">{{ auth()->user()->found_items_count ?? 0 }}</div>
                                <div class="stat-label">Ditemukan</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number amber">{{ auth()->user()->lost_items_count ?? 0 }}</div>
                                <div class="stat-label">Hilang</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number green">{{ auth()->user()->returned_items_count ?? 0 }}</div>
                                <div class="stat-label">Kembali</div>
                            </div>
                        </div>
                        
                        <hr class="separator">
                        
                        {{-- Contact Info --}}
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            @if(auth()->user()->phone)
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.2s;" onmouseover="this.style.background='rgba(139,92,246,0.05)'" onmouseout="this.style.background='transparent'">
                                <div class="icon-bg-violet" style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="16" height="16" fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <span style="font-size: 0.875rem; color: #4b5563;">{{ auth()->user()->phone }}</span>
                            </div>
                            @endif
                            
                            @if(auth()->user()->location)
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.2s;" onmouseover="this.style.background='rgba(139,92,246,0.05)'" onmouseout="this.style.background='transparent'">
                                <div class="icon-bg-violet" style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="16" height="16" fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span style="font-size: 0.875rem; color: #4b5563;">{{ auth()->user()->location }}</span>
                            </div>
                            @endif
                            
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.2s;" onmouseover="this.style.background='rgba(139,92,246,0.05)'" onmouseout="this.style.background='transparent'">
                                <div class="icon-bg-violet" style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="16" height="16" fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span style="font-size: 0.875rem; color: #4b5563;">Bergabung {{ auth()->user()->created_at->format('F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Quick Actions --}}
                <div class="card-modern shadow-violet" style="padding: 1.25rem;">
                    <h4 style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">
                        Aksi Cepat
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <a href="{{ route('items.createFound') }}" class="quick-action-btn violet">
                            <div class="quick-action-icon icon-bg-violet">
                                <svg width="20" height="20" fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 500; color: #111827;">Laporkan Ditemukan</div>
                                <div style="font-size: 0.75rem; color: #6b7280;">Temukan barang hilang</div>
                            </div>
                            <svg class="quick-action-arrow" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        
                        <a href="{{ route('items.createLost') }}" class="quick-action-btn amber">
                            <div class="quick-action-icon icon-bg-amber">
                                <svg width="20" height="20" fill="none" stroke="#d97706" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 500; color: #111827;">Laporkan Hilang</div>
                                <div style="font-size: 0.75rem; color: #6b7280;">Kehilangan barang</div>
                            </div>
                            <svg class="quick-action-arrow" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Right Content --}}
            <div class="col-lg-8">
                
                {{-- Tab Navigation --}}
                <div class="card-modern shadow-violet mb-4" style="padding: 0.5rem;">
                    <div class="tab-nav">
                        <button class="tab-btn active" onclick="switchTab('profile', this)">
                            <div class="tab-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div style="flex: 1;">
                                <div class="tab-title">Informasi Profil</div>
                                <div class="tab-desc">Perbarui data pribadi Anda</div>
                            </div>
                            <svg class="tab-chevron" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <button class="tab-btn" onclick="switchTab('password', this)">
                            <div class="tab-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div style="flex: 1;">
                                <div class="tab-title">Keamanan</div>
                                <div class="tab-desc">Ubah password Anda</div>
                            </div>
                            <svg class="tab-chevron" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <button class="tab-btn" onclick="switchTab('delete', this)">
                            <div class="tab-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <div style="flex: 1;">
                                <div class="tab-title">Hapus Akun</div>
                                <div class="tab-desc">Hapus akun secara permanen</div>
                            </div>
                            <svg class="tab-chevron" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                {{-- Profile Tab Content --}}
                <div id="tab-profile" class="tab-content active">
                    <div class="card-modern shadow-violet">
                        <div class="card-header-gradient" style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div class="icon-bg-violet" style="width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                    <svg width="20" height="20" fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827;">Informasi Profil</h3>
                                    <p style="font-size: 0.875rem; color: #6b7280;">Perbarui informasi profil dan data pribadi Anda</p>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 1.5rem;">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
                
                {{-- Password Tab Content --}}
                <div id="tab-password" class="tab-content">
                    <div class="card-modern shadow-violet">
                        <div class="card-header-gradient-amber" style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div class="icon-bg-amber" style="width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                    <svg width="20" height="20" fill="none" stroke="#d97706" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827;">Keamanan Akun</h3>
                                    <p style="font-size: 0.875rem; color: #6b7280;">Pastikan password Anda kuat dan aman</p>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 1.5rem;">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
                
                {{-- Delete Tab Content --}}
                <div id="tab-delete" class="tab-content">
                    <div class="card-modern shadow-red">
                        <div class="card-header-gradient-red" style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div class="icon-bg-red" style="width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                    <svg width="20" height="20" fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #dc2626;">Hapus Akun</h3>
                                    <p style="font-size: 0.875rem; color: #6b7280;">Tindakan ini tidak dapat dibatalkan</p>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 1.5rem;">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

{{-- Tab Switching Script --}}
<script>
    function switchTab(tabName, btn) {
        // Remove active class from all tabs
        document.querySelectorAll('.tab-btn').forEach(tab => {
            tab.classList.remove('active', 'active-red');
        });
        
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Add active class to clicked tab
        if (tabName === 'delete') {
            btn.classList.add('active-red');
        } else {
            btn.classList.add('active');
        }
        
        // Show corresponding content
        document.getElementById('tab-' + tabName).classList.add('active');
    }
</script>
@endsection
