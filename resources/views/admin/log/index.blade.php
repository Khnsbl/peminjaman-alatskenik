@extends('layouts.admin')
@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')
<div class="dark-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold" style="color: var(--text-primary)">Riwayat Aktivitas</h6>
    </div>

    <table class="table dark-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>User</th>
                <th>Aktivitas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="color: var(--text-muted); font-size: 0.78rem;">
                    {{ $log->created_at->format('d M Y, H:i') }}
                </td>
                <td style="color: var(--text-primary)">{{ $log->user->name ?? '-' }}</td>
                <td>{{ $log->aktivitas }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4" style="color: var(--text-muted)">
                    Belum ada log aktivitas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection