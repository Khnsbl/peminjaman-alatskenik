@extends('layouts.user')
@section('title', 'Kembalikan Alat')
@section('page-title', 'Kembalikan Alat')

@section('content')
<div class="dark-card" style="max-width: 600px;">
    <a href="{{ route('user.peminjaman.index') }}"
       style="font-size: 0.82rem; color: var(--accent-hover); text-decoration: none;">
        ← Kembali
    </a>

    <h6 class="mt-3 mb-4" style="color: var(--text-primary); font-weight: 600;">Form Pengembalian Alat</h6>

    {{-- Info Peminjaman --}}
    <div class="mb-4 p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid var(--card-border);">
        <div class="row g-2">
            <div class="col-6">
                <div style="font-size: 0.75rem; color: var(--text-muted);">Alat</div>
                <div style="font-size: 0.88rem; color: var(--text-primary); font-weight: 600;">{{ $peminjaman->alat->nama_alat }}</div>
            </div>
            <div class="col-6">
                <div style="font-size: 0.75rem; color: var(--text-muted);">Jumlah</div>
                <div style="font-size: 0.88rem; color: var(--text-primary); font-weight: 600;">{{ $peminjaman->jumlah }}</div>
            </div>
            <div class="col-6">
                <div style="font-size: 0.75rem; color: var(--text-muted);">Tanggal Pinjam</div>
                <div style="font-size: 0.88rem; color: var(--text-primary);">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</div>
            </div>
            <div class="col-6">
                <div style="font-size: 0.75rem; color: var(--text-muted);">Batas Kembali</div>
                <div style="font-size: 0.88rem; color: {{ $peminjaman->tanggal_kembali && \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->isPast() ? '#f87171' : 'var(--text-primary)' }}; font-weight: 600;">
                    {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}
                </div>
            </div>
        </div>

        {{-- Peringatan denda --}}
        @if($peminjaman->tanggal_kembali && \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->isPast())
        @php
            $hariTerlambat = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->diffInDays(\Carbon\Carbon::now());
            $dendaPerHari  = 5000;
            $totalDenda    = $hariTerlambat * $dendaPerHari;
        @endphp
        <div class="mt-3 p-3 rounded" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);">
            <div style="color: #f87171; font-size: 0.85rem; font-weight: 600;">
                ⚠️ Kamu terlambat {{ $hariTerlambat }} hari!
            </div>
            <div style="color: #fca5a5; font-size: 0.82rem; margin-top: 4px;">
                Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}
                <span style="color: var(--text-muted);">(Rp {{ number_format($dendaPerHari, 0, ',', '.') }}/hari)</span>
            </div>
        </div>
        @endif
    </div>

    <form action="{{ route('user.peminjaman.konfirmasi', $peminjaman) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">
                📷 Foto Bukti Pengembalian <span style="color: #f87171;">*</span>
            </label>
            <input type="file" name="foto_bukti" accept="image/*" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                Upload foto kondisi alat saat dikembalikan
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Tanggal Dikembalikan</label>
            <input type="date" name="tanggal_dikembalikan" value="{{ date('Y-m-d') }}" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        <div class="mb-4">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Catatan Kondisi Alat</label>
            <textarea name="catatan" rows="3" placeholder="Jelaskan kondisi alat saat dikembalikan..." class="form-control"
                      style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">{{ old('catatan') }}</textarea>
        </div>

        <button type="submit" class="btn btn-sm px-4"
                style="background: #34d399; color: #fff; border-radius: 8px; font-size: 0.85rem; border: none;">
            ✅ Kirim Bukti Pengembalian
        </button>
    </form>
</div>
@endsection