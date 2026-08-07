@extends('layouts.admin-users')

@section('title', 'Student Request Details')

@section('content')
@php
    $initials = collect(preg_split('/\s+/', $registration->full_name))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
@endphp

<section class="page-head">
    <div class="page-title">
        <span>Student Requests</span>
        <h1>Request Details</h1>
    </div>
    <a href="{{ route('admin.student_registrations.index') }}" class="secondary-button">Back to Requests</a>
</section>

<section class="detail-head">
    <div class="detail-user">
        <div class="user-avatar">{{ $initials ?: 'ST' }}</div>
        <div>
            <h2>{{ $registration->full_name }}</h2>
            <p>{{ $registration->email }}</p>
        </div>
    </div>
    <span class="badge {{ $registration->status }}">{{ ucfirst($registration->status) }}</span>
</section>

<div class="detail-grid">
    <section class="detail-panel">
        <div class="panel-head">
            <div>
                <span class="panel-kicker">Submitted Information</span>
                <h2>Student Account Details</h2>
            </div>
        </div>

        <div class="request-information">
            <div class="information-item">
                <span>Full Name</span>
                <strong>{{ $registration->full_name }}</strong>
            </div>
            <div class="information-item">
                <span>Student ID</span>
                <strong>{{ $registration->student_id }}</strong>
            </div>
            <div class="information-item">
                <span>Email Address</span>
                <strong>{{ $registration->email }}</strong>
            </div>
            <div class="information-item">
                <span>Department</span>
                <strong>{{ $registration->department?->name ?? 'Unassigned' }}</strong>
            </div>
            <div class="information-item">
                <span>Submitted</span>
                <strong>{{ $registration->created_at->format('F j, Y g:i A') }}</strong>
            </div>
            <div class="information-item">
                <span>Last Updated</span>
                <strong>{{ $registration->updated_at->format('F j, Y g:i A') }}</strong>
            </div>
        </div>
    </section>

    <section class="detail-panel">
        <div class="panel-head">
            <div>
                <span class="panel-kicker">Admin Control</span>
                <h2>{{ $registration->status === 'pending' ? 'Review Request' : 'Review Record' }}</h2>
            </div>
        </div>

        <div class="panel-body">
            @if($registration->status === 'pending')
                <div class="security-stack">
                    <div class="security-section">
                        <h3>Approve Student Account</h3>
                        <p class="review-copy">Creates the student account using the submitted ID and sends the approval email.</p>
                        <form method="POST" action="{{ route('admin.student_registrations.approve', $registration) }}" onsubmit="return confirm('Approve this student account request?');">
                            @csrf
                            <button type="submit" class="primary-button">Approve Request</button>
                        </form>
                    </div>

                    <div class="security-section">
                        <h3>Decline Request</h3>
                        <p class="review-copy">The reason is optional and will be included in the decline email.</p>
                        <form method="POST" action="{{ route('admin.student_registrations.decline', $registration) }}">
                            @csrf
                            <div class="field">
                                <label for="decline_reason">Decline Reason</label>
                                <textarea id="decline_reason" name="decline_reason" maxlength="1000" placeholder="Explain why this request was declined (optional)">{{ old('decline_reason') }}</textarea>
                                @error('decline_reason')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="danger-button" onclick="return confirm('Decline this student account request?');">Decline Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="security-stack">
                    <div class="security-section">
                        <h3>{{ ucfirst($registration->status) }} Request</h3>
                        <p class="review-copy">Reviewed by {{ ($registration->reviewer?->full_name ?: $registration->reviewer?->username) ?? 'an administrator' }} on {{ $registration->reviewed_at?->format('F j, Y g:i A') ?? 'an unknown date' }}.</p>
                    </div>

                    @if($registration->status === 'declined')
                        <div class="security-section">
                            <h3>Decline Reason</h3>
                            <div class="review-note">{{ $registration->decline_reason ?: 'No decline reason was provided.' }}</div>
                        </div>
                    @else
                        <div class="security-section">
                            <h3>Account Created</h3>
                            <p class="review-copy">Clearing this review record will not remove the approved student account.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
