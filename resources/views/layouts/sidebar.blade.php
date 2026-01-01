<!-- Sidebar -->
<aside class="fixed left-0 top-0 h-screen w-72 bg-white shadow-xl flex flex-col z-40 transition-transform duration-300 lg:translate-x-0 -translate-x-full" id="sidebar">
    
    <!-- Sidebar Brand -->
    <div class="p-5 border-b border-gray-100 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1">
            <img src="{{ asset('img/logo.png') }}" 
                 alt="Logo" 
                 class="w-24 h-24 object-contain"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ccircle cx=%2250%22 cy=%2250%22 r=%2245%22 fill=%22%233E9A3E%22/%3E%3Ctext x=%2250%22 y=%2260%22 font-size=%2240%22 text-anchor=%22middle%22 fill=%22%23fff%22 font-weight=%22bold%22%3EA%3C/text%3E%3C/svg%3E'">
            <span class="text-xl font-bold text-gray-800">Admin Panel</span>
        </a>
    </div>

    <!-- Navigation Menu - Scrollable -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-1 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">

        <!-- Dashboard - Semua role bisa akses -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
            <i class="fas fa-th-large text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        @php
            $userRole = Auth::user()->role;
            $isSuperadmin = $userRole === 'superadmin';
            $isAdmin = $userRole === 'admin';
            $isBendahara = $userRole === 'bendahara';
            $isPengurus = $userRole === 'pengurus';
        @endphp

        @if($isSuperadmin || $isAdmin)
            <!-- Keanggotaan Section - Hanya Superadmin & Admin -->
            <div class="pt-4 pb-2">
                <button onclick="toggleSection('keanggotaan')" class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                    <span>Keanggotaan</span>
                    <i class="fas fa-chevron-down transition-transform duration-200" id="keanggotaan-icon"></i>
                </button>
            </div>

            <div id="keanggotaan-menu" class="space-y-1">
                <a href="{{ route('user.anggota') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('user.anggota') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-users text-base {{ request()->routeIs('user.anggota') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Anggota</span>
                </a>

                <a href="#" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all">
                    <i class="fas fa-id-badge text-base text-[#3E9A3E]"></i>
                    <span class="font-medium">Kartu Anggota</span>
                </a>
            </div>
        @endif

        @if($isSuperadmin || $isAdmin || $isBendahara)
            <!-- Keuangan Section - Superadmin, Admin & Bendahara -->
            <div class="pt-4 pb-2">
                <button onclick="toggleSection('keuangan')" class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                    <span>Keuangan</span>
                    <i class="fas fa-chevron-down transition-transform duration-200" id="keuangan-icon"></i>
                </button>
            </div>

            <div id="keuangan-menu" class="space-y-1">
                <a href="{{ route('membership-fee.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('membership-fee.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-money-bill-wave text-base {{ request()->routeIs('membership-fee.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Iuran Anggota</span>
                </a>

                <a href="{{ route('master.donation-campaign.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('master.donation-campaign.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-hand-holding-heart text-base {{ request()->routeIs('master.donation-campaign.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Iuran & Donasi</span>
                </a>
            </div>
        @endif

        @if($isSuperadmin || $isAdmin || $isPengurus)
            <!-- Publikasi Section - Superadmin, Admin & Pengurus -->
            <div class="pt-4 pb-2">
                <button onclick="toggleSection('publikasi')" class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                    <span>Publikasi</span>
                    <i class="fas fa-chevron-down transition-transform duration-200" id="publikasi-icon"></i>
                </button>
            </div>

            <div id="publikasi-menu" class="space-y-1">
                <a href="{{ route('news.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('news.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-newspaper text-base {{ request()->routeIs('news.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Artikel</span>
                </a>

                <a href="{{ route('publikasi.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('publikasi.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-brain text-base {{ request()->routeIs('publikasi.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Publikasi Karya</span>
                </a>

                <a href="{{ route('bisnis.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('bisnis.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-briefcase text-base {{ request()->routeIs('bisnis.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Karya dan Bisnis</span>
                </a>

                <a href="{{ route('struktur-organisasi.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('struktur-organisasi.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-sitemap text-base {{ request()->routeIs('struktur-organisasi.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Struktur Organisasi</span>
                </a>

                <a href="{{ route('info-duka.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('info-duka.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-bell text-base {{ request()->routeIs('info-duka.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Info Duka</span>
                </a>

                <a href="{{ route('home-banner.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('home-banner.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-image text-base {{ request()->routeIs('home-banner.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Banner</span>
                </a>
            </div>

            <!-- UMKM Section - Superadmin, Admin & Pengurus -->
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Marketplace</p>
            </div>

            <a href="{{ route('umkm.index') }}" 
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('umkm.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                <i class="fas fa-store text-base {{ request()->routeIs('umkm.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                <span class="font-medium">Marketplace</span>
            </a>
        @endif

        @if($isSuperadmin || $isAdmin)
            <!-- Point Section - Hanya Superadmin & Admin -->
            <div class="pt-4 pb-2">
                <button onclick="toggleSection('point')" class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                    <span>Point Management</span>
                    <i class="fas fa-chevron-down transition-transform duration-200" id="point-icon"></i>
                </button>
            </div>

            <div id="point-menu" class="space-y-1">
                <a href="{{ route('point-kategoris.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('point-kategoris.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-tags text-base {{ request()->routeIs('point-kategoris.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Kategori Point</span>
                </a>

                <a href="{{ route('penukaran-poin.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('penukaran-poin.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-gift text-base {{ request()->routeIs('penukaran-poin.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Produk Point</span>
                </a>

                <a href="{{ route('tukar-point.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('tukar-point.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-exchange-alt text-base {{ request()->routeIs('tukar-point.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Tukar Point</span>
                </a>
            </div>

            <!-- Master Section - Hanya Superadmin & Admin -->
            <div class="pt-4 pb-2">
                <button onclick="toggleSection('pengaturan')" class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider hover:text-gray-600 transition-colors">
                    <span>Pengaturan</span>
                    <i class="fas fa-chevron-down transition-transform duration-200" id="pengaturan-icon"></i>
                </button>
            </div>

            <div id="pengaturan-menu" class="space-y-1">
                <a href="{{ route('position.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('position.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-user-tag text-base {{ request()->routeIs('position.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Posisi</span>
                </a>

                <a href="{{ route('division.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('division.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-layer-group text-base {{ request()->routeIs('division.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">Divisi</span>
                </a>

                <a href="{{ route('user.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-all {{ request()->routeIs('user.index') ? 'bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white hover:from-[#358935] hover:to-[#75B945]' : '' }}">
                    <i class="fas fa-users-cog text-base {{ request()->routeIs('user.index') ? 'text-white' : 'text-[#3E9A3E]' }}"></i>
                    <span class="font-medium">User</span>
                </a>
            </div>
        @endif

    </nav>

</aside>
<!-- End Sidebar -->

<script>
    function toggleSection(sectionName) {
        const menu = document.getElementById(sectionName + '-menu');
        const icon = document.getElementById(sectionName + '-icon');
        
        if (menu.style.display === 'none') {
            menu.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
            localStorage.setItem('sidebar-' + sectionName, 'open');
        } else {
            menu.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
            localStorage.setItem('sidebar-' + sectionName, 'closed');
        }
    }

    // Restore section states from localStorage on page load
    document.addEventListener('DOMContentLoaded', function() {
        const sections = ['keanggotaan', 'keuangan', 'publikasi', 'point', 'pengaturan'];
        
        sections.forEach(section => {
            const menu = document.getElementById(section + '-menu');
            const icon = document.getElementById(section + '-icon');
            
            if (!menu || !icon) return; // Skip if section doesn't exist for this role
            
            const savedState = localStorage.getItem('sidebar-' + section);
            
            // Check if any link in this section is active
            const activeLink = menu.querySelector('.bg-gradient-to-r');
            
            if (savedState === 'closed' && !activeLink) {
                // If saved as closed and no active link, keep it closed
                menu.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            } else {
                // Otherwise open it (default or has active link)
                menu.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
                if (!savedState) {
                    localStorage.setItem('sidebar-' + section, 'open');
                }
            }
        });
    });
</script>

<style>
    /* Custom Scrollbar untuk Sidebar */
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #d1d5db;
        border-radius: 10px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background-color: #9ca3af;
    }

    /* Responsive adjustments */
    @media (max-height: 768px) {
        aside nav {
            font-size: 0.875rem;
        }
        
        aside nav a {
            padding: 0.5rem 1rem;
        }
    }

    @media (max-height: 640px) {
        aside nav {
            font-size: 0.8125rem;
        }
        
        aside nav a {
            padding: 0.4rem 1rem;
        }
    }
</style>
