<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|max:100',
            'username' => 'required|unique:users,username|max:50',
            'password' => 'required|min:6',
            'level' => 'required|in:admin,perangkat_desa,kepala_desa',
        ]);

        User::create([
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_user' => 'required|max:100',
            'username' => 'required|unique:users,username,'.$id.',id_user|max:50',
            'password' => 'nullable|min:6',
            'level' => 'required|in:admin,perangkat_desa,kepala_desa',
        ]);

        $user = User::findOrFail($id);
        $user->nama_user = $request->nama_user;
        $user->username = $request->username;
        $user->level = $request->level;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diupdate!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
