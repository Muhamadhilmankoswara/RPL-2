@extends('layouts.app')

@section('title', 'Laporkan Barang Hilang - Lost & Found')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('items.lost') }}" class="text-decoration-none">Barang Hilang</a></li>
            <li class="breadcrumb-item active">Laporkan</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-question-circle" style="font-size: 2.5rem;"></i>
                        </div>
                        <h2 class="section-title mb-2">Laporkan Barang Hilang</h2>
                        <p class="text-muted mb-0">Bantu kami menemukan barang Anda yang hilang</p>
                    </div>

                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan</h6>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('items.storeLost') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Foto Barang -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Foto Barang</label>

                        <div
                            class="image-preview"
                            style="
                                height:260px;
                                border:2px dashed #cbd5e1;
                                border-radius:12px;
                                cursor:pointer;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                overflow:hidden;
                                background:#f8fafc;
                            "
                            onclick="document.getElementById('photo').click()"
                        >
                            <input
                                type="file"
                                name="photo"
                                id="photo"
                                class="d-none"
                                accept="image/*"
                            >

                            <img
                                id="previewImage"
                                alt="Preview"
                                style="
                                    display:none;
                                    width:100%;
                                    height:100%;
                                    object-fit:cover;
                                "
                            >

                            <div id="previewPlaceholder" class="text-center">
                                <i class="bi bi-camera" style="font-size:3rem;"></i>
                                <p class="mb-1">Klik untuk upload foto barang</p>
                                <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                            </div>
                        </div>

                        @error('photo')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            Upload foto barang jika ada (opsional tapi direkomendasikan)
                        </div>
                    </div>

                        <!-- Judul -->
                        <div class="form-floating mb-3">
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Judul" value="{{ old('title') }}" required>
                            <label for="title">Judul Barang <span class="text-danger">*</span></label>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="form-floating mb-3">
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Pilih kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="category_id">Kategori <span class="text-danger">*</span></label>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-floating mb-3">
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Deskripsi" style="height: 120px;" required>{{ old('description') }}</textarea>
                            <label for="description">Deskripsi Barang <span class="text-danger">*</span></label>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Jelaskan ciri-ciri khusus barang agar mudah dikenali</div>
                        </div>

                        <!-- Lokasi -->
                        <div class="form-floating mb-3">
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" placeholder="Lokasi" value="{{ old('location') }}" required>
                            <label for="location">Lokasi Terakhir Dilihat <span class="text-danger">*</span></label>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal -->
                        <div class="form-floating mb-3">
                            <input type="date" name="date" id="lost_date" class="form-control @error('lost_date') is-invalid @enderror" value="{{ old('lost_date', date('Y-m-d')) }}" required>
                            <label for="lost_date">Tanggal Hilang <span class="text-danger">*</span></label>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-3">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary flex-fill">
                                <i class="bi bi-x-lg me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-danger flex-fill">
                                <i class="bi bi-send me-2"></i>Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('photo');
    const preview = document.getElementById('previewImage');
    const placeholder = document.getElementById('previewPlaceholder');

    input.addEventListener('change', function () {
        if (!this.files || !this.files[0]) return;

        preview.src = URL.createObjectURL(this.files[0]);
        preview.style.display = 'block';
        placeholder.style.display = 'none';
    });
});
</script>
@endsection



