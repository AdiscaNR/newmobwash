<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function index() {
        return view('pages.auth.login');
    }

    public function login_action(Request $request) {
        $validator = Validator::make($request->all(), [
        'username' => 'required|string',
        'password' => 'required|string|max:30',
        ], [
        'username.required' => 'Username tidak boleh kosong',
        'password.required' => 'Password tidak boleh kosong',
        'password.max' => 'Password maksimal 30 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
        // Jika pengguna tidak ditemukan
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $redirect = '/';
            $data = [
                'redirect' => $redirect
            ];
            return Redirect::to('/');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function logout() {
        FacadesSession::flush();
        auth()->logout();
        return redirect('/login');
    }
}
