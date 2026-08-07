@extends('layouts.admin-users')
@section('title', 'User Management')
@section('content')
<section class="page-head"><div class="page-title"><span>Admin Workspace</span><h1>User Management</h1></div><div class="page-meta">{{ $users->total() }} staff account{{ $users->total() === 1 ? '' : 's' }}</div></section>
<form method="GET" action="{{ route('admin.users.index') }}" class="toolbar"><div class="filter-grid">
<div class="field"><label for="search">Search</label><input id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, username, or email"></div>
<div class="field"><label for="role">Role</label><select id="role" name="role"><option value="">All roles</option><option value="professor" @selected(($filters['role'] ?? '') === 'professor')>Professor</option><option value="faculty" @selected(($filters['role'] ?? '') === 'faculty')>Faculty</option></select></div>
<div class="field"><label for="department_id">Department</label><select id="department_id" name="department_id"><option value="">All departments</option>@foreach($departments as $department)<option value="{{ $department->id }}" @selected((string)($filters['department_id'] ?? '') === (string)$department->id)>{{ $department->name }}</option>@endforeach</select></div>
<div class="filter-actions"><button class="primary-button" type="submit">Apply</button><a class="secondary-button" href="{{ route('admin.users.index') }}">Clear</a></div>
</div></form>
<section class="table-panel"><div class="table-wrap"><table>
<thead><tr><th>Full Name</th><th>Username</th><th>Role</th><th>Department</th><th>Account</th><th>Attendance</th><th>Schedules</th><th style="text-align:right">Actions</th></tr></thead>
<tbody>
@forelse($users as $managedUser)
@php
    $openAttendance = $managedUser->attendanceRecords->first();
    $displayName = $managedUser->full_name ?: $managedUser->username;
    $initials = collect(preg_split('/\s+/', $displayName))->filter()->take(2)->map(fn($part) => strtoupper(substr($part, 0, 1)))->implode('');
@endphp
<tr>
<td><div class="user-cell"><div class="user-avatar">@if($managedUser->profile_picture)<img src="{{ asset('storage/'.$managedUser->profile_picture) }}" alt="">@else{{ $initials }}@endif</div><div class="user-copy"><strong>{{ $displayName }}</strong><span>{{ $managedUser->email }}</span></div></div></td>
<td>{{ $managedUser->username }}</td><td>{{ ucfirst($managedUser->role) }}</td><td>{{ $managedUser->department?->name ?? 'Unassigned' }}</td>
<td><span class="badge {{ $managedUser->is_suspended ? 'suspended' : 'active' }}">{{ $managedUser->is_suspended ? 'Suspended' : 'Active' }}</span></td>
<td><span class="badge {{ $openAttendance ? 'timed-in' : 'timed-out' }}">{{ $openAttendance ? 'Timed In' : 'Timed Out' }}</span></td><td>{{ $managedUser->schedules_count }}</td>
<td><div class="action-cell">
<a href="{{ route('admin.users.edit', $managedUser) }}" class="icon-button" title="Edit account and view history" aria-label="Edit {{ $displayName }}"><svg viewBox="0 0 24 24"><path d="m4 16-.8 4 4-.8L18.5 7.9l-3.2-3.2L4 16Z"/><path d="m13.8 6.2 3.2 3.2"/></svg></a>
@if($openAttendance)<form method="POST" action="{{ route('admin.users.force_time_out', $managedUser) }}" class="inline-form" onsubmit="return confirm('Force time out for this staff member?');">@csrf<button type="submit" class="icon-button force" title="Force time out" aria-label="Force time out {{ $displayName }}"><svg viewBox="0 0 24 24"><path d="M12 3v9"/><path d="M6.6 5.8a8 8 0 1 0 10.8 0"/></svg></button></form>@endif
</div></td></tr>
@empty<tr><td colspan="8" class="empty-row">No professor or faculty accounts match these filters.</td></tr>@endforelse
</tbody></table></div>
@if($users->hasPages())<div class="pagination"><span>Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span><div class="pagination-links"><a class="pagination-link{{ $users->onFirstPage() ? ' disabled' : '' }}" href="{{ $users->previousPageUrl() ?? '#' }}">Previous</a><a class="pagination-link{{ $users->hasMorePages() ? '' : ' disabled' }}" href="{{ $users->nextPageUrl() ?? '#' }}">Next</a></div></div>@endif
</section>
@endsection
