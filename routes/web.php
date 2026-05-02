<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ViewerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SpecialScheduleController;
use App\Http\Controllers\AdminStatusController;
use App\Http\Controllers\AcademicEventController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\User;
use Illuminate\Http\Request;

Route::get('/', function () {
    $snapshotStaff = User::snapshotStaff();
    $snapshotTotals = User::snapshotTotals();
    $availableTeacherSnapshot = User::availableTeacherSnapshot();

    return view('welcome', compact('snapshotStaff', 'snapshotTotals', 'availableTeacherSnapshot'));
});

Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route(match (auth()->user()->role) {
        'admin' => 'admin.dashboard',
        'teacher' => 'teacher.dashboard',
        'faculty' => 'faculty.dashboard',
        default => 'student.dashboard',
    });
})->name('dashboard');


// ================= DASHBOARDS =================

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::redirect('/admin', '/admin/dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/admin/academic-events', [AcademicEventController::class, 'index'])->name('academic_events.index');
    Route::get('/admin/academic-events/create', [AcademicEventController::class, 'create'])->name('academic_events.create');
    Route::post('/admin/academic-events', [AcademicEventController::class, 'store'])->name('academic_events.store');
    Route::get('/admin/academic-events/{academicEvent}/edit', [AcademicEventController::class, 'edit'])->name('academic_events.edit');
    Route::put('/admin/academic-events/{academicEvent}', [AcademicEventController::class, 'update'])->name('academic_events.update');
    Route::delete('/admin/academic-events/{academicEvent}', [AcademicEventController::class, 'destroy'])->name('academic_events.destroy');
    Route::get('/admin/users/create', [RegisteredUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [RegisteredUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/status', [AdminStatusController::class, 'index'])->name('admin.status');
    Route::post('/admin/status', [AdminStatusController::class, 'store'])->name('admin.status.store');
    Route::get('/admin/viewer', function (Request $request) {
        $search = $request->search;
        $department = $request->department;

        $query = \App\Models\User::whereIn('role', ['teacher', 'faculty']);

        if ($search) {
            $query->where('full_name', 'like', "%{$search}%");
        }

        if ($department) {
            $query->where('department_id', $department);
        }

        $users = $query->get();
        $departments = \App\Models\Department::all();

        return view('admin.viewer', compact('users', 'departments', 'search', 'department'));
    })->name('admin.viewer');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [DashboardController::class, 'student'])->name('student.dashboard');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [DashboardController::class, 'teacher'])->name('teacher.dashboard');
});

Route::middleware(['auth', 'role:faculty'])->group(function () {
    Route::get('/faculty/dashboard', [DashboardController::class, 'faculty'])->name('faculty.dashboard');
});


// ================= VIEWERS =================

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/viewer', [ViewerController::class, 'studentViewer'])->name('student.viewer');
});

Route::middleware(['auth', 'role:teacher,faculty'])->group(function () {
    Route::get('/teacher/viewer', [ViewerController::class, 'staffViewer'])->name('staff.viewer');
});

Route::middleware(['auth', 'role:teacher,faculty'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// ================= AUTH =================

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/teacher/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/teacher/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/teacher/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/teacher/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/teacher/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
});

Route::middleware(['auth', 'role:teacher,faculty'])->group(function () {
    Route::get('/special-schedules', [SpecialScheduleController::class, 'index'])->name('special_schedules.index');
    Route::get('/special-schedules/create', [SpecialScheduleController::class, 'create'])->name('special_schedules.create');
    Route::post('/special-schedules', [SpecialScheduleController::class, 'store'])->name('special_schedules.store');
    Route::get('/special-schedules/{specialSchedule}/edit', [SpecialScheduleController::class, 'edit'])->name('special_schedules.edit');
    Route::put('/special-schedules/{specialSchedule}', [SpecialScheduleController::class, 'update'])->name('special_schedules.update');
    Route::delete('/special-schedules/{specialSchedule}', [SpecialScheduleController::class, 'destroy'])->name('special_schedules.destroy');
});

require __DIR__.'/auth.php';
