@extends('layouts.user')
@section('title', 'Daftar Alat')
@section('page-title', 'Daftar Alat')

@section('content')
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Alat Tersedia</h6>
        <a href="{{ route('user.peminjaman.create') }}" class="btn btn-sm px-3"
           style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.82rem;">
            + Ajukan Peminjaman
        </a>
    </div>

    <div class="row g-3">
        @forelse($alats as $alat)
        <div class="col-md-4">
            <div style="background: var(--main-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden;">
                @if($alat->foto)
                    <img src="{{ asset('storage/' . $alat->foto) }}"
                         style="width: 100%; height: 160px; object-fit: cover;">
                @else
                    <div style="width: 100%; height: 160px; background: rgba(255,255,255,0.03); display: flex; align-items: center; justify-content: center; font-size: 3rem;">🔧</div>
                @endif
                <div class="p-3">
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">{{ $alat->nama_alat }}</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 8px;">{{ $alat->kategori->nama_kategori ?? '-' }}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size: 0.78rem; color: var(--text-secondary);">Stok: <strong style="color: var(--text-primary);">{{ $alat->stok }}</strong></span>
                        <span class="status-badge badge-green">Tersedia</span>
                    </div>
                    <a href="{{ route('user.peminjaman.create') }}?alat_id={{ $alat->id }}"
                       class="btn btn-sm w-100 mt-3"
                       style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.82rem;">
                        Pinjam Sekarang
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5" style="color: var(--text-muted);">
            Tidak ada alat yang tersedia saat ini
        </div>
        @endforelse
    </div>
</div>
@endsection