@extends('layouts.admin-users')

@section('title', 'Student Requests')

@section('content')
<section class="page-head">
    <div class="page-title">
        <span>Admin Review</span>
        <h1>Student Requests</h1>
    </div>
    <div class="page-meta">{{ $pendingRegistrations->total() }} pending request{{ $pendingRegistrations->total() === 1 ? '' : 's' }}</div>
</section>

<section class="table-panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Department</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingRegistrations as $registration)
                    @php
                        $initials = collect(preg_split('/\s+/', $registration->full_name))
                            ->filter()
                            ->take(2)
                            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                            ->implode('');
                    @endphp
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">{{ $initials ?: 'ST' }}</div>
                                <div class="user-copy">
                                    <strong>{{ $registration->full_name }}</strong>
                                    <span>{{ $registration->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $registration->student_id }}</td>
                        <td>{{ $registration->department?->name ?? 'Unassigned' }}</td>
                        <td>{{ $registration->created_at->format('M j, Y g:i A') }}</td>
                        <td><span class="badge pending">Pending</span></td>
                        <td>
                            <div class="action-cell">
                                <a
                                    href="{{ route('admin.student_registrations.show', $registration) }}"
                                    class="icon-button"
                                    title="View request details"
                                    aria-label="View {{ $registration->full_name }} request details"
                                >
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"/>
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.student_registrations.approve', $registration) }}" class="inline-form" onsubmit="return confirm('Approve this student account request?');">
                                    @csrf
                                    <button type="submit" class="row-button approve">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.student_registrations.decline', $registration) }}" class="inline-form" onsubmit="return confirm('Decline this student account request?');">
                                    @csrf
                                    <button type="submit" class="row-button decline">Decline</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-row">No pending student requests right now.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pendingRegistrations->hasPages())
        <div class="pagination">
            <span>Pending page {{ $pendingRegistrations->currentPage() }} of {{ $pendingRegistrations->lastPage() }}</span>
            <div class="pagination-links">
                <a class="pagination-link{{ $pendingRegistrations->onFirstPage() ? ' disabled' : '' }}" href="{{ $pendingRegistrations->previousPageUrl() ?? '#' }}">Previous</a>
                <a class="pagination-link{{ $pendingRegistrations->hasMorePages() ? '' : ' disabled' }}" href="{{ $pendingRegistrations->nextPageUrl() ?? '#' }}">Next</a>
            </div>
        </div>
    @endif
</section>

<section class="history-section">
    <div class="section-head">
        <div>
            <span class="panel-kicker">Recent Reviews</span>
            <h2>Reviewed Request History</h2>
        </div>

        @if($reviewedRegistrations->total() > 0)
            <form method="POST" action="{{ route('admin.student_registrations.clear_reviewed') }}" onsubmit="return confirm('Clear all reviewed request history? Approved student accounts will not be deleted.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="danger-button">Clear History</button>
            </form>
        @endif
    </div>

    <section class="table-panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Department</th>
                        <th>Decision</th>
                        <th>Reviewed By</th>
                        <th>Reviewed</th>
                        <th style="text-align:right">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviewedRegistrations as $registration)
                        @php
                            $initials = collect(preg_split('/\s+/', $registration->full_name))
                                ->filter()
                                ->take(2)
                                ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                                ->implode('');
                        @endphp
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">{{ $initials ?: 'ST' }}</div>
                                    <div class="user-copy">
                                        <strong>{{ $registration->full_name }}</strong>
                                        <span>{{ $registration->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $registration->student_id }}</td>
                            <td>{{ $registration->department?->name ?? 'Unassigned' }}</td>
                            <td><span class="badge {{ $registration->status }}">{{ ucfirst($registration->status) }}</span></td>
                            <td>{{ ($registration->reviewer?->full_name ?: $registration->reviewer?->username) ?? 'Unknown' }}</td>
                            <td>{{ $registration->reviewed_at?->format('M j, Y g:i A') ?? 'Not recorded' }}</td>
                            <td>
                                <div class="action-cell">
                                    <a
                                        href="{{ route('admin.student_registrations.show', $registration) }}"
                                        class="icon-button"
                                        title="View reviewed request"
                                        aria-label="View {{ $registration->full_name }} reviewed request"
                                    >
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"/>
                                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-row">Approved and declined requests will appear here.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviewedRegistrations->hasPages())
            <div class="pagination">
                <span>History page {{ $reviewedRegistrations->currentPage() }} of {{ $reviewedRegistrations->lastPage() }}</span>
                <div class="pagination-links">
                    <a class="pagination-link{{ $reviewedRegistrations->onFirstPage() ? ' disabled' : '' }}" href="{{ $reviewedRegistrations->previousPageUrl() ?? '#' }}">Previous</a>
                    <a class="pagination-link{{ $reviewedRegistrations->hasMorePages() ? '' : ' disabled' }}" href="{{ $reviewedRegistrations->nextPageUrl() ?? '#' }}">Next</a>
                </div>
            </div>
        @endif
    </section>
</section>
@endsection
