@extends('layouts.admin')
@section('title', 'Data Alat')
@section('page-title', 'Kelola Alat')

@section('content')
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Daftar Alat</h6>
        <a href="{{ route('admin.alat.create') }}" class="btn btn-sm px-3"
           style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.82rem;">
            + Tambah Alat
        </a>
    </div>

    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Kode</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alats as $index => $alat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if($alat->foto)
                        <img src="{{ asset('storage/' . $alat->foto) }}"
                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                    @else
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-image" style="color: var(--text-muted);"></i>
                        </div>
                    @endif
                </td>
                <td style="color: var(--text-muted); font-size: 0.78rem;">{{ $alat->kode_alat }}</td>
                <td style="color: var(--text-primary)">{{ $alat->nama_alat }}</td>
                <td>{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $alat->stok }}</td>
                <td>
                    @if($alat->kondisi == 'baik')
                        <span class="status-badge badge-green">Baik</span>
                    @elseif($alat->kondisi == 'rusak')
                        <span class="status-badge badge-red">Rusak</span>
                    @else
                        <span class="status-badge badge-yellow">Perbaikan</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.alat.edit', $alat) }}"
                       class="btn btn-sm me-1"
                       style="background: rgba(245,158,11,0.15); color: #fbbf24; border-radius: 6px; font-size: 0.75rem;">
                        Edit
                    </a>
                    <button onclick="confirmDelete(`{{ route('admin.alat.destroy', $alat) }}`)"
                            class="btn btn-sm"
                            style="background: rgba(239,68,68,0.15); color: #f87171; border-radius: 6px; font-size: 0.75rem;">
                        Hapus
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada data alat
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
                Apakah kamu yakin ingin menghapus alat ini?
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