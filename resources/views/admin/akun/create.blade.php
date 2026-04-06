@extends('layouts.admin')
@section('title', 'Tambah Akun')
@section('page-title', 'Tambah Akun')

@section('content')
<div class="dark-card" style="max-width: 600px;">
    <a href="{{ route('admin.akun.index') }}"
       style="font-size: 0.82rem; color: var(--accent-hover); text-decoration: none;">
        ← Kembali
    </a>

    <h6 class="mt-3 mb-4" style="color: var(--text-primary); font-weight: 600;">Form Tambah Akun</h6>

    @if($errors->any())
        <div class="mb-3 p-3 rounded" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171; font-size: 0.82rem;">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.akun.store') }}" method="POST">
        @csrf

        {{-- Role di atas --}}
        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Role</label>
            <select name="role" id="roleSelect" required class="form-select"
                    style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
                <option value="">-- Pilih Role --</option>
                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Siswa</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        {{-- Field khusus siswa --}}
        <div id="siswaFields">
            <div class="mb-3">
                <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">NISN</label>
                <input type="text" name="nisn" value="{{ old('nisn') }}"
                       class="form-control"
                       style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Kelas</label>
                <input type="text" name="kelas" value="{{ old('kelas') }}"
                       class="form-control"
                       style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Jurusan</label>
                <input type="text" name="jurusan" value="{{ old('jurusan') }}"
                       class="form-control"
                       style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        <div class="mb-4">
            <label class="form-label" style="font-size: 0.82rem; color: var(--text-secondary);">Password</label>
            <input type="password" name="password" required
                   class="form-control"
                   style="background: var(--main-bg); border-color: var(--card-border); color: var(--text-primary); font-size: 0.85rem;">
        </div>

        <button type="submit" class="btn btn-sm px-4"
                style="background: var(--accent); color: #fff; border-radius: 8px; font-size: 0.85rem;">
            Buat Akun
        </button>
    </form>
</div>

@push('scripts')
<script>
    const roleSelect = document.getElementById('roleSelect');
    const siswaFields = document.getElementById('siswaFields');

    function toggleSiswaFields() {
        if (roleSelect.value === 'user') {
            siswaFields.style.display = 'block';
        } else {
            siswaFields.style.display = 'none';
        }
    }

    // Jalankan saat halaman load
    toggleSiswaFields();

    // Jalankan saat role berubah
    roleSelect.addEventListener('change', toggleSiswaFields);
</script>
@endpush
@endsection