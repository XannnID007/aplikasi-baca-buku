<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital') - Baca Buku Gratis</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
            color: #2c3e50;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);
            box-shadow: 0 2px 20px rgba(66, 165, 245, 0.1);
            border-bottom: 1px solid rgba(66, 165, 245, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #1976d2;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-brand i {
            margin-right: 10px;
            font-size: 28px;
        }

        .navbar-menu {
            display: flex;
            list-style: none;
            align-items: center;
        }

        .navbar-menu li {
            margin-left: 30px;
        }

        .navbar-menu a {
            text-decoration: none;
            color: #546e7a;
            font-weight: 500;
            transition: all 0.3s;
            padding: 8px 16px;
            border-radius: 20px;
        }

        .navbar-menu a:hover,
        .navbar-menu a.active {
            color: #1976d2;
            background: rgba(25, 118, 210, 0.1);
        }

        .navbar-auth {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: #1976d2;
            border: 2px solid #1976d2;
        }

        .btn-outline:hover {
            background: #1976d2;
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #66bb6a 0%, #388e3c 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef5350 0%, #d32f2f 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffca28 0%, #f57c00 100%);
            color: #2c3e50;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .hero {
            text-align: center;
            padding: 80px 20px;
            background: linear-gradient(135deg, #ffffff 0%, #e3f2fd 100%);
            border-radius: 20px;
            margin-bottom: 60px;
            box-shadow: 0 10px 40px rgba(66, 165, 245, 0.1);
        }

        .hero h1 {
            font-size: 48px;
            color: #1976d2;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .hero p {
            font-size: 18px;
            color: #546e7a;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(66, 165, 245, 0.1);
            border: 1px solid rgba(66, 165, 245, 0.1);
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(66, 165, 245, 0.2);
        }

        .card-header {
            border-bottom: 2px solid #e3f2fd;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .card-title {
            font-size: 24px;
            color: #1976d2;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .card-subtitle {
            color: #546e7a;
            font-size: 16px;
        }

        /* Book Grid */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .book-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(66, 165, 245, 0.1);
            transition: all 0.3s;
            border: 1px solid rgba(66, 165, 245, 0.1);
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(66, 165, 245, 0.2);
        }

        .book-cover {
            height: 200px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            position: relative;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-cover .placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 48px;
            color: #42a5f5;
        }

        .book-info {
            padding: 20px;
        }

        .book-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .book-author {
            color: #546e7a;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .book-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 12px;
            color: #78909c;
        }

        .book-rating {
            display: flex;
            align-items: center;
        }

        .book-rating i {
            color: #ffc107;
            margin-right: 2px;
        }

        .book-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 15px;
        }

        .category-tag {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1976d2;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        .book-actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 20px;
        }

        /* Forms */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e3f2fd;
            border-radius: 10px;
            background: white;
            color: #2c3e50;
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #42a5f5;
            box-shadow: 0 0 0 3px rgba(66, 165, 245, 0.1);
        }

        .form-control::placeholder {
            color: #b0bec5;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto 40px;
        }

        .search-input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #e3f2fd;
            border-radius: 25px;
            font-size: 16px;
            background: white;
            transition: all 0.3s;
        }

        .search-input:focus {
            border-color: #42a5f5;
            box-shadow: 0 0 0 3px rgba(66, 165, 245, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: #42a5f5;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-btn:hover {
            background: #1976d2;
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid;
            display: flex;
            align-items: center;
        }

        .alert i {
            margin-right: 10px;
            font-size: 18px;
        }

        .alert-success {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            border-left-color: #4caf50;
            color: #2e7d32;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            border-left-color: #f44336;
            color: #c62828;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
            border-left-color: #ff9800;
            color: #ef6c00;
        }

        .alert-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left-color: #2196f3;
            color: #1565c0;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .pagination a,
        .pagination span {
            padding: 12px 16px;
            margin: 0 5px;
            color: #546e7a;
            text-decoration: none;
            border: 2px solid #e3f2fd;
            border-radius: 10px;
            background: white;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: #42a5f5;
            border-color: #42a5f5;
            color: white;
        }

        .pagination .active span {
            background: #42a5f5;
            border-color: #42a5f5;
            color: white;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);
            border-top: 1px solid rgba(66, 165, 245, 0.1);
            padding: 60px 0 30px;
            margin-top: 80px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        .footer-content h3 {
            color: #1976d2;
            margin-bottom: 15px;
            font-size: 24px;
        }

        .footer-content p {
            color: #546e7a;
            margin-bottom: 30px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .footer-links a {
            color: #546e7a;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #1976d2;
        }

        .footer-bottom {
            border-top: 1px solid rgba(66, 165, 245, 0.1);
            padding-top: 20px;
            color: #78909c;
            font-size: 14px;
        }

        /* Choice Cards */
        .choice-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 40px 0;
        }

        .choice-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(66, 165, 245, 0.1);
            border: 2px solid transparent;
            transition: all 0.3s;
            cursor: pointer;
            min-width: 250px;
        }

        .choice-card:hover {
            transform: translateY(-8px);
            border-color: #42a5f5;
            box-shadow: 0 20px 60px rgba(66, 165, 245, 0.2);
        }

        .choice-card.selected {
            border-color: #42a5f5;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }

        .choice-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #42a5f5;
        }

        .choice-title {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .choice-description {
            color: #546e7a;
            line-height: 1.6;
        }

        /* Category Selection */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .category-checkbox {
            position: relative;
        }

        .category-checkbox input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .category-label {
            display: block;
            background: white;
            border: 2px solid #e3f2fd;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .category-checkbox input:checked+.category-label {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-color: #42a5f5;
            color: #1976d2;
            font-weight: 600;
        }

        .category-label:hover {
            border-color: #42a5f5;
            transform: translateY(-2px);
        }

        .category-icon {
            font-size: 32px;
            margin-bottom: 10px;
            color: #42a5f5;
        }

        /* Reading Interface */
        .reading-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(66, 165, 245, 0.1);
        }

        .reading-header {
            background: linear-gradient(135deg, #1976d2 0%, #42a5f5 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reading-title {
            font-size: 20px;
            font-weight: 600;
        }

        .reading-controls {
            display: flex;
            gap: 10px;
        }

        .reading-content {
            padding: 40px;
            font-size: 18px;
            line-height: 1.8;
            color: #2c3e50;
        }

        .reading-navigation {
            background: #f8faff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e3f2fd;
        }

        .page-info {
            color: #546e7a;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                height: auto;
                padding: 15px 20px;
            }

            .navbar-menu {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .navbar-menu li {
                margin: 5px 10px;
            }

            .hero h1 {
                font-size: 36px;
            }

            .choice-container {
                flex-direction: column;
                align-items: center;
            }

            .category-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .book-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* Loading States */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(66, 165, 245, 0.3);
            border-radius: 50%;
            border-top-color: #42a5f5;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e3f2fd;
            padding-bottom: 15px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #546e7a;
            cursor: pointer;
        }

        /* Bookmark Button */
        .bookmark-btn {
            background: none;
            border: none;
            color: #ffc107;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .bookmark-btn:hover {
            transform: scale(1.1);
        }

        .bookmark-btn.bookmarked {
            color: #ff6b35;
        }

        /* Rating Stars */
        .rating-stars {
            display: flex;
            gap: 5px;
            margin: 10px 0;
        }

        .rating-stars i {
            font-size: 20px;
            color: #e0e0e0;
            cursor: pointer;
            transition: color 0.3s;
        }

        .rating-stars i.active,
        .rating-stars i:hover {
            color: #ffc107;
        }

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e3f2fd;
            border-radius: 4px;
            overflow: hidden;
            margin: 15px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
            transition: width 0.3s;
        }

        /* User Menu Dropdown */
        .user-menu {
            position: relative;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(66, 165, 245, 0.2);
            border: 1px solid #e3f2fd;
            min-width: 200px;
            z-index: 1000;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #546e7a;
            text-decoration: none;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background: #f8faff;
            color: #1976d2;
        }

        .dropdown-item i {
            margin-right: 10px;
            width: 16px;
        }

        .dropdown-divider {
            height: 1px;
            background: #e3f2fd;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <a href="{{ route('home') }}" class="navbar-brand">
                <i class="fas fa-book-open"></i>
                <span>Perpustakaan Digital</span>
            </a>

            <ul class="navbar-menu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                </li>
                <li><a href="{{ route('buku.index') }}"
                        class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Katalog Buku</a></li>
                <li><a href="{{ route('pilih.jenis') }}"
                        class="{{ request()->routeIs('pilih.*') ? 'active' : '' }}">Rekomendasi</a></li>
                @auth
                    <li><a href="{{ route('profile.index') }}"
                            class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">Profil</a></li>
                @endauth
            </ul>

            <div class="navbar-auth">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Daftar
                    </a>
                @else
                    <div class="user-menu">
                        <div class="user-avatar" onclick="toggleDropdown()">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="dropdown-menu" id="userDropdown">
                            <a href="{{ route('profile.index') }}" class="dropdown-item">
                                <i class="fas fa-user"></i> Profil Saya
                            </a>
                            <a href="{{ route('profile.bookmarks') }}" class="dropdown-item">
                                <i class="fas fa-bookmark"></i> Bookmark
                            </a>
                            <a href="{{ route('profile.riwayat') }}" class="dropdown-item">
                                <i class="fas fa-history"></i> Riwayat Bacaan
                            </a>
                            <a href="{{ route('profile.ratings') }}" class="dropdown-item">
                                <i class="fas fa-star"></i> Rating Saya
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item"
                                    style="width: 100%; text-align: left; border: none; background: none; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <ul style="margin: 10px 0 0 20px; list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <h3><i class="fas fa-book-open"></i> Perpustakaan Digital</h3>
            <p>Platform baca buku gratis dengan sistem rekomendasi cerdas menggunakan K-means clustering</p>

            <div class="footer-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('buku.index') }}">Katalog</a>
                <a href="#">Tentang Kami</a>
                <a href="#">Kontak</a>
                <a href="#">Bantuan</a>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Perpustakaan Digital. Dibuat untuk keperluan skripsi - Implementasi
                    K-means Clustering untuk Sistem Rekomendasi Buku.</p>
            </div>
        </div>
    </footer>

    <script>
        // Toggle user dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu');
            const dropdown = document.getElementById('userDropdown');

            if (userMenu && !userMenu.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Auto hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });

        // Book rating functionality
        function rateBook(bookId, rating) {
            fetch(`/buku/${bookId}/rating`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        rating: rating
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        showAlert('success', data.message);
                    }
                })
                .catch(error => {
                    showAlert('error', 'Terjadi kesalahan saat menyimpan rating');
                });
        }

        // Bookmark functionality
        function toggleBookmark(bookId) {
            fetch(`/buku/${bookId}/bookmark`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const bookmarkBtn = document.querySelector(`[data-book-id="${bookId}"] .bookmark-btn`);
                    if (bookmarkBtn) {
                        bookmarkBtn.classList.toggle('bookmarked', data.bookmarked);
                        bookmarkBtn.innerHTML = data.bookmarked ? '<i class="fas fa-bookmark"></i>' :
                            '<i class="far fa-bookmark"></i>';
                    }
                    showAlert('success', data.message);
                })
                .catch(error => {
                    showAlert('error', 'Terjadi kesalahan');
                });
        }

        // Show alert function
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type}">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    ${message}
                </div>
            `;

            const mainContent = document.querySelector('.main-content');
            mainContent.insertAdjacentHTML('afterbegin', alertHtml);

            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = mainContent.querySelector('.alert');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }

        // Search functionality
        function searchBooks() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput && searchInput.value.trim()) {
                window.location.href = `/buku?search=${encodeURIComponent(searchInput.value)}`;
            }
        }

        // Enter key for search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        searchBooks();
                    }
                });
            }
        });
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</body>

</html>
