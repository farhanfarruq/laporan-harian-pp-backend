<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Bidang;

class BidangController extends Controller
{
    public function index()
    {
        // Mengambil semua data bidang
        return response()->json(Bidang::all());
    }
}