@extends('layouts.admin')
@section('title', 'Edit Alat')
@section('page-title', 'Edit Alat')

@section('content')
<div class="dark-card" style="max-width: 600px;">
    <a href="{{ route('admin.alat.index') }}"
       style="font-size: 0.82rem; color: var(--accent-hover); text-decoration: none;">
        ← Kembali
    </a>

    <h6 class="mt-3 mb-4" style="color: var(--text-primary); font-weight: 600;">Form Edit Alat</h6>

    @if($errors->any())
        <div class="mb-3 p-3 rounded" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171; font-size: 0.82rem;">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.alat.update', $alat) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Kode Alat</label>
            <input type="text" name="kode_alat" value="{{ old('kode_alat', $alat->kode_alat) }}" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Nama Alat</label>
            <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Kategori</label>
            <select name="kategori_id" required class="form-select"
                    style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}"
                        {{ old('kategori_id', $alat->kategori_id) == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Stok</label>
            <input type="number" name="stok" value="{{ old('stok', $alat->stok) }}" min="0" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Kondisi</label>
            <select name="kondisi" required class="form-select"
                    style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
                <option value="baik" {{ old('kondisi', $alat->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak" {{ old('kondisi', $alat->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="perbaikan" {{ old('kondisi', $alat->kondisi) == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Foto Alat</label>
            @if($alat->foto)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $alat->foto) }}"
                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Foto saat ini</div>
                </div>
            @endif
            <input type="file" name="foto" accept="image/*"
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Kosongkan jika tidak ingin mengubah foto</div>
        </div>

        <button type="submit" class="btn btn-sm px-4"
                style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.85rem;">
            Update
        </button>
    </form>
</div>
@endsection