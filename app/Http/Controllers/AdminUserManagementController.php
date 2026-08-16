<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\SpecialSchedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUserManagementController extends Controller
{
    private const STAFF_ROLES = ['professor', 'faculty'];

    public function index(Request $request): View
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(self::STAFF_ROLES)],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
        ]);

        $users = User::query()
            ->whereIn('role', self::STAFF_ROLES)
            ->with('department')
            ->with('activeAttendanceRecord')
            ->withCount(['attendanceRecords', 'schedules'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('full_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($filters['role'] ?? null, fn ($query, $role) => $query->where('role', $role))
            ->when($filters['department_id'] ?? null, fn ($query, $departmentId) => $query->where('department_id', $departmentId))
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'departments', 'filters'));
    }

    public function edit(User $user): View
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $this->ensureStaffUser($user);
        $user->load('department');

        $departments = Department::orderBy('name')->get();
        $attendanceHistory = $user->attendanceRecords()->latest('time_in')->limit(30)->get();
        $schedules = $user->schedules()
            ->orderByRaw("CASE day_of_week WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3 WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6 WHEN 'Sunday' THEN 7 ELSE 8 END")
            ->orderBy('start_time')
            ->get();

        return view('admin.users.edit', compact('user', 'departments', 'attendanceHistory', 'schedules'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureStaffUser($user);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user)],
            'role' => ['required', Rule::in(self::STAFF_ROLES)],
            'department_id' => ['required', 'exists:departments,id'],
        ]);


        $user->update([
            'name' => $validated['full_name'],
            'full_name' => $validated['full_name'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'department_id' => $validated['department_id'],
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'User account details updated successfully.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $this->ensureStaffUser($user);

        $validated = $request->validateWithBag('passwordReset', [
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);
        $this->invalidateDatabaseSessions($user);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Password reset successfully. Existing sessions were signed out.');
    }

    public function toggleSuspension(Request $request, User $user): RedirectResponse
    {
        $this->ensureStaffUser($user);

        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $suspend = ! $user->is_suspended;

        $user->update([
            'is_suspended' => $suspend,
            'suspended_at' => $suspend ? now() : null,
            'suspended_by' => $suspend ? $request->user()->id : null,
        ]);

        if ($suspend) {
            $this->closeOpenAttendance($user);
            $this->invalidateDatabaseSessions($user);
        }

        return back()->with('success', $suspend
            ? 'User account suspended successfully.'
            : 'User account reactivated successfully.');
    }

    public function forceTimeOut(User $user): RedirectResponse
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $this->ensureStaffUser($user);

        $attendance = $this->closeOpenAttendance($user);

        return back()->with($attendance ? 'success' : 'error', $attendance
            ? "{$user->full_name} was timed out successfully."
            : 'This user is not currently timed in.');
    }

    private function closeOpenAttendance(User $user): bool
    {
        $updated = $user->attendanceRecords()
            ->whereNull('time_out')
            ->update(['time_out' => now()]);

        if ($updated > 0) {
            $user->specialSchedules()
                ->whereIn('type', ['On Break', 'Not Available'])
                ->where('start_datetime', '<=', now())
                ->where('end_datetime', '>=', now())
                ->update(['end_datetime' => now()->subSecond()]);
        }

        return $updated > 0;
    }

    private function invalidateDatabaseSessions(User $user): void
    {
        if (config('session.driver') === 'database' && Schema::hasTable(config('session.table', 'sessions'))) {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->delete();
        }
    }

    private function ensureStaffUser(User $user): void
    {
        abort_unless(in_array($user->role, self::STAFF_ROLES, true), 404);
    }
}
