<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_alat',
        'nama_alat',
        'kategori_id',
        'stok',
        'kondisi',
        'foto',
    ];

    // ── Relasi ─────────────────────────────────────
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // ── Accessor: supaya $alat->nama tetap bisa dipakai ──
    public function getNamaAttribute(): string
    {
        return $this->nama_alat ?? '';
    }

    // ── Stok Helpers ───────────────────────────────

    public function stokTersedia(): bool
    {
        return $this->stok > 0;
    }

    public function kurangiStok(int $jumlah = 1): bool
    {
        if ($this->stok < $jumlah) {
            return false;
        }

        $this->decrement('stok', $jumlah);

        if ($this->fresh()->stok <= 0) {
            $this->update(['kondisi' => 'rusak']);
        }

        return true;
    }

    public function tambahStok(int $jumlah = 1): void
    {
        $this->increment('stok', $jumlah);

        if ($this->fresh()->stok > 0 && !in_array($this->kondisi, ['perbaikan', 'rusak'])) {
            $this->update(['kondisi' => 'baik']);
        }
    }

    public function persentaseStok(): int
    {
        if (!isset($this->stok_awal) || $this->stok_awal <= 0) return 0;
        return (int) round(($this->stok / $this->stok_awal) * 100);
    }

    public function warnaBadgeStok(): string
    {
        $pct = $this->persentaseStok();
        if ($pct <= 0)  return 'coral';
        if ($pct <= 25) return 'amber';
        if ($pct <= 50) return 'blue';
        return 'green';
    }
}