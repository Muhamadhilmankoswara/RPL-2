@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Home - Lost & Found')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="hero-title">Temukan Barang Hilang Anda</h1>
        <p class="hero-subtitle">Platform Lost & Found terpercaya untuk mempertemukan barang hilang dengan pemiliknya</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('items.createFound') }}" class="btn btn-light btn-lg px-4">
                <i class="bi bi-plus-circle me-2"></i>Laporkan Barang Ditemukan
            </a>
            <a href="{{ route('items.createLost') }}" class="btn btn-outline-light btn-lg px-4">
                <i class="bi bi-search me-2"></i>Laporkan Barang Hilang
            </a>
        </div>
    </div>
</section>

<div class="container">
    <!-- Barang Ditemukan Terbaru -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title mb-1">Barang Ditemukan Terbaru</h2>
                <p class="section-subtitle mb-0">Barang-barang yang baru saja ditemukan dan dilaporkan</p>
            </div>
            <a href="{{ route('items.found') }}" class="btn btn-outline-primary">
                Lihat Semua<i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        @if($foundItems->count() > 0)
            <div class="row g-4">
                @foreach($foundItems as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('items.show', $item) }}" class="text-decoration-none">
                        <div class="card h-100">
                            <div class="position-relative">
                                @if($item->photo)
                                    <img src="{{ asset('storage/' . $item->photo) }}" class="card-img-top" alt="{{ $item->title }}">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <span class="badge badge-found position-absolute top-0 end-0 m-2">
                                    <i class="bi bi-check-circle me-1"></i>Ditemukan
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-truncate">{{ $item->title }}</h5>
                                <p class="card-text mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($item->location, 25) }}
                                </p>
                                <p class="card-text small text-muted mb-0">
                                    <i class="bi bi-calendar me-1"></i>{{ $item->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="card">
                <div class="empty-state">
                    <i class="bi bi-search"></i>
                    <h5>Belum ada barang ditemukan</h5>
                    <p class="mb-3">Jadilah yang pertama melaporkan barang ditemukan!</p>
                    <a href="{{ route('items.createFound') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Laporkan Sekarang
                    </a>
                </div>
            </div>
        @endif
    </section>

    <!-- Barang Hilang Terbaru -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title mb-1">Barang Hilang Terbaru</h2>
                <p class="section-subtitle mb-0">Barang-barang yang baru saja dilaporkan hilang</p>
            </div>
            <a href="{{ route('items.lost') }}" class="btn btn-outline-primary">
                Lihat Semua<i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        @if($lostItems->count() > 0)
            <div class="row g-4">
                @foreach($lostItems as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('items.show', $item) }}" class="text-decoration-none">
                        <div class="card h-100">
                            <div class="position-relative">
                                @if($item->photo)
                                    <img src="{{ asset('storage/' . $item->photo) }}" class="card-img-top" alt="{{ $item->title }}">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:180px">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <span class="badge badge-lost position-absolute top-0 end-0 m-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>Hilang
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-truncate">{{ $item->title }}</h5>
                                <p class="card-text mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($item->location, 25) }}
                                </p>
                                <p class="card-text small text-muted mb-0">
                                    <i class="bi bi-calendar me-1"></i>{{ $item->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="card">
                <div class="empty-state">
                    <i class="bi bi-question-circle"></i>
                    <h5>Belum ada barang hilang dilaporkan</h5>
                    <p class="mb-3">Jadilah yang pertama melaporkan barang hilang!</p>
                    <a href="{{ route('items.createLost') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Laporkan Sekarang
                    </a>
                </div>
            </div>
        @endif
    </section>

    <!-- How It Works -->
    <section class="mb-5">
        <h2 class="section-title text-center mb-5">Cara Kerja</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-upload" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="card-title">1. Laporkan</h5>
                    <p class="card-text text-muted">Unggah informasi barang hilang atau ditemukan dengan foto dan detail lengkap</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-search" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="card-title">2. Cari</h5>
                    <p class="card-text text-muted">Telusuri daftar barang atau tunggu notifikasi jika ada yang cocok</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-chat-dots" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="card-title">3. Hubungi</h5>
                    <p class="card-text text-muted">Gunakan fitur chat untuk berkomunikasi dan mengklaim barang Anda</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="mb-5">
        <div class="card bg-primary text-white">
            <div class="card-body p-5">
                <div class="row text-center">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h3 class="display-5 fw-bold mb-2">{{ $totalFound ?? 0 }}</h3>
                        <p class="mb-0 opacity-75">Barang Ditemukan</p>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h3 class="display-5 fw-bold mb-2">{{ $totalLost ?? 0 }}</h3>
                        <p class="mb-0 opacity-75">Barang Hilang</p>
                    </div>
                    <div class="col-md-4">
                        <h3 class="display-5 fw-bold mb-2">{{ $totalReturned ?? 0 }}</h3>
                        <p class="mb-0 opacity-75">Barang Kembali</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style>
/* FIX card image home */
.card-img-top {
    width: 100%;
    height: 180px;        /* KUNCI TINGGI */
    object-fit: cover;   /* POTONG BIAR RAPIH */
    border-top-left-radius: .375rem;
    border-top-right-radius: .375rem;
}
</style>

@endsection
