<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'full_name' => ['sometimes', 'required', 'string', 'max:255'],
            'username' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if (array_key_exists('full_name', $validated)) {
            $user->full_name = $validated['full_name'];
            $user->name = $validated['full_name'];
        }

        if (array_key_exists('username', $validated)) {
            $user->username = $validated['username'];
        }

        if (array_key_exists('email', $validated)) {
            $user->email = strtolower($validated['email']);
        }

        if ($request->hasFile('profile_picture')) {
            $user->profile_picture = $request->file('profile_picture')
                ->store('profile_pictures', 'public');
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}