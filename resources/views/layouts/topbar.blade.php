<nav class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        
        <!-- Left Side - Sidebar Toggle & Page Title -->
        <div class="flex items-center gap-4">
            <button id="sidebarToggle" class="lg:hidden w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        </div>

        <!-- Right Side - Notifications & Profile -->
        <div class="flex items-center gap-4">

            <!-- Notification -->
            <div class="relative">
                <button class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-lg transition-all relative">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg transition-all">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=3E9A3E&color=fff" 
                         class="w-9 h-9 rounded-full object-cover" 
                         alt="Avatar">
                    <div class="text-left hidden md:block">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
                     style="display: none;">
                    
                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-all">
                        <i class="fas fa-user text-[#3E9A3E]"></i>
                        <span>Profile</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-all">
                        <i class="fas fa-cog text-[#3E9A3E]"></i>
                        <span>Pengaturan</span>
                    </a>
                    
                    <hr class="my-2 border-gray-200">
                    
                    <a href="#" onclick="event.preventDefault(); confirmLogout();" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-all">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</nav>

<!-- Alpine.js for dropdown -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>