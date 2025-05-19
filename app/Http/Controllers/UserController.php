<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function teste() {
        return response()->json([
            'message' => 'Hello World'
        ], 200);
    }

    public function envio (Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        return response()->json([
            'message' => 'Email e senha enviados com sucesso',
            'email' => $validated['email']
        ], 200);
    }
}
