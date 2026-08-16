@extends('layouts.admin-users')

@section('title', 'Edit Staff Account')

@section('content')
@php
    $displayName = $user->full_name ?: $user->username;
    $openAttendance = $user->currentAttendance();
    $initials = collect(preg_split('/\s+/', $displayName))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
@endphp

<style>
    .staff-edit-shell {
        width: min(980px, 100%);
        margin: 0 auto;
    }

    .staff-edit-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .staff-edit-title span,
    .staff-card-kicker {
        color: var(--green-800);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .07em;
        text-transform: uppercase;
    }

    .staff-edit-title h1 {
        margin: 5px 0 0;
        font-size: clamp(1.85rem, 3vw, 2.35rem);
        line-height: 1.05;
    }

    .staff-edit-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--surface);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .staff-profile-top {
        display: grid;
        justify-items: center;
        gap: 10px;
        padding: 30px 24px 22px;
        border-bottom: 1px solid var(--border);
        text-align: center;
    }

    .staff-profile-photo {
        display: grid;
        place-items: center;
        width: 86px;
        height: 86px;
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 50%;
        color: var(--green-900);
        background: var(--green-100);
        font-size: 1.35rem;
        font-weight: 800;
    }

    .staff-profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .staff-profile-top h2 {
        margin: 0;
        font-size: 1.3rem;
    }

    .staff-profile-top p {
        margin: 0;
        color: var(--muted);
    }

    .staff-status-row {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 4px;
    }

    .staff-edit-content {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(300px, .85fr);
        gap: 18px;
        padding: 24px;
    }

    .staff-form-panel {
        min-width: 0;
    }

    .staff-form-panel h2,
    .staff-security-card h2 {
        margin: 4px 0 18px;
        font-size: 1.1rem;
    }

    .staff-profile-form {
        display: grid;
        gap: 14px;
    }

    .staff-field-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .staff-field-full {
        grid-column: 1 / -1;
    }

    .staff-profile-form .field input,
    .staff-profile-form .field select,
    .staff-security-card .field input {
        min-height: 44px;
        border-radius: 6px;
    }

    .staff-form-actions {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        margin-top: 4px;
    }

    .staff-form-actions .primary-button,
    .staff-form-actions .secondary-button {
        min-width: 132px;
    }

    .staff-security-card {
        display: grid;
        gap: 22px;
        padding-left: 20px;
        border-left: 1px solid var(--border);
    }

    .staff-security-section {
        display: grid;
        gap: 12px;
    }

    .staff-security-section + .staff-security-section {
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .staff-security-section h3 {
        margin: 0;
        font-size: .96rem;
    }

    .staff-security-section p {
        margin: 0;
        color: var(--muted);
        font-size: .82rem;
        line-height: 1.5;
    }

    .staff-security-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 8px;
    }

    @media (max-width: 900px) {
        .staff-edit-content {
            grid-template-columns: 1fr;
        }

        .staff-security-card {
            padding-left: 0;
            padding-top: 20px;
            border-left: 0;
            border-top: 1px solid var(--border);
        }
    }

    @media (max-width: 640px) {
        .staff-edit-head {
            align-items: stretch;
            flex-direction: column;
        }

        .staff-field-grid {
            grid-template-columns: 1fr;
        }

        .staff-edit-content {
            padding: 18px;
        }

        .staff-form-actions,
        .staff-security-actions {
            align-items: stretch;
            flex-direction: column-reverse;
        }

        .staff-form-actions .primary-button,
        .staff-form-actions .secondary-button,
        .staff-security-actions > * {
            width: 100%;
        }
    }
</style>

<div class="staff-edit-shell">
    <section class="staff-edit-head">
        <div class="staff-edit-title">
            <span>User Management</span>
            <h1>Edit Staff Account</h1>
        </div>
        <a href="{{ route('admin.users.index') }}" class="secondary-button">Back to users</a>
    </section>

    <section class="staff-edit-card">
        <div class="staff-profile-top">
            <div class="staff-profile-photo">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/'.$user->profile_picture) }}" alt="Profile picture of {{ $displayName }}">
                @else
                    {{ $initials }}
                @endif
            </div>
            <div>
                <h2>{{ $displayName }}</h2>
                <p>{{ ucfirst($user->role) }} - {{ $user->department?->name ?? 'Unassigned' }}</p>
            </div>
            <div class="staff-status-row">
                <span class="badge {{ $user->is_suspended ? 'suspended' : 'active' }}">
                    {{ $user->is_suspended ? 'Suspended' : 'Active Account' }}
                </span>
                <span class="badge {{ $openAttendance ? 'timed-in' : 'timed-out' }}">
                    {{ $openAttendance ? 'Timed In' : 'Timed Out' }}
                </span>
            </div>
        </div>

        <div class="staff-edit-content">
            <section class="staff-form-panel">
                <span class="staff-card-kicker">Account Details</span>
                <h2>Edit staff information</h2>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="staff-profile-form">
                    @csrf
                    @method('PATCH')

                    <div class="staff-field-grid">
                        <div class="field staff-field-full">
                            <label for="full_name">Full Name</label>
                            <input id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                            @error('full_name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field staff-field-full">
                            <label for="username">Username</label>
                            <input id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="role">Role</label>
                            <select id="role" name="role" required>
                                <option value="professor" @selected(old('role', $user->role) === 'professor')>Professor</option>
                                <option value="faculty" @selected(old('role', $user->role) === 'faculty')>Faculty</option>
                            </select>
                            @error('role')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="department_id">Department</label>
                            <select id="department_id" name="department_id" required>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @selected((string) old('department_id', $user->department_id) === (string) $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="staff-form-actions">
                        <button type="submit" class="primary-button">Save</button>
                        <a href="{{ route('admin.users.index') }}" class="secondary-button">Cancel</a>
                    </div>
                </form>
            </section>

            <aside class="staff-security-card">
                <section class="staff-security-section">
                    <span class="staff-card-kicker">Security</span>
                    <h2>Password and access</h2>

                    <h3>Reset Password</h3>
                    <p>Set a new password and sign out existing database sessions.</p>

                    <form method="POST" action="{{ route('admin.users.reset_password', $user) }}" class="staff-profile-form" autocomplete="on">
                        @csrf
                        <input type="hidden" name="username" value="{{ $user->username ?? $user->email }}" autocomplete="username">

                        <div class="field">
                            <label for="password">New Password</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" autocapitalize="none" spellcheck="false">
                            <x-password-strength for="password" confirmation="password_confirmation" />
                            @error('password', 'passwordReset')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" autocapitalize="none" spellcheck="false">
                        </div>

                        <div class="staff-security-actions">
                            <button type="submit" class="primary-button">Reset Password</button>
                        </div>
                    </form>
                </section>

                <section class="staff-security-section">
                    <h3>{{ $user->is_suspended ? 'Reactivate Account' : 'Suspend Account' }}</h3>
                    <p>
                        {{ $user->is_suspended
                            ? 'Restore login and workspace access for this staff account.'
                            : 'Block login, end open attendance, and sign out existing sessions.' }}
                    </p>

                    <form method="POST" action="{{ route('admin.users.toggle_suspension', $user) }}" onsubmit="return confirm('{{ $user->is_suspended ? 'Reactivate' : 'Suspend' }} this account?');">
                        @csrf
                        <button type="submit" class="{{ $user->is_suspended ? 'primary-button' : 'warning-button' }}">
                            {{ $user->is_suspended ? 'Reactivate Account' : 'Suspend Account' }}
                        </button>
                    </form>
                </section>
            </aside>
        </div>
    </section>
</div>
@endsection
