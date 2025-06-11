<?php

namespace App\Http\Controllers;

use App\Models\Historia; // Asegúrate de importar tu modelo Historia
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
        $historias = Historia::latest()->get();
        return view('index', ['historias' => $historias]);
    }
}