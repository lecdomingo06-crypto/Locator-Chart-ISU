<?php

use App\Http\Controllers\AcademicEventController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminStatusController;
use App\Http\Controllers\AdminStudentRegistrationController;
use App\Http\Controllers\AdminUserManagementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SpecialScheduleController;
use App\Http\Controllers\ViewerController;
use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    AttendanceRecord::autoTimeOutExpiredOpenSessions();

    $snapshotStaff = User::snapshotStaff();
    $snapshotTotals = User::snapshotTotals();
    $availableProfessorSnapshot = User::availableProfessorSnapshot();

    return view('welcome', compact('snapshotStaff', 'snapshotTotals', 'availableProfessorSnapshot'));
});

Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route(match (auth()->user()->role) {
        'admin' => 'admin.viewer',
        'professor' => 'staff.viewer',
        'faculty' => 'faculty.dashboard',
        default => 'student.viewer',
    });
})->name('dashboard');

Route::middleware('auth')->post('/chatbot/message', [ChatbotController::class, 'message'])
    ->name('chatbot.message');

// ================= DASHBOARDS =================

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::redirect('/admin', '/admin/viewer');
    Route::redirect('/admin/dashboard', '/admin/viewer')->name('admin.dashboard');
    Route::get('/admin/academic-events', [AcademicEventController::class, 'index'])->name('academic_events.index');
    Route::get('/admin/academic-events/create', [AcademicEventController::class, 'create'])->name('academic_events.create');
    Route::post('/admin/academic-events', [AcademicEventController::class, 'store'])->name('academic_events.store');
    Route::get('/admin/academic-events/{academicEvent}/edit', [AcademicEventController::class, 'edit'])->name('academic_events.edit');
    Route::put('/admin/academic-events/{academicEvent}', [AcademicEventController::class, 'update'])->name('academic_events.update');
    Route::delete('/admin/academic-events/{academicEvent}', [AcademicEventController::class, 'destroy'])->name('academic_events.destroy');
    Route::get('/admin/users', [AdminUserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [RegisteredUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [RegisteredUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminUserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [AdminUserManagementController::class, 'update'])->name('admin.users.update');
    Route::post('/admin/users/{user}/reset-password', [AdminUserManagementController::class, 'resetPassword'])->name('admin.users.reset_password');
    Route::post('/admin/users/{user}/toggle-suspension', [AdminUserManagementController::class, 'toggleSuspension'])->name('admin.users.toggle_suspension');
    Route::post('/admin/users/{user}/force-time-out', [AdminUserManagementController::class, 'forceTimeOut'])->name('admin.users.force_time_out');
    Route::get('/admin/attendance', [AdminAttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('/admin/attendance/print', [AdminAttendanceController::class, 'printable'])->name('admin.attendance.print');
    Route::post('/admin/attendance/{user}/force-time-out', [AdminAttendanceController::class, 'forceTimeOut'])->name('admin.attendance.force_time_out');
    Route::get('/admin/student-registrations', [AdminStudentRegistrationController::class, 'index'])->name('admin.student_registrations.index');
    Route::delete('/admin/student-registrations/reviewed', [AdminStudentRegistrationController::class, 'clearReviewed'])->name('admin.student_registrations.clear_reviewed');
    Route::get('/admin/student-registrations/{registration}', [AdminStudentRegistrationController::class, 'show'])->name('admin.student_registrations.show');
    Route::post('/admin/student-registrations/{registration}/approve', [AdminStudentRegistrationController::class, 'approve'])->name('admin.student_registrations.approve');
    Route::post('/admin/student-registrations/{registration}/decline', [AdminStudentRegistrationController::class, 'decline'])->name('admin.student_registrations.decline');
    Route::get('/admin/status', [AdminStatusController::class, 'index'])->name('admin.status');
    Route::post('/admin/status', [AdminStatusController::class, 'store'])->name('admin.status.store');
    Route::get('/admin/viewer', function (Request $request) {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $search = $request->search;
        $department = $request->department;

        $query = User::whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false);

        if ($search) {
            $query->where('full_name', 'like', "%{$search}%");
        }

        if ($department) {
            $query->where('department_id', $department);
        }

        $users = $query->get();
        $departments = Department::all();

        return view('admin.viewer', compact('users', 'departments', 'search', 'department'));
    })->name('admin.viewer');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [DashboardController::class, 'student'])->name('student.dashboard');
});

Route::middleware(['auth', 'role:professor'])->group(function () {
    Route::redirect('/professor/dashboard', '/professor/viewer')->name('professor.dashboard');
});

Route::middleware(['auth', 'role:faculty'])->group(function () {
    Route::get('/faculty/dashboard', [DashboardController::class, 'faculty'])->name('faculty.dashboard');
});

// ================= VIEWERS =================

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/viewer', [ViewerController::class, 'studentViewer'])->name('student.viewer');
});

Route::middleware(['auth', 'role:professor|faculty'])->group(function () {
    Route::get('/professor/viewer', [ViewerController::class, 'staffViewer'])->name('staff.viewer');
});

Route::middleware(['auth', 'role:professor|faculty'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/time-in', [AttendanceController::class, 'timeIn'])->name('attendance.time_in');
    Route::post('/attendance/time-out', [AttendanceController::class, 'timeOut'])->name('attendance.time_out');
    Route::post('/attendance/clear-sessions', [AttendanceController::class, 'clearSessions'])->name('attendance.clear_sessions');
    Route::get('/availability', [AvailabilityController::class, 'show'])->name('availability.show');
    Route::post('/availability/break', [AvailabilityController::class, 'startBreak'])->name('availability.break');
    Route::post('/availability/unavailable', [AvailabilityController::class, 'startUnavailable'])->name('availability.unavailable');
    Route::post('/availability/available', [AvailabilityController::class, 'available'])->name('availability.available');
});

Route::middleware(['auth', 'role:professor|faculty|student'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
// ================= AUTH =================

Route::middleware(['auth', 'role:professor|faculty'])->group(function () {
    Route::get('/professor/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/professor/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/professor/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/professor/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::post('/professor/schedules/{schedule}/update', [ScheduleController::class, 'update'])->name('schedules.update.post');
    Route::post('/professor/schedules/{schedule}/delete', [ScheduleController::class, 'destroy'])->name('schedules.destroy.post');
    Route::put('/professor/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/professor/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
});

Route::middleware(['auth', 'role:professor|faculty'])->group(function () {
    Route::get('/special-schedules', [SpecialScheduleController::class, 'index'])->name('special_schedules.index');
    Route::get('/special-schedules/create', [SpecialScheduleController::class, 'create'])->name('special_schedules.create');
    Route::post('/special-schedules', [SpecialScheduleController::class, 'store'])->name('special_schedules.store');
    Route::get('/special-schedules/{specialSchedule}/edit', [SpecialScheduleController::class, 'edit'])->name('special_schedules.edit');
    Route::put('/special-schedules/{specialSchedule}', [SpecialScheduleController::class, 'update'])->name('special_schedules.update');
    Route::delete('/special-schedules/{specialSchedule}', [SpecialScheduleController::class, 'destroy'])->name('special_schedules.destroy');
});

require __DIR__.'/auth.php';
