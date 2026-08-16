@extends('layouts.admin-users')

@section('title', 'Create Account')

@section('content')
<section class="page-head">
    <div class="page-title">
        <span>Admin Access</span>
        <h1>Create Account</h1>
    </div>
    <div class="page-meta">Professor and faculty only</div>
</section>

<section class="detail-panel">
    <div class="panel-head">
        <div>
            <span class="panel-kicker">Account Details</span>
            <h2>Provision a workspace account</h2>
        </div>
    </div>

    <div class="panel-body">
        <form method="POST" action="{{ route('admin.users.store') }}" autocomplete="on">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="full_name">Full Name</label>
                    <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required autofocus>
                    @error('full_name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username">
                    @error('username')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="professor" @selected(old('role') === 'professor')>Professor</option>
                        <option value="faculty" @selected(old('role') === 'faculty')>Faculty</option>
                    </select>
                    @error('role')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="department_id">Department</label>
                    <select id="department_id" name="department_id">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) old('department_id') === (string) $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" autocapitalize="none" spellcheck="false">
                    <x-password-strength for="password" confirmation="password_confirmation" />
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" autocapitalize="none" spellcheck="false">
                    @error('password_confirmation')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.viewer') }}" class="secondary-button">Cancel</a>
                <button type="submit" class="primary-button">Create Account</button>
            </div>
        </form>
    </div>
</section>
@endsection
