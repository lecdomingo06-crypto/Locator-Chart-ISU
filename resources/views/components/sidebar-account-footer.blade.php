@php
    $sidebarUser = auth()->user();
    $sidebarDisplayName = $sidebarUser?->full_name ?: $sidebarUser?->username;
    $sidebarRole = $sidebarUser?->role ? ucfirst($sidebarUser->role) : 'User';
@endphp

@if($sidebarUser)
    <div class="sidebar-account-footer">
        <div class="sidebar-account-identity">
            <strong>{{ $sidebarDisplayName }}</strong>
            <span>{{ $sidebarRole }} account</span>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="sidebar-account-logout">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </div>

    <style>
        .admin-sidebar,
        .workspace-sidebar,
        .student-sidebar {
            display: flex !important;
            flex-direction: column;
        }

        .sidebar-account-footer {
            display: grid;
            gap: 12px;
            width: 100%;
            margin-top: auto;
            padding: 16px 8px 0;
            border-top: 1px solid rgba(12, 92, 56, 0.16);
            font-family: 'Outfit', system-ui, sans-serif;
        }

        .sidebar-account-identity {
            display: grid;
            gap: 3px;
            min-width: 0;
        }

        .sidebar-account-identity strong {
            overflow: hidden;
            color: #173524;
            font-size: 0.84rem;
            font-weight: 800;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-account-identity span {
            color: #607766;
            font-size: 0.78rem;
            line-height: 1.4;
        }

        .sidebar-account-logout {
            margin: 0;
        }

        .sidebar-account-logout button {
            width: 100%;
            min-height: 40px;
            border: 1px solid rgba(20, 114, 71, 0.32);
            border-radius: 7px;
            color: #0c5c38;
            background: #ffffff;
            font: inherit;
            font-size: 0.8rem;
            font-weight: 800;
            cursor: pointer;
        }

        .sidebar-account-logout button:hover {
            background: #eef8f1;
        }

        @media (max-width: 840px) {
            .sidebar-account-footer {
                margin-top: 16px;
            }
        }
    </style>
@endif