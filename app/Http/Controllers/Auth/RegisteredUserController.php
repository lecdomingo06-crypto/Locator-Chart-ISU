<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the admin account creation view.
     */
    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('auth.register', compact('departments'));
    }

    /**
     * Handle an incoming account creation request from an admin.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'in:professor,faculty'],
            'department_id' => [
                Rule::requiredIf(fn () => in_array($request->role, ['professor', 'faculty'], true)),
                'nullable',
                'exists:departments,id',
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->full_name,
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->username.'@local.test',
            'role' => $request->role,
            'department_id' => $request->department_id,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()
            ->route('admin.users.create')
            ->with('status', "Account created successfully for {$user->full_name}.");
    }
}
