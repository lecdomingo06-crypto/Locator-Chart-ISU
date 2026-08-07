<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PendingStudentRegistration;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class StudentRegistrationController extends Controller
{
    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('auth.student-register', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'student_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class, 'student_id'),
                Rule::unique(User::class, 'username'),
                Rule::unique('pending_student_registrations', 'student_id')->where(fn ($query) => $query->where('status', 'pending')),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(User::class, 'email'),
                Rule::unique('pending_student_registrations', 'email')->where(fn ($query) => $query->where('status', 'pending')),
            ],
            'department_id' => ['required', 'exists:departments,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        PendingStudentRegistration::create([
            'student_id' => $request->student_id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'password' => Hash::make($request->password),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('student.register')
            ->with('status', 'Registration submitted. Please wait for admin approval by email.');
    }
}
