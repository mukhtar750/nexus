<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Summit;
use Illuminate\Http\Request;

class SummitController extends Controller
{
    public function index()
    {
        $summits = Summit::where('is_active', true)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => $summits
        ]);
    }
}
