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

<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row shadow-inner">
    <!-- Backdrop for mobile sidebar -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300 opacity-0 md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="bg-[#0B1120] text-white w-64 flex-shrink-0 flex flex-col fixed md:relative top-0 left-0 z-50 h-screen border-r border-gray-800 transition-transform duration-300 -translate-x-full md:translate-x-0">
        <!-- Brand -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-800 bg-[#0B1120] flex-shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-cube text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white tracking-tight">ExportHub</h1>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Admin</p>
                </div>
            </div>
            <button id="sidebar-close" class="md:hidden text-gray-400 hover:text-white transition-colors p-2">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scrollbar">
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
                    class="fas fa-calendar-alt w-5 h-5 mr-3 {{ request()->routeIs('admin.events.*') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} transition-colors"></i>
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

            <a href="{{ route('admin.invitations.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.invitations.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i
                    class="fas fa-ticket-alt w-5 h-5 mr-3 {{ request()->routeIs('admin.invitations.*') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} transition-colors"></i>
                Invitation Tokens
            </a>

            {{-- Community separator --}}
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-5 mb-2">Community</p>

            <a href="{{ route('admin.community.index') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.community.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i
                    class="fas fa-comments w-5 h-5 mr-3 {{ request()->routeIs('admin.community.*') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} transition-colors"></i>
                Posts & Discussions
                @php
                    $reportedPostsCount = \Illuminate\Support\Facades\Schema::hasTable('community_posts') ? \App\Models\CommunityPost::where('reports_count', '>', 0)->count() : 0;
                @endphp
                @if($reportedPostsCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $reportedPostsCount }}
                    </span>
                @endif
            </a>

        </nav>

        <!-- User Profile -->
        <div class="p-4 border-t border-gray-800 bg-[#0f1623] flex-shrink-0">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-sm font-bold shadow-lg flex-shrink-0">
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

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
        <!-- Mobile Header (Visible only on small screens) -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-40 md:hidden h-16 flex items-center justify-between px-4 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-cube text-white text-xs"></i>
                </div>
                <span class="text-lg font-bold text-gray-900 tracking-tight">ExportHub</span>
            </div>
            <button id="sidebar-toggle" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </header>

        <!-- Page Content Area -->
        <main class="flex-1 p-4 md:p-8 overflow-y-auto">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center gap-3"
                        role="alert">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <div>
                            <p class="font-bold text-sm">Success</p>
                            <p class="text-xs">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center gap-3" role="alert">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <div>
                            <p class="font-bold text-sm">Error</p>
                            <p class="text-xs">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', openSidebar);
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }
        });
    </script>

</body>

</html>