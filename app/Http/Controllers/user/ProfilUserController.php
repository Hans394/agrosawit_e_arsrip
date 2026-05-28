<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfilUserController extends Controller
{
    /**
     * Tampilkan halaman profil user
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        return view('profil_user', compact('user'));
    }
}