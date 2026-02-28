<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - ExportHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside id="sidebar"
        class="bg-[#0B1120] text-white w-64 flex-shrink-0 hidden md:flex flex-col fixed md:relative inset-y-0 left-0 z-50 h-full border-r border-gray-800 transition-all duration-300">
        <!-- Brand -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-800 bg-[#0B1120]">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-cube text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white tracking-tight">ExportHub</h1>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Admin</p>
                </div>
            </div>
            <button id="sidebar-close" class="md:hidden text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Menu</p>

            <a href="{{ route('admin.dashboard') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i
                    class="fas fa-tachometer-alt w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} transition-colors"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i
                    class="fas fa-users w-5 h-5 mr-3 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} transition-colors"></i>
                Users
            </a>

            <a href="{{ route('admin.events.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i
                    class="fas fa-calendar-alt w-5 h-5 mr-3 {{ request()->routeIs('admin.events.* ') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} transition-colors"></i>
                Events
            </a>

            <a href="{{ route('admin.summits.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.summits.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i
                    class="fas fa-mountain w-5 h-5 mr-3 {{ request()->routeIs('admin.summits.*') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} transition-colors"></i>
                Summits
            </a>

            {{-- EOI separator --}}
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-5 mb-2">NESS 2026</p>

            <a href="{{ route('admin.eois.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.eois.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i
                    class="fas fa-handshake w-5 h-5 mr-3 {{ request()->routeIs('admin.eois.*') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} transition-colors"></i>
                EOI Applications
                {{-- pending badge --}}
                @php
                    $pendingCount = \App\Models\SummitEoi::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
        </nav>

        <!-- User Profile -->
        <div class="p-4 border-t border-gray-800 bg-[#0f1623]">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-sm font-bold shadow-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-red-400 bg-red-400/10 hover:bg-red-400/20 rounded-lg transition-all duration-200">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Mobile Header -->
        <header class="bg-white shadow-sm md:hidden z-10">
            <div class="p-4 flex justify-between items-center">
                <span class="text-xl font-bold text-gray-800">ExportHub</span>
                <button id="sidebar-toggle" class="text-gray-600 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"
                    role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('hidden');
                });
            }

            if (sidebarClose && sidebar) {
                sidebarClose.addEventListener('click', () => {
                    sidebar.classList.add('hidden');
                });
            }
        });
    </script>

</body>

</html>