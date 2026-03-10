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
            ->get()
            ->sortBy(function ($summit) {
                return strtotime($summit->date);
            })
            ->values();

        return response()->json([
            'data' => $summits
        ]);
    }
}
