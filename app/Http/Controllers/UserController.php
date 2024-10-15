<?php

namespace App\Http\Controllers;

use App\Models\User; // Ensure you import the User model
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search_user');
        $users = User::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create() {
        return view('users.create');
    }

    public function store(Request $request) {
        // Logic to store the new user
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:admin,user', // Validasi untuk role
        ]);

        // Create the user...
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role, // Tambahkan role di sini
        ]);
        
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string|in:admin,user', // Validasi untuk role
        ]);

        // Find the user by ID and update the information
        $user = User::findOrFail($id);
        $user->update($validatedData);

        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }

    public function destroy($id) {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('user.index')->with('success', 'User deleted successfully');
        }
        return redirect()->route('user.index')->with('failed', 'User not found');
    }
}
