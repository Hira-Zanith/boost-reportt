<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // បង្ហាញបញ្ជីបុគ្គលិក និង Form បង្កើត
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // រក្សាទុកគណនីបុគ្គលិកថ្មី
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'បង្កើតគណនីបុគ្គលិកជោគជ័យ! 🎉');
    }

    // លុបគណនីបុគ្គលិក
    public function destroy(User $user) {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'អ្នកមិនអាចលុបគណនីខ្លួនឯងបានទេ!');
        }
        $user->delete();
        return back()->with('success', 'លុបគណនីជោគជ័យ!');
    }

    // កំណត់ពាក្យសម្ងាត់ឡើងវិញ
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:8',
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', "ប្តូរលេខសម្ងាត់ឱ្យបុគ្គលិក {$user->name} ជោគជ័យ! 🎉");
    }
}