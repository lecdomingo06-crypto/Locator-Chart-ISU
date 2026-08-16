<?php

namespace App\Http\Controllers;

use App\Mail\StudentRegistrationApproved;
use App\Mail\StudentRegistrationDeclined;
use App\Models\PendingStudentRegistration;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class AdminStudentRegistrationController extends Controller
{
    public function index(): View
    {
        $pendingRegistrations = PendingStudentRegistration::with('department')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $reviewedRegistrations = PendingStudentRegistration::with(['department', 'reviewer'])
            ->whereIn('status', ['approved', 'declined'])
            ->latest('reviewed_at')
            ->paginate(10, ['*'], 'reviewed_page')
            ->withQueryString();

        return view('admin.student-registrations.index', compact('pendingRegistrations', 'reviewedRegistrations'));
    }

    public function show(PendingStudentRegistration $registration): View
    {
        $registration->load(['department', 'reviewer']);

        return view('admin.student-registrations.show', compact('registration'));
    }

    public function clearReviewed(): RedirectResponse
    {
        $deleted = PendingStudentRegistration::whereIn('status', ['approved', 'declined'])->delete();

        return redirect()
            ->route('admin.student_registrations.index')
            ->with(
                'success',
                $deleted === 0
                    ? 'There was no reviewed request history to clear.'
                    : "Cleared {$deleted} reviewed student request".($deleted === 1 ? '.' : 's.')
            );
    }

    public function approve(Request $request, PendingStudentRegistration $registration): RedirectResponse
    {
        if ($registration->status !== 'pending') {
            return redirect()
                ->route('admin.student_registrations.index')
                ->with('error', 'This registration has already been reviewed.');
        }

        validator([
            'student_id' => $registration->student_id,
            'email' => $registration->email,
        ], [
            'student_id' => [
                'required',
                Rule::unique(User::class, 'student_id'),
                Rule::unique(User::class, 'username'),
            ],
            'email' => ['required', Rule::unique(User::class, 'email')],
        ])->validate();

        $user = User::create([
            'name' => $registration->full_name,
            'full_name' => $registration->full_name,
            'username' => $registration->student_id,
            'student_id' => $registration->student_id,
            'email' => $registration->email,
            'role' => 'student',
            'department_id' => $registration->department_id,
            'password' => $registration->password,
        ]);

        $user->assignRole('student');

        $registration->fill([
            'status' => 'approved',
            'reviewed_at' => now(),
            'decline_reason' => null,
        ]);
        $registration->reviewer()->associate($request->user());
        $registration->save();

        try {
            Mail::to($registration->email)->send(new StudentRegistrationApproved($registration));
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('admin.student_registrations.index')
                ->with('error', "Student account approved for {$registration->full_name}, but the email failed to send. Check the mail settings.");
        }

        return redirect()
            ->route('admin.student_registrations.index')
            ->with('success', "Student account approved for {$registration->full_name}.");
    }

    public function decline(Request $request, PendingStudentRegistration $registration): RedirectResponse
    {
        if ($registration->status !== 'pending') {
            return redirect()
                ->route('admin.student_registrations.index')
                ->with('error', 'This registration has already been reviewed.');
        }

        $validated = $request->validate([
            'decline_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $registration->fill([
            'status' => 'declined',
            'reviewed_at' => now(),
            'decline_reason' => $validated['decline_reason'] ?? null,
        ]);
        $registration->reviewer()->associate($request->user());
        $registration->save();

        try {
            Mail::to($registration->email)->send(new StudentRegistrationDeclined($registration));
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('admin.student_registrations.index')
                ->with('error', "Student registration declined for {$registration->full_name}, but the email failed to send. Check the mail settings.");
        }

        return redirect()
            ->route('admin.student_registrations.index')
            ->with('success', "Student registration declined for {$registration->full_name}.");
    }
}
