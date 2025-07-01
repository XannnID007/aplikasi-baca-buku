@extends('layouts.app')

@section('title', 'Masuk ke Akun Anda')

@section('content')
    <div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
        <div style="width: 100%; max-width: 450px;">
            <!-- Login Card -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <!-- Header -->
                <div
                    style="background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%); color: white; padding: 40px 30px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h2 style="color: white; margin-bottom: 10px; font-size: 28px;">Selamat Datang Kembali</h2>
                    <p style="color: rgba(255,255,255,0.9); margin: 0;">Masuk ke akun Anda untuk melanjutkan membaca</p>
                </div>

                <!-- Form -->
                <div style="padding: 40px 30px;">
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <!-- Email Field -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Masukkan email Anda..." required
                                autocomplete="email" autofocus>
                            @error('email')
                                <div style="color: #ef5350; font-size: 14px; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div style="position: relative;">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password Anda..." required autocomplete="current-password"
                                    id="passwordInput">
                                <button type="button" onclick="togglePassword()"
                                    style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #546e7a; cursor: pointer;">
                                    <i class="fas fa-eye" id="passwordToggle"></i>
                                </button>
                            </div>
                            @error('password')
                                <div style="color: #ef5350; font-size: 14px; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                            <label style="display: flex; align-items: center; cursor: pointer; color: #546e7a;">
                                <input type="checkbox" name="remember" style="margin-right: 8px; width: 16px; height: 16px;"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <span style="font-size: 14px;">Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    style="color: #42a5f5; text-decoration: none; font-size: 14px;">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary"
                            style="width: 100%; padding: 15px; font-size: 16px; margin-bottom: 20px;" id="submitBtn">
                            <i class="fas fa-sign-in-alt"></i> Masuk ke Akun
                        </button>
                    </form>

                    <!-- Divider -->
                    <div style="text-align: center; margin: 25px 0; position: relative;">
                        <div style="height: 1px; background: #e3f2fd;"></div>
                        <span
                            style="background: white; padding: 0 20px; color: #546e7a; font-size: 14px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            atau
                        </span>
                    </div>

                    <!-- Register Link -->
                    <div style="text-align: center;">
                        <p style="color: #546e7a; margin-bottom: 15px;">Belum punya akun?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline" style="width: 100%; padding: 12px;">
                            <i class="fas fa-user-plus"></i> Daftar Akun Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('home') }}" style="color: #42a5f5; text-decoration: none; margin: 0 15px;">
                    <i class="fas fa-home"></i> Beranda
                </a>
                <a href="{{ route('pilih.jenis') }}" style="color: #42a5f5; text-decoration: none; margin: 0 15px;">
                    <i class="fas fa-magic"></i> Coba Rekomendasi
                </a>
                <a href="{{ route('buku.index') }}" style="color: #42a5f5; text-decoration: none; margin: 0 15px;">
                    <i class="fas fa-books"></i> Katalog Buku
                </a>
            </div>
        </div>
    </div>

    <!-- Demo Accounts Info -->
    <div
        style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding: 30px; margin-top: 40px; border-radius: 15px;">
        <div style="max-width: 600px; margin: 0 auto; text-align: center;">
            <h4 style="color: #1976d2; margin-bottom: 20px;">
                <i class="fas fa-info-circle"></i> Akun Demo untuk Testing
            </h4>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div
                    style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(25, 118, 210, 0.1);">
                    <h6 style="color: #1976d2; margin-bottom: 10px;">
                        <i class="fas fa-user"></i> Akun User
                    </h6>
                    <div
                        style="font-family: monospace; background: #f8faff; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 14px;">
                        <div>Email: user@example.com</div>
                        <div>Password: password</div>
                    </div>
                    <button onclick="fillDemo('user')" class="btn btn-outline btn-sm">
                        <i class="fas fa-fill"></i> Isi Otomatis
                    </button>
                </div>

                <div
                    style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(25, 118, 210, 0.1);">
                    <h6 style="color: #1976d2; margin-bottom: 10px;">
                        <i class="fas fa-user-shield"></i> Akun Admin
                    </h6>
                    <div
                        style="font-family: monospace; background: #f8faff; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 14px;">
                        <div>Email: admin@perpustakaan.com</div>
                        <div>Password: password</div>
                    </div>
                    <button onclick="fillDemo('admin')" class="btn btn-outline btn-sm">
                        <i class="fas fa-fill"></i> Isi Otomatis
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const passwordToggle = document.getElementById('passwordToggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordToggle.className = 'fas fa-eye';
            }
        }

        // Fill demo credentials
        function fillDemo(type) {
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.querySelector('input[name="password"]');

            if (type === 'user') {
                emailInput.value = 'user@example.com';
                passwordInput.value = 'password';
            } else if (type === 'admin') {
                emailInput.value = 'admin@perpustakaan.com';
                passwordInput.value = 'password';
            }

            // Add visual feedback
            emailInput.style.background = '#e8f5e8';
            passwordInput.style.background = '#e8f5e8';

            setTimeout(() => {
                emailInput.style.background = '';
                passwordInput.style.background = '';
            }, 1000);
        }

        // Form submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
            submitBtn.disabled = true;

            // Re-enable button after 10 seconds if form doesn't submit successfully
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }, 10000);
        });

        // Add input animations
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');

            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#42a5f5';
                    this.style.boxShadow = '0 0 0 3px rgba(66, 165, 245, 0.1)';
                });

                input.addEventListener('blur', function() {
                    this.style.borderColor = '#e3f2fd';
                    this.style.boxShadow = 'none';
                });
            });

            // Card entrance animation
            const card = document.querySelector('.card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });

        // Remember me functionality enhancement
        const rememberCheckbox = document.querySelector('input[name="remember"]');
        if (rememberCheckbox) {
            // Load saved email if remember me was checked
            const savedEmail = localStorage.getItem('rememberedEmail');
            if (savedEmail) {
                document.querySelector('input[name="email"]').value = savedEmail;
                rememberCheckbox.checked = true;
            }

            // Save email when remember me is checked
            document.getElementById('loginForm').addEventListener('submit', function() {
                const email = document.querySelector('input[name="email"]').value;
                if (rememberCheckbox.checked) {
                    localStorage.setItem('rememberedEmail', email);
                } else {
                    localStorage.removeItem('rememberedEmail');
                }
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + D for demo user
            if (e.altKey && e.key.toLowerCase() === 'd') {
                e.preventDefault();
                fillDemo('user');
            }

            // Alt + A for demo admin
            if (e.altKey && e.key.toLowerCase() === 'a') {
                e.preventDefault();
                fillDemo('admin');
            }
        });

        // Error message animation
        const errorMessages = document.querySelectorAll('.is-invalid ~ div');
        errorMessages.forEach(error => {
            error.style.opacity = '0';
            error.style.transform = 'translateY(-10px)';

            setTimeout(() => {
                error.style.transition = 'all 0.3s ease';
                error.style.opacity = '1';
                error.style.transform = 'translateY(0)';
            }, 200);
        });
    </script>

    <style>
        /* Custom styles for better UX */
        .form-control.is-invalid {
            border-color: #ef5350 !important;
            box-shadow: 0 0 0 3px rgba(239, 83, 80, 0.1) !important;
        }

        .form-control:focus {
            outline: none;
            border-color: #42a5f5;
            box-shadow: 0 0 0 3px rgba(66, 165, 245, 0.1);
        }

        /* Loading state for submit button */
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Smooth transitions */
        .btn,
        .form-control,
        .card {
            transition: all 0.3s ease;
        }

        /* Demo credentials hover effect */
        .demo-credentials:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.15);
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .card {
                margin: 0 10px;
            }

            .card>div:first-child {
                padding: 30px 20px;
            }

            .card>div:last-child {
                padding: 30px 20px;
            }

            .demo-info {
                margin: 0 10px;
                padding: 20px;
            }

            .demo-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
@endsection
