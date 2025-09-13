<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function jobdesks()
    {
        return $this->hasMany(Jobdesk::class);
    }

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class);
    }
}