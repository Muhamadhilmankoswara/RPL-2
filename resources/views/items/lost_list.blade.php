@extends('layouts.app')

@section('title', 'Barang Hilang - Lost & Found')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active">Barang Hilang</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="section-title mb-1">
                <i class="bi bi-question-circle text-danger me-2"></i>Barang Hilang
            </h2>
            <p class="text-muted mb-0">Daftar barang yang dilaporkan hilang</p>
        </div>
        <a href="{{ route('items.createLost') }}" class="btn btn-danger">
            <i class="bi bi-plus-circle me-2"></i>Laporkan Barang Hilang
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('items.lost') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari barang..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            @if(request('search') || request('category'))
                                <a href="{{ route('items.lost') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Grid -->
    @if($items->count() > 0)
        <div class="row g-4">
            @foreach($items as $item)
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
                            <span class="badge badge-lost position-absolute top-0 end-0 m-2">
                                <i class="bi bi-exclamation-circle me-1"></i>Hilang
                            </span>
                            @if($item->reward)
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                                    <i class="bi bi-gift me-1"></i>Reward
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-truncate">{{ $item->title }}</h5>
                            <p class="card-text mb-2">
                                <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($item->location, 25) }}
                            </p>
                            <p class="card-text small text-muted mb-2">
                                <i class="bi bi-calendar me-1"></i>{{ $item->lost_date ? $item->lost_date->format('d M Y') : $item->created_at->format('d M Y') }}
                            </p>
                            @if($item->reward)
                                <p class="card-text small text-warning mb-0">
                                    <i class="bi bi-gift me-1"></i>{{ $item->reward }}
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5">
            {{ $items->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="empty-state">
                <i class="bi bi-question-circle"></i>
                <h5>Tidak ada barang hilang</h5>
                <p class="mb-3">
                    @if(request('search') || request('category'))
                        Tidak ada hasil yang cocok dengan pencarian Anda.
                    @else
                        Belum ada barang yang dilaporkan hilang.
                    @endif
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('items.lost') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                @endif
                <a href="{{ route('items.createLost') }}" class="btn btn-danger">
                    <i class="bi bi-plus-circle me-2"></i>Laporkan Barang Hilang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
