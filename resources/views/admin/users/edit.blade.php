@extends('layouts.admin-users')
@section('title', 'Edit Staff Account')
@section('content')
@php
    $displayName = $user->full_name ?: $user->username;
    $openAttendance = $user->currentAttendance();
    $initials = collect(preg_split('/\s+/', $displayName))->filter()->take(2)->map(fn($part) => strtoupper(substr($part, 0, 1)))->implode('');
@endphp
<section class="page-head"><div class="page-title"><span>User Management</span><h1>Edit Staff Account</h1></div><a href="{{ route('admin.users.index') }}" class="secondary-button">Back to users</a></section>
<section class="detail-head"><div class="detail-user"><div class="user-avatar">@if($user->profile_picture)<img src="{{ asset('storage/'.$user->profile_picture) }}" alt="">@else{{ $initials }}@endif</div><div><h2>{{ $displayName }}</h2><p>{{ ucfirst($user->role) }} · {{ $user->department?->name ?? 'Unassigned' }}</p></div></div><div><span class="badge {{ $user->is_suspended ? 'suspended' : 'active' }}">{{ $user->is_suspended ? 'Suspended' : 'Active Account' }}</span> <span class="badge {{ $openAttendance ? 'timed-in' : 'timed-out' }}">{{ $openAttendance ? 'Timed In' : 'Timed Out' }}</span></div></section>
<div class="detail-grid">
<section class="detail-panel"><div class="panel-head"><div><span class="panel-kicker">Account Details</span><h2>Edit staff information</h2></div></div><div class="panel-body">
<form method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PATCH')
<div class="form-grid">
<div class="field"><label for="full_name">Full Name</label><input id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>@error('full_name')<p class="field-error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="username">Username</label><input id="username" name="username" value="{{ old('username', $user->username) }}" required>@error('username')<p class="field-error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="role">Role</label><select id="role" name="role" required><option value="professor" @selected(old('role',$user->role)==='professor')>Professor</option><option value="faculty" @selected(old('role',$user->role)==='faculty')>Faculty</option></select>@error('role')<p class="field-error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="department_id">Department</label><select id="department_id" name="department_id" required>@foreach($departments as $department)<option value="{{ $department->id }}" @selected((string)old('department_id',$user->department_id)===(string)$department->id)>{{ $department->name }}</option>@endforeach</select>@error('department_id')<p class="field-error">{{ $message }}</p>@enderror</div>
</div><div class="form-actions"><a href="{{ route('admin.users.index') }}" class="secondary-button">Cancel</a><button type="submit" class="primary-button">Save Changes</button></div>
</form></div></section>
<section class="detail-panel"><div class="panel-head"><div><span class="panel-kicker">Security</span><h2>Password and access</h2></div></div><div class="panel-body security-stack">
<div class="security-section"><h3>Reset Password</h3><p>Set a new password. Existing database sessions will be signed out.</p>
<form method="POST" action="{{ route('admin.users.reset_password', $user) }}">@csrf
<div class="field"><label for="password">New Password</label><input id="password" name="password" type="password" autocomplete="new-password">@error('password','passwordReset')<p class="field-error">{{ $message }}</p>@enderror</div>
<div class="field" style="margin-top:10px"><label for="password_confirmation">Confirm Password</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"></div>
<div class="form-actions"><button type="submit" class="primary-button">Reset Password</button></div></form></div>
<div class="security-section"><h3>{{ $user->is_suspended ? 'Reactivate Account' : 'Suspend Account' }}</h3><p>{{ $user->is_suspended ? 'Restore login and workspace access for this staff account.' : 'Block login, end open attendance, and sign out existing sessions.' }}</p>
<form method="POST" action="{{ route('admin.users.toggle_suspension', $user) }}" onsubmit="return confirm('{{ $user->is_suspended ? 'Reactivate' : 'Suspend' }} this account?');">@csrf<button type="submit" class="{{ $user->is_suspended ? 'primary-button' : 'warning-button' }}">{{ $user->is_suspended ? 'Reactivate Account' : 'Suspend Account' }}</button></form></div>
</div></section></div>
<div class="history-grid">
<section class="detail-panel"><div class="panel-head"><div><span class="panel-kicker">Attendance</span><h2>Recent attendance history</h2></div><span class="page-meta">{{ $attendanceHistory->count() }} records</span></div><div class="table-wrap"><table class="compact-table"><thead><tr><th>Date</th><th>Time In</th><th>Time Out</th><th>Duration</th></tr></thead><tbody>
@forelse($attendanceHistory as $attendance)<tr><td>{{ $attendance->time_in->format('M j, Y') }}</td><td>{{ $attendance->time_in->format('g:i A') }}</td><td>{{ $attendance->time_out?->format('g:i A') ?? 'Active' }}</td><td>{{ $attendance->time_in->diffForHumans($attendance->time_out ?? now(), true) }}</td></tr>@empty<tr><td colspan="4" class="empty-row">No attendance records yet.</td></tr>@endforelse
</tbody></table></div></section>
<section class="detail-panel"><div class="panel-head"><div><span class="panel-kicker">Weekly Schedule</span><h2>Saved class schedule</h2></div><span class="page-meta">{{ $schedules->count() }} entries</span></div><div class="table-wrap"><table class="compact-table"><thead><tr><th>Day</th><th>Time</th><th>Subject</th><th>Room</th><th>Semester</th></tr></thead><tbody>
@forelse($schedules as $schedule)<tr><td>{{ $schedule->day_of_week }}</td><td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</td><td>{{ $schedule->subject }}</td><td>{{ $schedule->room }}</td><td>{{ $schedule->semester }} · {{ $schedule->school_year }}</td></tr>@empty<tr><td colspan="5" class="empty-row">No weekly schedule entries saved.</td></tr>@endforelse
</tbody></table></div></section>
</div>
@endsection
