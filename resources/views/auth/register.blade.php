@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
    <div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
        <div style="width: 100%; max-width: 500px;">
            <!-- Register Card -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <!-- Header -->
                <div
                    style="background: linear-gradient(135deg, #66bb6a 0%, #388e3c 100%); color: white; padding: 40px 30px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 style="color: white; margin-bottom: 10px; font-size: 28px;">Bergabung dengan Kami</h2>
                    <p style="color: rgba(255,255,255,0.9); margin: 0;">Daftar gratis dan mulai petualangan membaca Anda</p>
                </div>

                <!-- Form -->
                <div style="padding: 40px 30px;">
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <!-- Name Field -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i> Nama Lengkap
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda..." required
                                autocomplete="name" autofocus>
                            @error('name')
                                <div style="color: #ef5350; font-size: 14px; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Masukkan alamat email Anda..." required
                                autocomplete="email">
                            @error('email')
                                <div style="color: #ef5350; font-size: 14px; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div style="color: #546e7a; font-size: 12px; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i> Email akan digunakan untuk login
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div style="position: relative;">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Buat password yang kuat..." required autocomplete="new-password"
                                    id="passwordInput">
                                <button type="button" onclick="togglePassword('passwordInput', 'passwordToggle')"
                                    style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #546e7a; cursor: pointer;">
                                    <i class="fas fa-eye" id="passwordToggle"></i>
                                </button>
                            </div>
                            @error('password')
                                <div style="color: #ef5350; font-size: 14px; margin-top: 5px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror

                            <!-- Password Strength Indicator -->
                            <div style="margin-top: 8px;">
                                <div style="display: flex; gap: 2px; margin-bottom: 5px;">
                                    <div class="strength-bar" data-level="1"></div>
                                    <div class="strength-bar" data-level="2"></div>
                                    <div class="strength-bar" data-level="3"></div>
                                    <div class="strength-bar" data-level="4"></div>
                                </div>
                                <div style="font-size: 12px; color: #546e7a;" id="strengthText">
                                    Minimal 8 karakter dengan kombinasi huruf dan angka
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i> Konfirmasi Password
                            </label>
                            <div style="position: relative;">
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ulangi password Anda..." required autocomplete="new-password"
                                    id="confirmPasswordInput">
                                <button type="button"
                                    onclick="togglePassword('confirmPasswordInput', 'confirmPasswordToggle')"
                                    style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #546e7a; cursor: pointer;">
                                    <i class="fas fa-eye" id="confirmPasswordToggle"></i>
                                </button>
                            </div>
                            <div style="font-size: 12px; margin-top: 5px;" id="passwordMatch">
                                <!-- Password match indicator will be shown here -->
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div style="margin-bottom: 25px;">
                            <label
                                style="display: flex; align-items: start; cursor: pointer; color: #546e7a; line-height: 1.5;">
                                <input type="checkbox" name="terms" required
                                    style="margin-right: 10px; margin-top: 3px; width: 16px; height: 16px;">
                                <span style="font-size: 14px;">
                                    Saya setuju dengan
                                    <a href="#" style="color: #42a5f5; text-decoration: none;">Syarat dan
                                        Ketentuan</a>
                                    serta
                                    <a href="#" style="color: #42a5f5; text-decoration: none;">Kebijakan Privasi</a>
                                    yang berlaku
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success"
                            style="width: 100%; padding: 15px; font-size: 16px; margin-bottom: 20px;" id="submitBtn">
                            <i class="fas fa-user-plus"></i> Daftar Akun Gratis
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

                    <!-- Login Link -->
                    <div style="text-align: center;">
                        <p style="color: #546e7a; margin-bottom: 15px;">Sudah punya akun?</p>
                        <a href="{{ route('login') }}" class="btn btn-outline" style="width: 100%; padding: 12px;">
                            <i class="fas fa-sign-in-alt"></i> Masuk ke Akun Anda
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

    <!-- Benefits Section -->
    <div
        style="background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%); padding: 40px 20px; margin-top: 40px; border-radius: 15px;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h3 style="color: #388e3c; margin-bottom: 30px; font-size: 24px;">
                <i class="fas fa-gifts"></i> Keuntungan Bergabung dengan Kami
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px;">
                <div style="text-align: center;">
                    <div style="font-size: 40px; color: #4caf50; margin-bottom: 15px;">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h5 style="color: #2c3e50; margin-bottom: 10px;">Akses Unlimited</h5>
                    <p style="color: #546e7a; font-size: 14px; margin: 0;">
                        Baca ribuan buku gratis tanpa batasan waktu atau kuota
                    </p>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 40px; color: #2196f3; margin-bottom: 15px;">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h5 style="color: #2c3e50; margin-bottom: 10px;">Rekomendasi AI</h5>
                    <p style="color: #546e7a; font-size: 14px; margin: 0;">
                        Dapatkan saran buku personal dengan teknologi K-means clustering
                    </p>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 40px; color: #ff9800; margin-bottom: 15px;">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <h5 style="color: #2c3e50; margin-bottom: 10px;">Bookmark & History</h5>
                    <p style="color: #546e7a; font-size: 14px; margin: 0;">
                        Simpan buku favorit dan lanjutkan dari halaman terakhir
                    </p>
                </div>

                <div style="text-align: center;">
                    <div style="font-size: 40px; color: #e91e63; margin-bottom: 15px;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h5 style="color: #2c3e50; margin-bottom: 10px;">100% Gratis</h5>
                    <p style="color: #546e7a; font-size: 14px; margin: 0;">
                        Tidak ada biaya tersembunyi atau langganan berbayar
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .strength-bar {
            height: 4px;
            flex: 1;
            background: #e0e0e0;
            border-radius: 2px;
            transition: background-color 0.3s ease;
        }

        .strength-bar.active-1 {
            background: #f44336;
        }

        .strength-bar.active-2 {
            background: #ff9800;
        }

        .strength-bar.active-3 {
            background: #2196f3;
        }

        .strength-bar.active-4 {
            background: #4caf50;
        }

        .form-control.is-invalid {
            border-color: #ef5350 !important;
            box-shadow: 0 0 0 3px rgba(239, 83, 80, 0.1) !important;
        }

        .form-control.is-valid {
            border-color: #4caf50 !important;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1) !important;
        }

        @media (max-width: 480px) {
            .card {
                margin: 0 10px;
            }

            .card>div:first-child,
            .card>div:last-child {
                padding: 30px 20px;
            }

            .benefits-section {
                margin: 0 10px;
                padding: 30px 20px;
            }
        }
    </style>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const passwordToggle = document.getElementById(toggleId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordToggle.className = 'fas fa-eye';
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const checks = [
                /.{8,}/, // At least 8 characters
                /[a-z]/, // Lowercase letter
                /[A-Z]/, // Uppercase letter
                /[0-9]/, // Number
                /[^A-Za-z0-9]/ // Special character
            ];

            checks.forEach(check => {
                if (check.test(password)) strength++;
            });

            return Math.min(strength, 4);
        }

        // Update password strength indicator
        function updatePasswordStrength() {
            const password = document.getElementById('passwordInput').value;
            const strength = checkPasswordStrength(password);
            const strengthBars = document.querySelectorAll('.strength-bar');
            const strengthText = document.getElementById('strengthText');

            // Reset all bars
            strengthBars.forEach(bar => {
                bar.className = 'strength-bar';
            });

            // Activate bars based on strength
            for (let i = 0; i < strength; i++) {
                strengthBars[i].classList.add(`active-${strength}`);
            }

            // Update text
            const strengthTexts = [
                'Password terlalu lemah',
                'Password lemah',
                'Password cukup',
                'Password kuat',
                'Password sangat kuat'
            ];

            if (password.length > 0) {
                strengthText.textContent = strengthTexts[strength];
                strengthText.style.color = strength < 2 ? '#f44336' : strength < 3 ? '#ff9800' : '#4caf50';
            } else {
                strengthText.textContent = 'Minimal 8 karakter dengan kombinasi huruf dan angka';
                strengthText.style.color = '#546e7a';
            }
        }

        // Check password match
        function checkPasswordMatch() {
            const password = document.getElementById('passwordInput').value;
            const confirmPassword = document.getElementById('confirmPasswordInput').value;
            const matchIndicator = document.getElementById('passwordMatch');

            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchIndicator.innerHTML = '<i class="fas fa-check-circle" style="color: #4caf50;"></i> Password cocok';
                    matchIndicator.style.color = '#4caf50';
                    document.getElementById('confirmPasswordInput').classList.remove('is-invalid');
                    document.getElementById('confirmPasswordInput').classList.add('is-valid');
                } else {
                    matchIndicator.innerHTML =
                        '<i class="fas fa-exclamation-circle" style="color: #f44336;"></i> Password tidak cocok';
                    matchIndicator.style.color = '#f44336';
                    document.getElementById('confirmPasswordInput').classList.remove('is-valid');
                    document.getElementById('confirmPasswordInput').classList.add('is-invalid');
                }
            } else {
                matchIndicator.innerHTML = '';
                document.getElementById('confirmPasswordInput').classList.remove('is-valid', 'is-invalid');
            }
        }

        // Form validation
        function validateForm() {
            const name = document.querySelector('input[name="name"]').value.trim();
            const email = document.querySelector('input[name="email"]').value.trim();
            const password = document.getElementById('passwordInput').value;
            const confirmPassword = document.getElementById('confirmPasswordInput').value;
            const terms = document.querySelector('input[name="terms"]').checked;

            let isValid = true;

            // Validate name
            if (name.length < 2) {
                showFieldError('name', 'Nama harus minimal 2 karakter');
                isValid = false;
            }

            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showFieldError('email', 'Format email tidak valid');
                isValid = false;
            }

            // Validate password strength
            if (checkPasswordStrength(password) < 2) {
                showFieldError('password', 'Password terlalu lemah');
                isValid = false;
            }

            // Validate password match
            if (password !== confirmPassword) {
                showFieldError('password_confirmation', 'Password tidak cocok');
                isValid = false;
            }

            // Validate terms
            if (!terms) {
                alert('Anda harus menyetujui syarat dan ketentuan');
                isValid = false;
            }

            return isValid;
        }

        function showFieldError(fieldName, message) {
            const field = document.querySelector(`input[name="${fieldName}"]`);
            field.classList.add('is-invalid');

            // Remove existing error message
            const existingError = field.parentNode.querySelector('.error-message');
            if (existingError) existingError.remove();

            // Add new error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.cssText = 'color: #ef5350; font-size: 14px; margin-top: 5px;';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            field.parentNode.appendChild(errorDiv);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('passwordInput');
            const confirmPasswordInput = document.getElementById('confirmPasswordInput');

            // Password strength checking
            passwordInput.addEventListener('input', updatePasswordStrength);

            // Password match checking
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            passwordInput.addEventListener('input', checkPasswordMatch);

            // Form submission
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }

                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendaftarkan Akun...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 10000);
            });

            // Remove error states on input
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    const errorMessage = this.parentNode.querySelector('.error-message');
                    if (errorMessage) errorMessage.remove();
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

        // Email availability check (optional enhancement)
        let emailCheckTimeout;

        function checkEmailAvailability(email) {
            clearTimeout(emailCheckTimeout);
            emailCheckTimeout = setTimeout(() => {
                if (email.length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    // Implementation for checking email availability
                    // This would require a backend endpoint
                }
            }, 1000);
        }
    </script>
@endsection
