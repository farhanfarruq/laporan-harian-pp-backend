<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jobdesk;
use Illuminate\Http\Request;

class JobdeskController extends Controller
{
    /**
     * Menampilkan daftar semua jobdesk yang dikelompokkan
     * untuk keperluan form.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Mengambil semua jobdesk beserta relasi bidangnya
        $jobdesks = Jobdesk::with('bidang:id,slug')->get();

        $formattedJobdesks = [];

        // Mengelompokkan jobdesk berdasarkan slug bidang dan tipe jobdesk
        foreach ($jobdesks as $jobdesk) {
            // Membuat kunci unik, contoh: 'keamanan_harian' atau 'bapakamar'
            $key = $jobdesk->bidang->slug;
            if ($jobdesk->type) {
                $key .= '_' . $jobdesk->type;
            }

            // Memasukkan deskripsi jobdesk ke dalam array dengan kunci yang sesuai
            $formattedJobdesks[$key][] = $jobdesk->description;
        }

        return response()->json($formattedJobdesks);
    }
}
