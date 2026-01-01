<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title') - Dashboard</title>

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite CSS (Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Summernote CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }
        .swal2-container { z-index: 300000 !important; }
        
        /* Force sidebar spacing on desktop */
        @media (min-width: 1024px) {
            .main-content-wrapper {
                margin-left: 18rem !important; /* 288px = w-72 */
            }
        }
        
        /* Smooth scrolling for table */
        .overflow-x-auto {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
    </style>
    
    @stack('styles')
</head>

<body class="bg-gray-50">
    
    @include('layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper min-h-screen lg:ml-72 transition-all duration-300">
        
        @include('layouts.topbar')

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4 px-6 mt-auto">
            <div class="text-center text-sm text-gray-600">
                Copyright © {{ date('Y') }} - {{ config('app.name') }}
            </div>
        </footer>

    </div>

    <!-- Scroll to Top Button -->
    <a href="#page-top" class="fixed bottom-6 right-6 w-12 h-12 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all opacity-0 pointer-events-none" id="scrollTop">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Core Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Summernote JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        // Logout Confirmation with SweetAlert
        function confirmLogout() {
            Swal.fire({
                title: 'Ready to Leave?',
                text: 'Pilih "Logout" untuk mengakhiri sesi.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3E9A3E',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i>Logout',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-6 py-2 rounded-lg',
                    cancelButton: 'px-6 py-2 rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Sidebar Toggle for Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });

        // Scroll to Top Button
        window.addEventListener('scroll', function() {
            const scrollTop = document.getElementById('scrollTop');
            if (window.pageYOffset > 100) {
                scrollTop.classList.remove('opacity-0', 'pointer-events-none');
            } else {
                scrollTop.classList.add('opacity-0', 'pointer-events-none');
            }
        });

        document.getElementById('scrollTop').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    @stack('scripts')

</body>

</html>