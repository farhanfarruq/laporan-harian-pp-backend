<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengurus;

class PengurusController extends Controller
{
    public function index()
    {
        $pengurus = Pengurus::all()->groupBy('bidang_id');
        return response()->json($pengurus);
    }
}