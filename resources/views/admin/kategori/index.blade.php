@extends('layouts.admin')
@section('title', 'Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Daftar Kategori</h6>
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-sm px-3"
           style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.82rem;">
            + Tambah Kategori
        </a>
    </div>

    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategoris as $index => $kategori)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="color: var(--text-primary)">{{ $kategori->nama_kategori }}</td>
                <td>{{ $kategori->keterangan ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.kategori.edit', $kategori) }}"
                       class="btn btn-sm me-1"
                       style="background: rgba(245,158,11,0.15); color: #fbbf24; border-radius: 6px; font-size: 0.75rem;">
                        Edit
                    </a>
                    <button onclick="confirmDelete(`{{ route('admin.kategori.destroy', $kategori) }}`)"
                            class="btn btn-sm"
                            style="background: rgba(239,68,68,0.15); color: #f87171; border-radius: 6px; font-size: 0.75rem;">
                        Hapus
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada kategori
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
                Apakah kamu yakin ingin menghapus kategori ini?
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