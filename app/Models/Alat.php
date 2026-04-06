<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $fillable = ['kode_alat', 'nama_alat', 'kategori_id', 'stok', 'kondisi', 'foto'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}