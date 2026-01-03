<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class frontController extends Controller
{
    public function index(){

        $kelas = Kelas::get();
        return view('welcome',[
            'kelas'=>$kelas,
        ]);
    }
}
