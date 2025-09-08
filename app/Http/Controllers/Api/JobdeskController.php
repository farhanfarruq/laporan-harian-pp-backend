<?php

namespace App\Http\Controllers\Api; // <-- INI YANG DIPERBAIKI (sebelumnya ada ...\Master)

use App\Http\Controllers\Controller;
use App\Models\Jobdesk;
use Illuminate\Http\Request;

class JobdeskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Rute ini hanya untuk mengambil data, tidak perlu otorisasi ketat
        $jobdesk = Jobdesk::all();
        return response()->json(['data' => $jobdesk]);
    }
}