<nav x-data="{ open: false }" class="bg-green-900 border-b border-green-800 shadow">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT SIDE -->
            <div class="flex items-center gap-6">

                <!-- LOGO + TITLE -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="bg-white p-2 rounded-lg shadow">
                        <x-application-logo class="h-6 w-6 text-green-900" />
                    </div>
                    <span class="text-white font-semibold text-lg hidden sm:block">
                        ISU Monitor
                    </span>
                </a>

                <!-- LINKS -->
                <div class="hidden sm:flex gap-6 ml-6">

                    <a href="{{ route('dashboard') }}"
                       class="text-white/90 hover:text-yellow-300 transition text-sm">
                        Dashboard
                    </a>

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.users.create') }}"
                           class="text-white/90 hover:text-yellow-300 transition text-sm">
                            Create Account
                        </a>

                        <a href="/admin/viewer"
                           class="text-white/90 hover:text-yellow-300 transition text-sm">
                            Viewer
                        </a>

                        <a href="/admin/status"
                           class="text-white/90 hover:text-yellow-300 transition text-sm">
                            Override
                        </a>

                        <a href="{{ route('academic_events.index') }}"
                           class="text-white/90 hover:text-yellow-300 transition text-sm">
                            Academic Events
                        </a>
                    @endif

                    @if(Auth::user()->role === 'teacher' || Auth::user()->role === 'faculty')
                        <a href="/staff/viewer"
                           class="text-white/90 hover:text-yellow-300 transition text-sm">
                            Staff Viewer
                        </a>
                    @endif

                    @if(Auth::user()->role === 'student')
                        <a href="/student/viewer"
                           class="text-white/90 hover:text-yellow-300 transition text-sm">
                            Student Viewer
                        </a>
                    @endif

                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="hidden sm:flex items-center gap-4">

                <!-- USER NAME -->
                <span class="text-white text-sm">
                    {{ Auth::user()->full_name }}
                </span>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-yellow-400 text-green-900 px-3 py-1 rounded-md text-sm font-semibold hover:bg-yellow-300 transition">
                        Logout
                    </button>
                </form>

            </div>

            <!-- MOBILE BUTTON -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open" class="text-white">
                    ☰
                </button>
            </div>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="open" class="sm:hidden bg-green-800 px-4 pb-4 space-y-2">

        <a href="{{ route('dashboard') }}" class="block text-white">Dashboard</a>

        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.users.create') }}" class="block text-white">Create Account</a>
            <a href="/admin/viewer" class="block text-white">Viewer</a>
            <a href="/admin/status" class="block text-white">Override</a>
            <a href="{{ route('academic_events.index') }}" class="block text-white">Academic Events</a>
        @endif

        @if(Auth::user()->role === 'teacher' || Auth::user()->role === 'faculty')
            <a href="/staff/viewer" class="block text-white">Staff Viewer</a>
        @endif

        @if(Auth::user()->role === 'student')
            <a href="/student/viewer" class="block text-white">Student Viewer</a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full bg-yellow-400 text-green-900 px-3 py-2 rounded mt-2">
                Logout
            </button>
        </form>

    </div>
</nav>
