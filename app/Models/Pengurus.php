<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    use HasFactory;
    protected $table = 'pengurus';
    protected $guarded = ['id'];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    // Tambahkan relasi ini
    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
}