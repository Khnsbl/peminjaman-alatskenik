@extends('layouts.admin')
@section('title', 'Peminjaman')
@section('page-title', 'Kelola Peminjaman')

@section('content')
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-600" style="color: var(--text-primary)">Daftar Peminjaman</h6>
        <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-sm" 
           style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.82rem;">
            + Tambah Peminjaman
        </a>
    </div>

    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Alat</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $peminjaman)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="color: var(--text-primary)">{{ $peminjaman->user->name }}</td>
                <td>{{ $peminjaman->alat->nama_alat }}</td>
                <td>{{ $peminjaman->jumlah }}</td>
                <td>{{ $peminjaman->tanggal_pinjam }}</td>
                <td>{{ $peminjaman->tanggal_kembali ?? '-' }}</td>
                <td>
                    @if($peminjaman->status == 'menunggu')
                        <span class="status-badge badge-yellow">Menunggu</span>
                    @elseif($peminjaman->status == 'dipinjam')
                        <span class="status-badge badge-blue">Dipinjam</span>
                    @elseif($peminjaman->status == 'dikembalikan')
                        <span class="status-badge badge-green">Dikembalikan</span>
                    @else
                        <span class="status-badge badge-red">Ditolak</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.peminjaman.edit', $peminjaman) }}"
                       class="btn btn-sm me-1"
                       style="background: rgba(245,158,11,0.15); color: #fbbf24; border-radius: 6px; font-size: 0.75rem;">
                        Edit
                    </a>
                    <button onclick="confirmDelete(`{{ route('admin.peminjaman.destroy', $peminjaman) }}`)"
                            class="btn btn-sm"
                            style="background: rgba(239,68,68,0.15); color: #f87171; border-radius: 6px; font-size: 0.75rem;">
                        Hapus
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada data peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--card-border);">
            <div class="modal-header" style="border-color: var(--card-border)">
                <h6 class="modal-title" style="color: var(--text-primary)">⚠️ Konfirmasi Hapus</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color: var(--text-secondary)">
                Apakah kamu yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer" style="border-color: var(--card-border)">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(url) {
        document.getElementById('deleteForm').action = url;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
@endsection