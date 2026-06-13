<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillingUnit;

class CekSlotController extends Controller
{
    public function index()
    {
        $units = BillingUnit::with([
            'jenisUnit',
            'activeBilling',
            'activeBilling.extends',
        ])->orderBy('nama_unit')->get();

        $lantai1 = $units->where('lantai', 1);
        $lantai2 = $units->where('lantai', 2);

        return view('pages.cek-slot', compact('lantai1', 'lantai2'));
    }
}