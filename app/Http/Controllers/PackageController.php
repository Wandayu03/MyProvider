<?php

namespace App\Http\Controllers;

use App\Models\package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index() {
        $packages = package::all();
        return response()->json($packages);
    }
}
