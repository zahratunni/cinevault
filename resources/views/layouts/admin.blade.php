<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - CineVault</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Heroicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out">
            <div class="flex flex-col h-full">
                
                <!-- Logo -->
                <div class="flex items-center justify-between h-16 px-6 bg-gray-800">
                    <span class="text-xl font-bold text-white">🎬 CineVault</span>
                    <button id="closeSidebar" class="lg:hidden text-gray-400 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 overflow-y-auto">
                    <ul class="space-y-2">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                                <i class="fas fa-home w-5"></i>
                                <span class="ml-3">Dashboard</span>
                            </a>
                        </li>

                        <!-- Kelola Film -->
                        <li>
                            <a href="{{ route('admin.films.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('admin.films.*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                                <i class="fas fa-film w-5"></i>
                                <span class="ml-3">Kelola Film</span>
                            </a>
                        </li>

                        <!-- Kelola Jadwal -->
                        <li>
                            <a href="{{ route('admin.jadwals.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('admin.jadwals.*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                                <i class="fas fa-calendar-alt w-5"></i>
                                <span class="ml-3">Kelola Jadwal</span>
                            </a>
                        </li>

                        <!-- Kelola Studio -->
                        <li>
                            <a href="{{ route('admin.studios.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('admin.studios.*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                                <i class="fas fa-door-open w-5"></i>
                                <span class="ml-3">Kelola Studio</span>
                            </a>
                        </li>

                        <!-- Kelola Kasir -->
                        <li>
                            <a href="{{ route('admin.kasirs.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('admin.kasirs.*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                                <i class="fas fa-user-tie w-5"></i>
                                <span class="ml-3">Kelola Kasir</span>
                            </a>
                        </li>

                        <!-- Kelola Pelanggan -->
                        <li>
                            <a href="{{ route('admin.pelanggans.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('admin.pelanggans.*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                                <i class="fas fa-users w-5"></i>
                                <span class="ml-3">Kelola Pelanggan</span>
                            </a>
                        </li>

                        <!-- Divider -->
                        <li class="pt-4 mt-4 border-t border-gray-700">
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition text-gray-300">
                                <i class="fas fa-cog w-5"></i>
                                <span class="ml-3">Pengaturan</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Profile -->
                <div class="px-4 py-4 border-t border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                {{ substr(Auth::user()->username, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->username }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                    
                    <!-- Mobile Menu Button -->
                    <button id="openSidebar" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>

                    <!-- Page Title -->
                    <h1 class="text-xl font-semibold text-gray-800 hidden sm:block">
                        @yield('page-title', 'Dashboard')
                    </h1>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-4 ml-auto">
                        <!-- Notifications -->
                        <button class="relative text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative" id="userDropdown">
                            <button id="userMenuButton" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold">
                                    {{ substr(Auth::user()->username, 0, 1) }}
                                </div>
                                <span class="hidden md:block text-sm font-medium">{{ Auth::user()->username }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <hr class="my-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 lg:p-8">
                
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Overlay for Mobile -->
    <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

    <!-- JavaScript -->
    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        openSidebar.addEventListener('click', toggleSidebar);
        closeSidebar.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // User Dropdown
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        userMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!userMenu.classList.contains('hidden') && !userMenuButton.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>