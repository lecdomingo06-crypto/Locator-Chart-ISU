<x-guest-layout>
    <div class="auth-stack">
        <div class="auth-heading">
            <div class="auth-eyebrow">Admin Access</div>
            <h1 class="auth-title">Create a new account</h1>
            <p class="auth-copy">Provision student, teacher, and faculty access without leaving the familiar registration form.</p>
        </div>

        <x-auth-session-status class="auth-status" :status="session('status')" />

        <form method="POST" action="{{ route('admin.users.store') }}" class="auth-form-grid">
            @csrf

            <div class="auth-group">
                <x-input-label for="full_name" :value="__('Full Name')" class="auth-label" />
                <x-text-input id="full_name"
                    class="auth-field"
                    type="text"
                    name="full_name"
                    :value="old('full_name')"
                    required
                    autofocus />
                <x-input-error :messages="$errors->get('full_name')" class="auth-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="username" :value="__('Username')" class="auth-label" />
                <x-text-input id="username"
                    class="auth-field"
                    type="text"
                    name="username"
                    :value="old('username')"
                    required />
                <x-input-error :messages="$errors->get('username')" class="auth-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="role" :value="__('Role')" class="auth-label" />
                <select id="role" name="role"
                    class="auth-field auth-select"
                    required>
                    <option value="">Select Role</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="faculty" {{ old('role') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="auth-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="department_id" :value="__('Department')" class="auth-label" />
                <select id="department_id" name="department_id"
                    class="auth-field auth-select">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('department_id')" class="auth-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="password" :value="__('Password')" class="auth-label" />
                <x-text-input id="password"
                    class="auth-field"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="auth-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="auth-label" />
                <x-text-input id="password_confirmation"
                    class="auth-field"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
            </div>

            <button type="submit" class="auth-button">
                {{ __('Create Account') }}
            </button>
        </form>

        <div class="auth-footer">
            <span>Finished creating accounts?</span>
            <a href="{{ route('admin.dashboard') }}" class="auth-inline-link">Back to dashboard</a>
        </div>
    </div>
</x-guest-layout>
