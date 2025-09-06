<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Pengurus;
use Illuminate\Http\Request;
class PengurusController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('bidang_id')) {
            return Pengurus::where('bidang_id', $request->bidang_id)->get();
        }
        return Pengurus::with('bidang')->get();
    }
}