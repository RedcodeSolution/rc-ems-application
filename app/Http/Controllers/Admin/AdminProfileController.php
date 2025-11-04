<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:Admin,HR Admin,Department Admin',
            'contact_no' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password|string|min:8',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);


        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->contact_no = $request->contact_no;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->route('admin.profile')
                    ->with('error', 'Current password is incorrect.');
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();


        Auth::login($user);

        if (strtolower($user->role) === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Profile updated successfully!');
        }

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }
}
