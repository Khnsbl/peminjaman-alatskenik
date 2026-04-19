@extends('layouts.petugas')
@section('title', 'Verifikasi Pengembalian')
@section('page-title', 'Verifikasi Pengembalian')

@section('content')
<style>
    .btn-aksi {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
        font-family: inherit;
    }
    .btn-selesai {
        background: rgba(16,185,129,0.15);
        color: #34d399;
        border: 1px solid rgba(16,185,129,0.3);
    }
    .btn-selesai:hover { background: rgba(16,185,129,0.25); }
    .btn-denda {
        background: rgba(245,158,11,0.15);
        color: #fbbf24;
        border: 1px solid rgba(245,158,11,0.3);
    }
    .btn-denda:hover { background: rgba(245,158,11,0.25); }
    .btn-lunas {
        background: rgba(99,102,241,0.15);
        color: #818cf8;
        border: 1px solid rgba(99,102,241,0.3);
    }
    .btn-lunas:hover { background: rgba(99,102,241,0.25); }
    .kondisi-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 600;
    }
    .kondisi-baik { background: rgba(16,185,129,0.15); color: #34d399; }
    .kondisi-rusak-ringan { background: rgba(245,158,11,0.15); color: #fbbf24; }
    .kondisi-rusak-berat { background: rgba(239,68,68,0.15); color: #f87171; }
</style>

<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Daftar Pengembalian</h6>
    </div>

    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Batas Kembali</th>
                <th>Tgl Dikembalikan</th>
                <th>Kondisi</th>
                <th>Keterangan</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $item)
            @php
                $terlambat = $item->tanggal_kembali && $item->tanggal_dikembalikan
                    && \Carbon\Carbon::parse($item->tanggal_dikembalikan)->gt(\Carbon\Carbon::parse($item->tanggal_kembali));
                $hariTerlambat = $terlambat
                    ? \Carbon\Carbon::parse($item->tanggal_kembali)->diffInDays(\Carbon\Carbon::parse($item->tanggal_dikembalikan))
                    : 0;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div style="color: var(--text-primary); font-weight: 500;">{{ $item->user->name ?? '-' }}</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $item->user->kelas ?? '' }}</div>
                </td>
                <td>
                    <div style="color: var(--text-primary);">{{ $item->alat->nama_alat ?? '-' }}</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">×{{ $item->jumlah }}</div>
                </td>
                <td>
                    @if($item->tanggal_kembali)
                        <span style="color: {{ $terlambat ? '#f87171' : 'var(--text-primary)' }}">
                            {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                        </span>
                    @else
                        <span style="color: var(--text-muted)">-</span>
                    @endif
                </td>
                <td>
                    @if($item->tanggal_dikembalikan)
                        <div>{{ \Carbon\Carbon::parse($item->tanggal_dikembalikan)->format('d M Y') }}</div>
                        @if($terlambat)
                            <div style="font-size: 0.7rem; color: #f87171;">⚠️ Terlambat {{ $hariTerlambat }} hari</div>
                        @endif
                    @else
                        <span style="color: var(--text-muted)">-</span>
                    @endif
                </td>
                <td>
                    @if($item->kondisi_kembali == 'baik')
                        <span class="kondisi-badge kondisi-baik">✅ Baik</span>
                    @elseif($item->kondisi_kembali == 'rusak_ringan')
                        <span class="kondisi-badge kondisi-rusak-ringan">⚠️ Rusak Ringan</span>
                    @elseif($item->kondisi_kembali == 'rusak_berat')
                        <span class="kondisi-badge kondisi-rusak-berat">❌ Rusak Berat</span>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>
                <td style="font-size: 0.78rem; max-width: 150px; color: var(--text-muted);">
                    {{ $item->keterangan ?? '-' }}
                </td>
                <td>
                    @if($item->foto_bukti)
                        <a href="{{ asset('storage/' . $item->foto_bukti) }}" target="_blank">
                            <img src="{{ asset('storage/' . $item->foto_bukti) }}"
                                 style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 1px solid var(--card-border);">
                        </a>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>
                <td>
                    @if($item->status == 'menunggu_verifikasi')
                        <span class="status-badge badge-yellow">Menunggu Verifikasi</span>
                    @elseif($item->status == 'perlu_bayar_denda')
                        <span class="status-badge badge-red">Perlu Bayar Denda</span>
                    @endif
                </td>
                <td>
                    @if($item->denda > 0)
                        <span style="color: #f87171; font-weight: 600; font-size: 0.82rem;">
                            Rp {{ number_format($item->denda, 0, ',', '.') }}
                        </span>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>
                <td>
                    @if($item->status == 'menunggu_verifikasi')
                        {{-- Selesai tanpa denda --}}
                        <form action="{{ route('petugas.pengembalian.verifikasi', $item) }}" method="POST" class="d-inline mb-1">
                            @csrf
                            <input type="hidden" name="aksi" value="selesai">
                            <button type="submit" class="btn-aksi btn-selesai mb-1">✅ Selesai</button>
                        </form>
                        {{-- Kenakan denda --}}
                        <button class="btn-aksi btn-denda" onclick="showDendaModal({{ $item->id }})">💰 Denda</button>

                    @elseif($item->status == 'perlu_bayar_denda')
                        <form action="{{ route('petugas.pengembalian.konfirmasi-denda', $item) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-aksi btn-lunas">✅ Konfirmasi Lunas</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-4" style="color: var(--text-muted)">
                    Tidak ada pengembalian yang perlu diverifikasi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Input Denda --}}
<div class="modal fade" id="dendaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--card-border);">
            <div class="modal-header" style="border-color: var(--card-border)">
                <h6 class="modal-title" style="color: var(--text-primary)">💰 Kenakan Denda</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="dendaForm" method="POST">
                @csrf
                <input type="hidden" name="aksi" value="denda">
                <div class="modal-body">
                    <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">
                        Nominal Denda (Rp) <span style="color: #f87171;">*</span>
                    </label>
                    <input type="number" name="denda" min="0" required
                           placeholder="Contoh: 10000"
                           class="form-control"
                           style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 6px;">
                        Masukkan nominal denda yang harus dibayar user.
                    </div>
                </div>
                <div class="modal-footer" style="border-color: var(--card-border)">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning">Kenakan Denda</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDendaModal(id) {
    document.getElementById('dendaForm').action = `/petugas/pengembalian/${id}/verifikasi`;
    new bootstrap.Modal(document.getElementById('dendaModal')).show();
}
</script>
@endpush
@endsection