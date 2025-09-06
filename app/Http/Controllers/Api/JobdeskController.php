<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\Jobdesk;
use Illuminate\Http\Request;

class JobdeskController extends Controller
{
    public function index()
    {
        return Jobdesk::with('bidang')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'bidang_id' => 'required|exists:bidangs,id',
            'type' => 'nullable|in:malam,subuh',
        ]);

        $jobdesk = Jobdesk::create($validated);
        return response()->json($jobdesk->load('bidang'), 201);
    }

    public function show(Jobdesk $jobdesk)
    {
        return $jobdesk->load('bidang');
    }

    public function update(Request $request, Jobdesk $jobdesk)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'bidang_id' => 'required|exists:bidangs,id',
            'type' => 'nullable|in:malam,subuh',
        ]);

        // Pastikan bidang_id tidak diubah
        if ($jobdesk->bidang_id != $validated['bidang_id']) {
            return response()->json(['message' => 'Perubahan bidang tidak diizinkan.'], 422);
        }

        $jobdesk->update($validated);
        return response()->json($jobdesk->load('bidang'));
    }

    public function destroy(Jobdesk $jobdesk)
    {
        $jobdesk->delete();
        return response()->json(null, 204);
    }
}