<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $table = 'laporans';
    protected $guarded = ['id'];

    protected $casts = [
        'details' => 'array',
        'tanggal' => 'date',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class);
    }
    
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}