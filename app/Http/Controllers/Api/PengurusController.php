<?php

namespace App\Http\Controllers\Api; // <-- INI YANG DIPERBAIKI (sebelumnya ada ...\Master)

use App\Http\Controllers\Controller;
use App\Models\Pengurus;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Rute ini hanya untuk mengambil data, tidak perlu otorisasi ketat
        $pengurus = Pengurus::all();
        return response()->json(['data' => $pengurus]);
    }
}