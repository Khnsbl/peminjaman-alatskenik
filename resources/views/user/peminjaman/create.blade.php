@extends('layouts.user')
@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman')

@section('content')
<style>
    .form-panel {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 28px 32px 36px;
        max-width: 700px;
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

    .form-back:hover {
        opacity: 0.75;
        text-decoration: none;
        color: var(--accent);
    }

    .form-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .form-subtitle {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 28px;
    }

    .field {
        margin-bottom: 20px;
    }

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
        background: rgba(255, 255, 255, 0.04);
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

    .field-input:focus {
        border-color: var(--accent);
        background: rgba(99, 102, 241, 0.06);
    }

    .field-input.is-error {
        border-color: #f87171;
    }

    .field-input option {
        background: #1a1d27;
        color: #f0f2f8;
    }

    .field-input::placeholder {
        color: var(--text-muted);
    }

    .field-error {
        font-size: 11px;
        color: #f87171;
        margin-top: 5px;
    }

    .field-hint {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 5px;
    }

    /* Stok Widget */
    .stok-widget {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 14px 16px;
        margin-top: 10px;
        display: none;
    }

    .stok-widget.show {
        display: block;
    }

    .stok-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .stok-nama {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .stok-count-wrap {
        display: flex;
        align-items: baseline;
        gap: 4px;
    }

    .stok-count {
        font-size: 22px;
        font-weight: 700;
        line-height: 1;
        color: var(--text-primary);
    }

    .stok-count-label {
        font-size: 12px;
        color: var(--text-muted);
    }

    .stok-bar {
        height: 6px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 6px;
    }

    .stok-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease, background 0.3s;
    }

    .stok-info {
        font-size: 11px;
        color: var(--text-muted);
    }

    .stok-habis {
        display: none;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: rgba(248, 113, 113, 0.1);
        border: 1px solid rgba(248, 113, 113, 0.25);
        border-radius: 10px;
        font-size: 13px;
        color: #f87171;
        margin-top: 10px;
    }

    .stok-habis.show {
        display: flex;
    }

    /* Denda info */
    .denda-info {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 11px 14px;
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 10px;
        font-size: 12px;
        color: #fbbf24;
        margin-top: 8px;
        line-height: 1.5;
    }

    /* Row grid */
    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 700px) {
        .form-panel {
            padding: 24px 20px 28px;
        }
    }

    @media (max-width: 540px) {
        .field-row {
            grid-template-columns: 1fr;
        }
    }

    .form-divider {
        height: 1px;
        background: var(--card-border);
        margin: 24px 0;
    }

    /* Submit button */
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

    .btn-submit:hover:not(:disabled) {
        background: #4f46e5;
        transform: translateY(-1px);
    }

    .btn-submit:disabled {
        background: rgba(255, 255, 255, 0.06);
        color: var(--text-muted);
        cursor: not-allowed;
        transform: none;
    }

    /* Jumlah warning */
    .jumlah-warning {
        font-size: 11px;
        color: #f87171;
        margin-top: 5px;
        display: none;
    }

    .jumlah-warning.show {
        display: block;
    }

    /* Summary box */
    .summary-box {
        background: rgba(99, 102, 241, 0.06);
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 10px;
        padding: 14px 16px;
        display: none;
        margin-top: 4px;
    }

    .summary-box.show {
        display: block;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-row span:last-child {
        color: var(--text-primary);
        font-weight: 600;
    }

    .summary-durasi {
        font-size: 13px;
        color: #818cf8;
        font-weight: 700;
    }

    /* Form wrapper untuk center content */
    .form-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-width: 100%;
    }
</style>

<div class="form-wrapper">
    <a href="{{ url()->previous() }}" class="form-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="form-panel">
    <div class="form-title">Form Ajukan Peminjaman</div>
    <div class="form-subtitle">Isi data di bawah dengan lengkap dan benar</div>

    {{-- Error List --}}
    @if($errors->any())
    <div style="background:rgba(248,113,113,0.1); border:1px solid rgba(248,113,113,0.25); border-radius:10px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#f87171;">
        @foreach($errors->all() as $err)
        <div style="display:flex; align-items:center; gap:6px; margin-bottom:3px;">
            <i class="bi bi-x-circle-fill" style="font-size:11px;"></i> {{ $err }}
        </div>
        @endforeach
    </div>
    @endif

    <form action="{{ route('user.peminjaman.store') }}" method="POST" id="formPeminjaman">
        @csrf

        {{-- PILIH ALAT --}}
        <div class="field">
            <label class="field-label">Pilih Alat <span style="color:#f87171;">*</span></label>
            <select name="alat_id" id="selectAlat"
                class="field-input {{ $errors->has('alat_id') ? 'is-error' : '' }}"
                onchange="onAlatChange(this.value)">
                <option value="">-- Pilih Alat --</option>
                @foreach($alatList as $a)
                <option value="{{ $a->id }}"
                    data-stok="{{ $a->stok }}"
                    data-stok-awal="{{ $a->stok ?? 0 }}"
                    data-nama="{{ $a->nama_alat }}"
                    {{ old('alat_id', $alat?->id ?? '') == $a->id ? 'selected' : '' }}>
                    {{ $a->nama_alat }} (Stok: {{ $a->stok }})
                </option>
                @endforeach
            </select>
            @error('alat_id') <div class="field-error">{{ $message }}</div> @enderror

            {{-- Stok Widget --}}
            <div class="stok-widget" id="stokWidget">
                <div class="stok-top">
                    <span class="stok-nama" id="stokNama">-</span>
                    <div class="stok-count-wrap">
                        <span class="stok-count" id="stokCount">0</span>
                        <span class="stok-count-label">unit tersedia</span>
                    </div>
                </div>
                <div class="stok-bar">
                    <div class="stok-fill" id="stokFill" style="width:0%;"></div>
                </div>
                <div class="stok-info" id="stokInfo">-</div>
            </div>

            {{-- Stok Habis --}}
            <div class="stok-habis" id="stokHabis">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>Stok alat ini sedang habis, tidak bisa dipinjam saat ini.</span>
            </div>
        </div>

        {{-- JUMLAH --}}
        <div class="field">
            <label class="field-label">Jumlah <span style="color:#f87171;">*</span></label>
            <input type="number" name="jumlah" id="inputJumlah"
                class="field-input {{ $errors->has('jumlah') ? 'is-error' : '' }}"
                value="{{ old('jumlah', 1) }}"
                min="1" max="99"
                placeholder="Masukkan jumlah"
                oninput="onJumlahChange(this.value)">
            <div class="jumlah-warning" id="jumlahWarning">Jumlah melebihi stok yang tersedia!</div>
            @error('jumlah') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-divider"></div>

        {{-- TANGGAL --}}
        <div class="field-row">
            <div class="field">
                <label class="field-label">Tanggal Pinjam <span style="color:#f87171;">*</span></label>
                <input type="date" name="tanggal_pinjam" id="tglPinjam"
                    class="field-input {{ $errors->has('tanggal_pinjam') ? 'is-error' : '' }}"
                    value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                    min="{{ date('Y-m-d') }}"
                    onchange="hitungDurasi()">
                @error('tanggal_pinjam') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label class="field-label">Tanggal Kembali <span style="color:#f87171;">*</span></label>
                {{-- PERBAIKAN: name diganti dari tanggal_kembali → tanggal_rencana_kembali --}}
                <input type="date" name="tanggal_rencana_kembali" id="tglKembali"
                    class="field-input {{ $errors->has('tanggal_rencana_kembali') ? 'is-error' : '' }}"
                    value="{{ old('tanggal_rencana_kembali') }}"
                    min="{{ date('Y-m-d') }}"
                    onchange="hitungDurasi()">
                @error('tanggal_rencana_kembali') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Ringkasan durasi --}}
        <div class="summary-box" id="summaryBox">
            <div class="summary-row">
                <span>Durasi Peminjaman</span>
                <span class="summary-durasi" id="summaryDurasi">-</span>
            </div>
            <div class="summary-row">
                <span>Estimasi Denda (jika terlambat)</span>
                <span>Rp 2.000 / hari</span>
            </div>
        </div>

        {{-- Info denda --}}
        <div class="denda-info" style="margin-top: 10px;">
            <i class="bi bi-info-circle-fill" style="margin-top:1px; flex-shrink:0;"></i>
            <span>Keterlambatan pengembalian akan dikenakan denda. Pastikan mengembalikan alat tepat waktu sesuai tanggal yang dipilih.</span>
        </div>

        <div class="form-divider"></div>

        {{-- KEPERLUAN --}}
        <div class="field" style="margin-bottom: 24px;">
            <label class="field-label">Keperluan / Keterangan</label>
            <textarea name="keperluan" id="keperluan"
                class="field-input {{ $errors->has('keperluan') ? 'is-error' : '' }}"
                rows="3"
                placeholder="Contoh: Untuk praktikum lab fisika semester 2...">{{ old('keperluan') }}</textarea>
            <div class="field-hint">Opsional — jelaskan untuk apa alat ini dipinjam.</div>
            @error('keperluan') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        {{-- SUBMIT --}}
        <button type="submit" class="btn-submit" id="btnSubmit">
            <i class="bi bi-send-fill"></i> Ajukan Peminjaman
        </button>

    </form>
</div>
</div>

@push('scripts')
<script>
    let stokMax = 0;

    function onAlatChange(val) {
        const sel = document.getElementById('selectAlat');
        const opt = sel.options[sel.selectedIndex];
        const widget = document.getElementById('stokWidget');
        const habis = document.getElementById('stokHabis');
        const btnSubmit = document.getElementById('btnSubmit');

        if (!val) {
            widget.classList.remove('show');
            habis.classList.remove('show');
            stokMax = 0;
            return;
        }

        const stok = parseInt(opt.dataset.stok) || 0;
        const stokAwal = parseInt(opt.dataset.stokAwal) || stok || 1;
        const nama = opt.dataset.nama || '';
        stokMax = stok;

        if (stok <= 0) {
            widget.classList.remove('show');
            habis.classList.add('show');
            btnSubmit.disabled = true;
            return;
        }

        habis.classList.remove('show');
        btnSubmit.disabled = false;
        widget.classList.add('show');

        document.getElementById('stokNama').textContent = nama;
        document.getElementById('stokCount').textContent = stok;

        const pct = Math.min((stok / stokAwal) * 100, 100);
        const color = pct > 50 ? '#34d399' : pct > 20 ? '#fbbf24' : '#f87171';
        const fill = document.getElementById('stokFill');
        fill.style.width = pct + '%';
        fill.style.background = color;

        document.getElementById('stokInfo').textContent =
            stok + ' dari ' + stokAwal + ' unit tersedia';

        onJumlahChange(document.getElementById('inputJumlah').value);
    }

    function onJumlahChange(val) {
        const jumlah = parseInt(val) || 0;
        const warning = document.getElementById('jumlahWarning');
        const btn = document.getElementById('btnSubmit');

        if (stokMax > 0 && jumlah > stokMax) {
            warning.classList.add('show');
            btn.disabled = true;
        } else {
            warning.classList.remove('show');
            if (stokMax > 0) btn.disabled = false;
        }
    }

    function hitungDurasi() {
        const tglP = document.getElementById('tglPinjam').value;
        const tglK = document.getElementById('tglKembali').value;
        const box = document.getElementById('summaryBox');

        if (!tglP || !tglK) {
            box.classList.remove('show');
            return;
        }

        const d1 = new Date(tglP);
        const d2 = new Date(tglK);
        const diff = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));

        if (diff <= 0) {
            box.classList.remove('show');
            document.getElementById('tglKembali').classList.add('is-error');
            return;
        }

        document.getElementById('tglKembali').classList.remove('is-error');
        box.classList.add('show');
        document.getElementById('summaryDurasi').textContent = diff + ' hari';
    }

    // Init jika ada old value (validasi gagal)
    document.addEventListener('DOMContentLoaded', function() {
        const sel = document.getElementById('selectAlat');
        if (sel && sel.value) onAlatChange(sel.value);
        hitungDurasi();
    });
</script>
@endpush

@endsection