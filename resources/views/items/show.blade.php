@extends('layouts.app')

@section('title', $item->title . ' - Lost & Found')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            @if($item->type == 'found')
                <li class="breadcrumb-item"><a href="{{ route('items.found') }}" class="text-decoration-none">Barang Ditemukan</a></li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('items.lost') }}" class="text-decoration-none">Barang Hilang</a></li>
            @endif
            <li class="breadcrumb-item active">{{ Str::limit($item->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Left Column - Image -->
        <div class="col-lg-5">
            <div class="card">
                <div class="position-relative">
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 400px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                    <span class="badge {{ $item->type == 'found' ? 'badge-found' : 'badge-lost' }} position-absolute top-0 end-0 m-3" style="font-size: 0.875rem;">
                        @if($item->type == 'found')
                            <i class="bi bi-check-circle me-1"></i>Ditemukan
                        @else
                            <i class="bi bi-exclamation-circle me-1"></i>Hilang
                        @endif
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
<div class="card mt-3">
    <div class="card-body">
        @auth
            @php
                $userId = auth()->id();
                $isUploader = ($item->user_id == $userId);

                // Apakah user ini sudah konfirmasi?
                $userConfirmed = $item->confirmations
                    ->where('user_id', $userId)
                    ->where('confirmed', 1)
                    ->count() > 0;

                // Apakah sudah ada "pemilik" (non-uploader) yang konfirmasi?
                $ownerConfirmed = $item->confirmations
                    ->where('user_id', '!=', $item->user_id)
                    ->where('confirmed', 1)
                    ->count() > 0;

                // Apakah uploader sudah konfirmasi?
                $finderConfirmed = $item->confirmations
                    ->where('user_id', $item->user_id)
                    ->where('confirmed', 1)
                    ->count() > 0;

                // Selesai kalau dua pihak sudah konfirmasi
                $isCompleted = $ownerConfirmed && $finderConfirmed;
            @endphp

            {{-- Kalau sudah completed, tampilkan info saja --}}
            @if($isCompleted)
                <div class="alert alert-success mb-0">
                    <i class="bi bi-check-circle me-2"></i>Status: <strong>Completed</strong> (dua pihak sudah konfirmasi)
                </div>

            @else
                {{-- BUKAN uploader (calon pemilik) --}}
                @if(!$isUploader)
                    <a href="{{ route('chat.start', $item) }}" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-chat-dots me-2"></i>Mulai Chat
                    </a>

                    {{-- Tombol klaim hanya kalau belum pernah konfirmasi --}}
                    @if(!$userConfirmed)
                        <form method="POST" action="{{ route('items.confirm.owner', $item) }}">
                            @csrf
                            <button class="btn btn-outline-success w-100">
                                <i class="bi bi-check-circle me-2"></i>Ini Barang Saya
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-hourglass-split me-2"></i>Anda sudah mengklaim. Menunggu konfirmasi penemu.
                        </div>
                    @endif

                {{-- uploader (penemu) --}}
                @else
                    {{-- Penemu baru bisa konfirmasi kalau sudah ada pemilik yang klaim --}}
                    @if($ownerConfirmed && !$userConfirmed)
                        <form method="POST" action="{{ route('items.confirm.finder', $item) }}">
                            @csrf
                            <button class="btn btn-success w-100">
                                <i class="bi bi-box-seam me-2"></i>Barang Sudah Diserahkan
                            </button>
                        </form>
                    @elseif(!$ownerConfirmed)
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>Menunggu ada pemilik yang mengklaim barang ini.
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>Anda sudah konfirmasi.
                        </div>
                    @endif
                @endif
            @endif
        @else
            <div class="alert alert-light border mb-0 text-center">
                <p class="mb-2">Login untuk menghubungi pemilik barang</p>
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </a>
            </div>
        @endauth
    </div>
</div>
        </div>

        <!-- Right Column - Details -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <!-- Title -->
                    <h2 class="section-title mb-3">{{ $item->title }}</h2>

                    <!-- Meta Info -->
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-tag me-1"></i>{{ $item->category->name ?? 'Uncategorized' }}
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-calendar me-1"></i>
                            @if($item->type == 'found')
                                {{ $item->found_date ? $item->found_date->format('d M Y') : $item->created_at->format('d M Y') }}
                            @else
                                {{ $item->lost_date ? $item->lost_date->format('d M Y') : $item->created_at->format('d M Y') }}
                            @endif
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-eye me-1"></i>{{ $item->views ?? 0 }} views
                        </span>
@if(auth()->id() === $item->user_id && !$item->confirmations->where('confirmed',1)->count())
    <!-- EDIT -->
    <button class="btn btn-warning w-100 mb-2"
            data-bs-toggle="modal"
            data-bs-target="#editItemModal">
        <i class="bi bi-pencil-square me-2"></i>Edit Laporan
    </button>

    <!-- DELETE -->
    <form action="{{ route('items.destroy', $item) }}" method="POST"
          onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger w-100">
            <i class="bi bi-trash me-2"></i>Hapus Laporan
        </button>
    </form>
@endif

                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-2">Deskripsi</h6>
                        <p class="mb-0" style="line-height: 1.8;">{{ $item->description }}</p>
                    </div>

                    <!-- Location -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            @if($item->type == 'found')
                                Lokasi Ditemukan
                            @else
                                Lokasi Terakhir Dilihat
                            @endif
                        </h6>
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <i class="bi bi-geo-alt-fill text-primary me-3" style="font-size: 1.5rem;"></i>
                            <span>{{ $item->location }}</span>
                        </div>
                    </div>

                    <!-- Reward (for lost items) -->
                    @if($item->type == 'lost' && $item->reward)
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-2">
                                <i class="bi bi-gift me-2"></i>Hadiah
                            </h6>
                            <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded border border-warning">
                                <i class="bi bi-gift-fill text-warning me-3" style="font-size: 1.5rem;"></i>
                                <span class="fw-medium">{{ $item->reward }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Contact Info (for found items) -->
                    @if($item->type == 'found' && $item->contact_info)
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-2">
                                <i class="bi bi-telephone me-2"></i>Informasi Kontak
                            </h6>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="bi bi-person-lines-fill text-primary me-3" style="font-size: 1.5rem;"></i>
                                <span>{{ $item->contact_info }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Posted By -->
                    <div class="border-top pt-4">
                        <h6 class="fw-bold text-muted mb-3">Diposting Oleh</h6>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 1.25rem; font-weight: 600;">
                                {{ strtoupper(substr($item->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $item->user->name ?? 'Anonymous' }}</h6>
                                <small class="text-muted">Bergabung {{ $item->user->created_at->diffForHumans() ?? 'recently' }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Posted Date -->
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>Diposting {{ $item->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@auth
@if(auth()->id() === $item->user_id && !$item->confirmations->where('confirmed',1)->count())
<div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form method="POST"
                  action="{{ route('items.update', $item) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Edit Laporan
                    </h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body"
                     style="max-height: 70vh; overflow-y: auto;">

                    <!-- Judul -->
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               value="{{ old('title', $item->title) }}"
                               required>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id"
                                class="form-select"
                                required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description"
                                  rows="4"
                                  class="form-control">{{ old('description', $item->description) }}</textarea>
                    </div>

                    <!-- Lokasi -->
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text"
                               name="location"
                               class="form-control"
                               value="{{ old('location', $item->location) }}"
                               required>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date"
                               name="date"
                               class="form-control"
                               value="{{ old('date', $item->date?->format('Y-m-d')) }}"
                               required>
                    </div>

                    <!-- Foto -->
                    <div class="mb-3">
                        <label class="form-label">Ganti Foto (opsional)</label>
                        <input type="file"
                               name="photo"
                               class="form-control">
                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti foto
                        </small>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit"
                            class="btn btn-warning">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endif
@endauth

@endsection
