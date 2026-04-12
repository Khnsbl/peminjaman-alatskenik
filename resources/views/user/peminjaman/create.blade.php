@extends('layouts.user')
@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman')

@section('content')
<div class="dark-card" style="max-width: 600px;">
    <a href="{{ route('user.peminjaman.index') }}"
       style="font-size: 0.82rem; color: var(--accent-hover); text-decoration: none;">
        ← Kembali
    </a>

    <h6 class="mt-3 mb-4" style="color: var(--text-primary); font-weight: 600;">Form Ajukan Peminjaman</h6>

    @if($errors->any())
        <div class="mb-3 p-3 rounded" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171; font-size: 0.82rem;">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('user.peminjaman.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Pilih Alat</label>
            <select name="alat_id" required class="form-select"
                    style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
                <option value="">-- Pilih Alat --</option>
                @foreach($alats as $alat)
                    <option value="{{ $alat->id }}"
                        {{ (old('alat_id') ?? request('alat_id')) == $alat->id ? 'selected' : '' }}>
                        {{ $alat->nama_alat }} (Stok: {{ $alat->stok }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Jumlah</label>
            <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Tanggal Pengembalian</label>
            <input type="date" name="tanggal_pengembalian" value="{{ old('tanggal_pengembalian', date('Y-m-d')) }}" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        <div class="mb-4">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Keperluan peminjaman..." class="form-control"
                      style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">{{ old('keterangan') }}</textarea>
        </div>

        <button type="submit" class="btn btn-sm px-4"
                style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.85rem;">
            Ajukan Peminjaman
        </button>
    </form>
</div>
@endsection