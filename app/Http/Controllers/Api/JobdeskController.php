<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Jobdesk;
use Illuminate\Http\Request;
class JobdeskController extends Controller
{
    public function index(Request $request)
    {
        $query = Jobdesk::query();
        if ($request->has('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        return $query->get();
    }
}