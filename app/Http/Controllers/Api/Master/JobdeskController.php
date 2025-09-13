<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\Jobdesk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JobdeskController extends Controller
{
    /**
     * Menampilkan daftar semua jobdesk untuk manajemen data master.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Mengambil data dengan paginasi dan relasi bidang
        $jobdesks = Jobdesk::with('bidang:id,name')->latest()->paginate(10);
        return response()->json($jobdesks);
    }

    /**
     * Menyimpan jobdesk baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required|exists:bidangs,id',
            'description' => 'required|string|max:255',
            'type' => [
                'nullable',
                'string',
                 // Tipe harus 'harian' atau 'insidental' jika tidak null
                Rule::in(['harian', 'insidental']),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jobdesk = Jobdesk::create($validator->validated());

        return response()->json($jobdesk->load('bidang:id,name'), 201);
    }

    /**
     * Menampilkan satu data jobdesk spesifik.
     *
     * @param  \App\Models\Jobdesk  $jobdesk
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Jobdesk $jobdesk)
    {
        return response()->json($jobdesk->load('bidang:id,name'));
    }

    /**
     * Memperbarui data jobdesk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jobdesk  $jobdesk
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Jobdesk $jobdesk)
    {
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required|exists:bidangs,id',
            'description' => 'required|string|max:255',
            'type' => [
                'nullable',
                'string',
                Rule::in(['harian', 'insidental']),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jobdesk->update($validator->validated());

        return response()->json($jobdesk->load('bidang:id,name'));
    }

    /**
     * Menghapus data jobdesk.
     *
     * @param  \App\Models\Jobdesk  $jobdesk
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Jobdesk $jobdesk)
    {
        $jobdesk->delete();
        return response()->json(['message' => 'Jobdesk berhasil dihapus.'], 200);
    }
}
