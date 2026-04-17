<?php

namespace App\Http\Controllers;

use App\Models\pulsa;
use Illuminate\Http\Request;

class PulsaController extends Controller
{
    public function index() {
        $pulsas = pulsa::all();
        return response()->json($pulsas);
    }
}
