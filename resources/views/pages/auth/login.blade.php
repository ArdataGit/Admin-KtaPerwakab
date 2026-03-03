<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login - Dashboard</title>

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-gradient-green { background: linear-gradient(135deg, #3E9A3E 0%, #85C955 100%); }
        .btn-gradient-green { background: linear-gradient(135deg, #3E9A3E 0%, #85C955 100%); }
    </style>
</head>

<body class="bg-gradient-green min-h-screen flex items-center justify-center p-5">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        
        <!-- Left Side - Logo & Branding -->
        <div class="flex-1 bg-gradient-green p-10 md:p-16 flex flex-col items-center justify-center text-white relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute w-72 h-72 bg-white/10 rounded-full -top-24 -right-24"></div>
            <div class="absolute w-48 h-48 bg-white/10 rounded-full -bottom-12 -left-12"></div>
            
            <div class="relative z-10 text-center mb-8">
                <img src="{{ asset('img/logo.png') }}" 
                     alt="Logo" 
                     class="w-44 h-44 md:w-52 md:h-52 object-contain drop-shadow-lg mb-5 mx-auto"
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ccircle cx=%2250%22 cy=%2250%22 r=%2245%22 fill=%22%23fff%22/%3E%3Ctext x=%2250%22 y=%2260%22 font-size=%2240%22 text-anchor=%22middle%22 fill=%22%233E9A3E%22 font-weight=%22bold%22%3EL%3C/text%3E%3C/svg%3E'">
                <h2 class="text-3xl md:text-4xl font-bold mb-2 drop-shadow-md">Perwakab</h2>
                <p class="text-base md:text-lg font-light opacity-95">Dashboard Admin</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 p-10 md:p-16 flex flex-col justify-center">
            <div class="mb-10">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Selamat Datang!</h1>
                <p class="text-gray-600">Silakan login untuk melanjutkan ke dashboard</p>
            </div>

            @if(session('error'))
                <div class="flex items-center gap-3 p-4 mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#3E9A3E] focus:ring-4 focus:ring-[#3E9A3E]/10 transition-all placeholder:text-gray-300"
                               placeholder="Masukkan email Anda" 
                               required 
                               autofocus>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#3E9A3E] focus:ring-4 focus:ring-[#3E9A3E]/10 transition-all placeholder:text-gray-300"
                               placeholder="Masukkan password Anda" 
                               required>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-4 btn-gradient-green text-white font-semibold rounded-xl shadow-lg shadow-[#3E9A3E]/30 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#3E9A3E]/40 active:translate-y-0 transition-all mt-2">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session("success") }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session("error") }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')
</body>

</html>