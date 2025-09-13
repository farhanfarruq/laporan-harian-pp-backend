<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Bidang;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    /**
     * Menampilkan daftar laporan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Memulai query dengan eager loading untuk relasi
        $query = Laporan::with(['user:id,name', 'pengurus:id,nama,kelas', 'bidang:id,name,slug']);

        // Filter berdasarkan peran: admin bidang hanya bisa melihat laporan bidangnya
        if ($user->role === 'admin_bidang') {
            $query->where('bidang_id', $user->bidang_id);
        }

        // Filter berdasarkan query parameter 'tanggal'
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter berdasarkan query parameter 'bidang' (slug)
        if ($request->filled('bidang') && $user->role !== 'admin_bidang') {
            $query->whereHas('bidang', function ($q) use ($request) {
                $q->where('slug', $request->bidang);
            });
        }

        // Mengambil data laporan yang diurutkan dari yang terbaru
        $laporan = $query->latest()->get();

        return response()->json($laporan);
    }

    /**
     * Menyimpan laporan baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'bidang' => 'required|string|exists:bidangs,slug',
            'tab' => 'nullable|string',
            'pengurus' => 'required|string',
            'tanggal' => 'required|date',
            'jobdesk' => 'required|array',
            'incomplete' => 'nullable|array',
            'submittedBy' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Cari Bidang dan Pengurus berdasarkan data dari request
        $bidang = Bidang::where('slug', $request->bidang)->firstOrFail();
        $pengurus = Pengurus::where('nama', $request->pengurus)->where('bidang_id', $bidang->id)->first();

        // Jika pengurus tidak ditemukan, kirim respons error
        if (!$pengurus) {
            return response()->json(['message' => 'Data pengurus tidak ditemukan untuk bidang yang dipilih.'], 404);
        }

        // Buat laporan baru
        $laporan = Laporan::create([
            'user_id' => Auth::id(),
            'pengurus_id' => $pengurus->id,
            'bidang_id' => $bidang->id,
            'tanggal' => $request->tanggal,
            'details' => [ // Data ini akan di-cast ke JSON secara otomatis oleh Eloquent
                'tab' => $request->tab,
                'jobdesk_status' => $request->jobdesk,
                'incomplete_details' => $request->incomplete,
                'submitted_by' => $request->submittedBy,
            ],
        ]);

        // Kembalikan laporan yang baru dibuat beserta relasinya
        return response()->json($laporan->load(['user', 'pengurus', 'bidang']), 201);
    }

    /**
     * Menampilkan satu data laporan spesifik.
     *
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Laporan $laporan)
    {
        // Menggunakan Route Model Binding untuk mengambil laporan
        // dan memuat relasinya
        return response()->json($laporan->load(['user', 'pengurus', 'bidang']));
    }

    /**
     * Memperbarui data laporan yang ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Laporan $laporan)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'bidang' => 'required|string|exists:bidangs,slug',
            'tab' => 'nullable|string',
            'pengurus' => 'required|string',
            'tanggal' => 'required|date',
            'jobdesk' => 'required|array',
            'incomplete' => 'nullable|array',
            'submittedBy' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $bidang = Bidang::where('slug', $request->bidang)->firstOrFail();
        $pengurus = Pengurus::where('nama', $request->pengurus)->where('bidang_id', $bidang->id)->first();

        if (!$pengurus) {
            return response()->json(['message' => 'Data pengurus tidak ditemukan.'], 404);
        }

        // Update data laporan
        $laporan->update([
            'pengurus_id' => $pengurus->id,
            'bidang_id' => $bidang->id,
            'tanggal' => $request->tanggal,
            'details' => [
                'tab' => $request->tab,
                'jobdesk_status' => $request->jobdesk,
                'incomplete_details' => $request->incomplete,
                'submitted_by' => $request->submittedBy,
            ],
        ]);

        return response()->json($laporan->load(['user', 'pengurus', 'bidang']));
    }

    /**
     * Menghapus data laporan.
     *
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Laporan $laporan)
    {
        // Hapus laporan
        $laporan->delete();

        // Kembalikan respons no content, menandakan sukses
        return response()->json(['message' => 'Laporan berhasil dihapus.'], 200);
    }
}
