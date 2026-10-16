<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_user' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'bidang_keahlian' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ], [
            'nama_user.required' => 'Nama lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan, silakan gunakan email lain',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'bidang_keahlian.required' => 'Bidang keahlian wajib diisi',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'rekanan',
            'bidang_keahlian' => $request->bidang_keahlian,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        if ($user) {
            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        }

        return redirect()->back()
            ->with('error', 'Registrasi gagal. Silakan coba lagi.')
            ->withInput();
    }
}