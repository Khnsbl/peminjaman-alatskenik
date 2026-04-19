@extends('layouts.user')
@section('title', 'Ajukan Pengembalian')
@section('page-title', 'Ajukan Pengembalian')

@section('content')
<style>
    .form-panel {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 28px 32px 36px;
        max-width: 600px;
        width: 100%;
        margin: 0 auto;
    }
    .form-back {
        font-size: 13px;
        color: var(--accent);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 20px;
        transition: opacity 0.15s;
    }
    .form-back:hover { opacity: 0.75; color: var(--accent); text-decoration: none; }
    .form-title { font-size: 17px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
    .form-subtitle { font-size: 13px; color: var(--text-muted); margin-bottom: 28px; }
    .field { margin-bottom: 20px; }
    .field-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 7px;
    }
    .field-input {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 11px 14px;
        color: var(--text-primary);
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border-color 0.15s, background 0.15s;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
    }
    .field-input:focus { border-color: var(--accent); background: rgba(99,102,241,0.06); }
    .field-input.is-error { border-color: #f87171; }
    .field-input option { background: #1a1d27; color: #f0f2f8; }
    .field-input::placeholder { color: var(--text-muted); }
    .field-error { font-size: 11px; color: #f87171; margin-top: 5px; }
    .field-hint { font-size: 11px; color: var(--text-muted); margin-top: 5px; }
    .form-divider { height: 1px; background: var(--card-border); margin: 24px 0; }
    .btn-submit {
        width: 100%;
        padding: 13px;
        border-radius: 10px;
        background: var(--accent);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
        font-family: 'Plus Jakarta Sans', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 8px;
    }
    .btn-submit:hover { background: #4f46e5; transform: translateY(-1px); }
    .info-box {
        background: rgba(99,102,241,0.06);
        border: 1px solid rgba(99,102,241,0.2);
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 24px;
        font-size: 13px;
        color: var(--text-secondary);
    }
    .info-box strong { color: var(--text-primary); }
    .form-wrapper { display: flex; flex-direction: column; align-items: center; width: 100%; }
    .foto-preview {
        margin-top: 10px;
        display: none;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--card-border);
    }
    .foto-preview img { width: 100%; max-height: 200px; object-fit: cover; display: block; }
</style>

<div class="form-wrapper">
    <a href="{{ route('user.peminjaman.index') }}" class="form-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="form-panel">
        <div class="form-title">Form Pengembalian Alat</div>
        <div class="form-subtitle">Isi data pengembalian dengan lengkap dan benar</div>

        {{-- Info Peminjaman --}}
        <div class="info-box">
            🔧 <strong>{{ $peminjaman->alat->nama_alat }}</strong> &nbsp;|&nbsp;
            Jumlah: <strong>{{ $peminjaman->jumlah }}</strong> &nbsp;|&nbsp;
            Batas Kembali: <strong>{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</strong>
            @if($peminjaman->tanggal_kembali && \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->isPast())
                &nbsp;<span style="color:#f87171;">⚠️ Sudah melewati batas!</span>
            @endif
        </div>

        @if($errors->any())
        <div style="background:rgba(248,113,113,0.1); border:1px solid rgba(248,113,113,0.25); border-radius:10px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#f87171;">
            @foreach($errors->all() as $err)
            <div style="display:flex; align-items:center; gap:6px; margin-bottom:3px;">
                <i class="bi bi-x-circle-fill" style="font-size:11px;"></i> {{ $err }}
            </div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('user.peminjaman.kembalikan', $peminjaman->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- TANGGAL DIKEMBALIKAN --}}
            <div class="field">
                <label class="field-label">Tanggal Pengembalian <span style="color:#f87171;">*</span></label>
                <input type="date" name="tanggal_dikembalikan"
                    class="field-input {{ $errors->has('tanggal_dikembalikan') ? 'is-error' : '' }}"
                    value="{{ old('tanggal_dikembalikan', date('Y-m-d')) }}"
                    max="{{ date('Y-m-d') }}">
                @error('tanggal_dikembalikan') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            {{-- KONDISI ALAT --}}
            <div class="field">
                <label class="field-label">Kondisi Alat Saat Dikembalikan <span style="color:#f87171;">*</span></label>
                <select name="kondisi_kembali" class="field-input {{ $errors->has('kondisi_kembali') ? 'is-error' : '' }}">
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="baik" {{ old('kondisi_kembali') == 'baik' ? 'selected' : '' }}>✅ Baik</option>
                    <option value="rusak_ringan" {{ old('kondisi_kembali') == 'rusak_ringan' ? 'selected' : '' }}>⚠️ Rusak Ringan</option>
                    <option value="rusak_berat" {{ old('kondisi_kembali') == 'rusak_berat' ? 'selected' : '' }}>❌ Rusak Berat</option>
                </select>
                @error('kondisi_kembali') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-divider"></div>

            {{-- KETERANGAN --}}
            <div class="field">
                <label class="field-label">Keterangan / Alasan Keterlambatan</label>
                <textarea name="keterangan_kembali"
                    class="field-input {{ $errors->has('keterangan_kembali') ? 'is-error' : '' }}"
                    rows="3"
                    placeholder="Contoh: Terlambat karena ujian, kondisi alat masih baik...">{{ old('keterangan_kembali') }}</textarea>
                <div class="field-hint">Opsional — jelaskan kondisi atau alasan jika terlambat.</div>
                @error('keterangan_kembali') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            {{-- FOTO BUKTI --}}
            <div class="field">
                <label class="field-label">Foto Bukti Alat <span style="color:#f87171;">*</span></label>
                <input type="file" name="foto_bukti" accept="image/*"
                    class="field-input {{ $errors->has('foto_bukti') ? 'is-error' : '' }}"
                    onchange="previewFoto(this)" style="padding: 8px 14px; cursor:pointer;">
                <div class="field-hint">Upload foto kondisi alat sebelum dikembalikan. Maks 2MB.</div>
                @error('foto_bukti') <div class="field-error">{{ $message }}</div> @enderror
                <div class="foto-preview" id="fotoPreview">
                    <img id="fotoImg" src="" alt="Preview">
                </div>
            </div>

            {{-- SUBMIT --}}
            <button type="submit" class="btn-submit">
                <i class="bi bi-arrow-return-left"></i> Ajukan Pengembalian
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewFoto(input) {
    const preview = document.getElementById('fotoPreview');
    const img = document.getElementById('fotoImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection