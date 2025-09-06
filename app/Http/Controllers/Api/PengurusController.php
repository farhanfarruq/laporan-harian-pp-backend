<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengurusController extends Controller
{
    public function index()
    {
        return Pengurus::with('bidang')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'nullable|string|max:50',
            'bidang_id' => 'required|exists:bidangs,id',
        ]);

        $pengurus = Pengurus::create($validated);
        return response()->json($pengurus->load('bidang'), 201);
    }

    public function show(Pengurus $pengurus)
    {
        return $pengurus->load('bidang');
    }

    public function update(Request $request, Pengurus $pengurus)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'nullable|string|max:50',
            // Bidang tidak diizinkan untuk diubah untuk menjaga integritas data
            'bidang_id' => 'required|exists:bidangs,id',
        ]);
        
        // Pastikan bidang_id tidak diubah
        if ($pengurus->bidang_id != $validated['bidang_id']) {
            return response()->json(['message' => 'Perubahan bidang tidak diizinkan.'], 422);
        }

        $pengurus->update($validated);
        return response()->json($pengurus->load('bidang'));
    }

    public function destroy(Pengurus $pengurus)
    {
        // Pengecekan apakah pengurus memiliki relasi dengan laporan
        if ($pengurus->laporans()->exists()) {
            return response()->json(['message' => 'Tidak dapat menghapus, pengurus sudah memiliki data laporan terkait.'], 422);
        }
        
        $pengurus->delete();
        return response()->json(null, 204);
    }
}