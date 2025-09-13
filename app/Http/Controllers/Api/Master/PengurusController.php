<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengurusController extends Controller
{
    /**
     * Menampilkan daftar semua pengurus untuk manajemen data master.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Mengambil data dengan paginasi dan relasi bidang
        $pengurus = Pengurus::with('bidang:id,name')->latest()->paginate(10);
        return response()->json($pengurus);
    }

    /**
     * Menyimpan data pengurus baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jabatan' => 'required|string|max:100',
            'bidang_id' => 'required|exists:bidangs,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pengurus = Pengurus::create($validator->validated());

        return response()->json($pengurus->load('bidang:id,name'), 201);
    }

    /**
     * Menampilkan satu data pengurus spesifik.
     *
     * @param  \App\Models\Pengurus  $pengurus
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Pengurus $pengurus)
    {
        return response()->json($pengurus->load('bidang:id,name'));
    }

    /**
     * Memperbarui data pengurus.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengurus  $pengurus
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Pengurus $pengurus)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jabatan' => 'required|string|max:100',
            'bidang_id' => 'required|exists:bidangs,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pengurus->update($validator->validated());

        return response()->json($pengurus->load('bidang:id,name'));
    }

    /**
     * Menghapus data pengurus.
     *
     * @param  \App\Models\Pengurus  $pengurus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Pengurus $pengurus)
    {
        // Cek apakah pengurus masih memiliki laporan terkait
        if ($pengurus->laporans()->exists()) {
            return response()->json([
                'message' => 'Tidak dapat menghapus. Pengurus ini masih memiliki data laporan terkait.'
            ], 409); // 409 Conflict
        }

        $pengurus->delete();
        
        return response()->json(['message' => 'Pengurus berhasil dihapus.'], 200);
    }
}
