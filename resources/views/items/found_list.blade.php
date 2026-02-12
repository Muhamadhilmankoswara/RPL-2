@extends('layouts.app')

@section('title', 'Barang Ditemukan - Lost & Found')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active">Barang Ditemukan</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="section-title mb-1">
                <i class="bi bi-check-circle text-success me-2"></i>Barang Ditemukan
            </h2>
            <p class="text-muted mb-0">Daftar barang yang telah ditemukan dan dilaporkan</p>
        </div>
        <a href="{{ route('items.createFound') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-2"></i>Laporkan Barang Ditemukan
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('items.found') }}" method="GET">
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
                                <a href="{{ route('items.found') }}" class="btn btn-outline-secondary">
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
                            <span class="badge badge-found position-absolute top-0 end-0 m-2">
                                <i class="bi bi-check-circle me-1"></i>Ditemukan
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-truncate">{{ $item->title }}</h5>
                            <p class="card-text mb-2">
                                <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($item->location, 25) }}
                            </p>
                            <p class="card-text small text-muted mb-2">
                                <i class="bi bi-calendar me-1"></i>{{ $item->found_date ? $item->found_date->format('d M Y') : $item->created_at->format('d M Y') }}
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 0.75rem; font-weight: 600;">
                                    {{ strtoupper(substr($item->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="small text-muted text-truncate">{{ $item->user->name ?? 'Anonymous' }}</span>
                            </div>
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
                <i class="bi bi-search"></i>
                <h5>Tidak ada barang ditemukan</h5>
                <p class="mb-3">
                    @if(request('search') || request('category'))
                        Tidak ada hasil yang cocok dengan pencarian Anda.
                    @else
                        Belum ada barang yang dilaporkan ditemukan.
                    @endif
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('items.found') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                @endif
                <a href="{{ route('items.createFound') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Laporkan Barang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
