@extends('layouts.petugas')
@section('title', 'Validasi Peminjaman')
@section('page-title', 'Validasi Peminjaman')

@section('content')
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Daftar Peminjaman</h6>
    </div>

    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Keperluan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div style="color: var(--text-primary); font-weight: 500;">{{ $item->user->name ?? '-' }}</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $item->user->nisn ?? '' }}</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $item->user->kelas ?? '' }} - {{ $item->user->jurusan ?? '' }}</div>
                </td>
                <td>
                    <div style="color: var(--text-primary);">{{ $item->alat->nama_alat ?? '-' }}</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">Stok: {{ $item->alat->stok ?? 0 }}</div>
                </td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}</td>
                <td style="font-size: 0.78rem; max-width: 150px;">{{ $item->keterangan ?? '-' }}</td>
                <td>
                    @if($item->status == 'menunggu')
                        <span class="status-badge badge-yellow">Menunggu</span>
                    @elseif($item->status == 'dipinjam')
                        <span class="status-badge badge-blue">Dipinjam</span>
                    @elseif($item->status == 'dikembalikan')
                        <span class="status-badge badge-green">Dikembalikan</span>
                    @elseif($item->status == 'menunggu_verifikasi')
                        <span class="status-badge badge-yellow">Menunggu Verifikasi</span>
                    @elseif($item->status == 'perlu_bayar_denda')
                        <span class="status-badge badge-red">Perlu Bayar Denda</span>
                    @else
                        <span class="status-badge badge-red">Ditolak</span>
                    @endif
                </td>
                <td>
                    @if($item->status == 'menunggu')
                    <form action="{{ route('petugas.peminjaman.approve', $item) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm mb-1"
                                style="background: rgba(16,185,129,0.15); color: #34d399; border-radius: 6px; font-size: 0.75rem;">
                            ✅ Setujui
                        </button>
                    </form>
                    <button onclick="showTolakModal({{ $item->id }})" class="btn btn-sm"
                            style="background: rgba(239,68,68,0.15); color: #f87171; border-radius: 6px; font-size: 0.75rem;">
                        ❌ Tolak
                    </button>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.78rem;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada data peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="tolakModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--card-border);">
            <div class="modal-header" style="border-color: var(--card-border)">
                <h6 class="modal-title" style="color: var(--text-primary)">❌ Tolak Peminjaman</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="tolakForm" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">
                        Alasan Penolakan <span style="color: #f87171;">*</span>
                    </label>
                    <textarea name="alasan_tolak" rows="3" required
                              placeholder="Jelaskan alasan penolakan..."
                              class="form-control"
                              style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;"></textarea>
                </div>
                <div class="modal-footer" style="border-color: var(--card-border)">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger">Ya, Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showTolakModal(id) {
    document.getElementById('tolakForm').action = `/petugas/peminjaman/${id}/tolak`;
    new bootstrap.Modal(document.getElementById('tolakModal')).show();
}
</script>
@endpush
@endsection