<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Laporan::with(['user', 'pengurus', 'bidang'])->latest();

        // Filter berdasarkan role
        if ($user->role === 'admin_bidang') {
            $query->where('bidang_id', $user->bidang_id);
        }

        // Filter dari request
        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }
        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengurus_id' => 'required|exists:pengurus,id',
            'bidang_id' => 'required|exists:bidangs,id',
            'tanggal' => 'required|date',
            'details' => 'required|array',
        ]);

        $laporan = Laporan::create([
            'user_id' => Auth::id(),
            'pengurus_id' => $validated['pengurus_id'],
            'bidang_id' => $validated['bidang_id'],
            'tanggal' => $validated['tanggal'],
            'details' => $validated['details'],
        ]);

        return response()->json($laporan->load(['user', 'pengurus', 'bidang']), 201);
    }
    
    public function show(Laporan $laporan)
    {
        return $laporan->load(['user', 'pengurus', 'bidang']);
    }
}