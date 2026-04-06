@extends('layouts.admin')
@section('title', 'Edit Peminjaman')
@section('page-title', 'Edit Peminjaman')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-lg">
    <a href="{{ route('admin.peminjaman.index') }}"
       class="text-sm text-blue-600 hover:underline mb-4 inline-block">← Kembali</a>

    <form action="{{ route('admin.peminjaman.update', $peminjaman) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Peminjam</label>
            <input type="text" value="{{ $peminjaman->user->name }}" disabled
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Alat</label>
            <input type="text" value="{{ $peminjaman->alat->nama_alat }}" disabled
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="menunggu" {{ $peminjaman->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="ditolak" {{ $peminjaman->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
            <textarea name="keterangan" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">
            Update
        </button>
    </form>
</div>
@endsection