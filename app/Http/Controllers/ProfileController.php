<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil
     */
    public function index()
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    /**
     * Update profil user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nama_user' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id_user, 'id_user'),
            ],

            'no_telp' => [
                'nullable',
                'string',
                'max:20',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Validasi khusus REKANAN
        |--------------------------------------------------------------------------
        */

        if ($user->isRekanan()) {
            $rules['bidang_keahlian'] = [
                'nullable',
                'string',
                'max:100',
            ];

            $rules['alamat'] = [
                'nullable',
                'string',
                'max:500',
            ];
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'nama_user.required' =>
                    'Nama lengkap wajib diisi.',

                'email.required' =>
                    'Email wajib diisi.',

                'email.email' =>
                    'Format email tidak valid.',

                'email.unique' =>
                    'Email tersebut sudah digunakan oleh akun lain.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Data utama
        |--------------------------------------------------------------------------
        */

        $data = [
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
        ];

        /*
        |--------------------------------------------------------------------------
        | Data khusus REKANAN
        |--------------------------------------------------------------------------
        */

        if ($user->isRekanan()) {
            $data['bidang_keahlian'] = $request->bidang_keahlian;
            $data['alamat'] = $request->alamat;
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan perubahan
        |--------------------------------------------------------------------------
        */

        $user->update($data);

        /*
        |--------------------------------------------------------------------------
        | Refresh data user
        |--------------------------------------------------------------------------
        */

        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'user' => $user,
        ]);
    }

    /**
     * Mengubah password user
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => [
                    'required',
                    'string',
                ],

                'new_password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ],
            [
                'current_password.required' =>
                    'Password saat ini wajib diisi.',

                'new_password.required' =>
                    'Password baru wajib diisi.',

                'new_password.min' =>
                    'Password baru minimal 8 karakter.',

                'new_password.confirmed' =>
                    'Konfirmasi password baru tidak cocok.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Cek password lama
        |--------------------------------------------------------------------------
        */

        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'current_password' => [
                        'Password saat ini salah!'
                    ],
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan password baru
        |--------------------------------------------------------------------------
        */

        $user->update([
            'password' => Hash::make(
                $request->new_password
            ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah!',
        ]);
    }
}