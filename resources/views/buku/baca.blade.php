<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $buku->judul }} - Perpustakaan Digital</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #1a1a1a;
            --secondary-color: #2d2d2d;
            --accent-color: #007bff;
            --text-color: #e0e0e0;
            --text-secondary: #b0b0b0;
            --border-color: #444;
            --dark-bg: #121212;
            --darker-bg: #0a0a0a;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--dark-bg);
            color: var(--text-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Reader Header */
        .reader-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }

        .reader-header.hidden {
            transform: translateY(-100%);
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .back-btn {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 18px;
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            margin-right: 20px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        .book-title {
            font-size: 18px;
            color: var(--text-color);
            font-weight: 600;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-btn {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-btn:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        .header-btn.active {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* PDF Viewer Container */
        .pdf-container {
            margin-top: 70px;
            margin-bottom: 120px;
            display: flex;
            justify-content: center;
            min-height: calc(100vh - 190px);
            background: var(--darker-bg);
            padding: 20px;
        }

        .pdf-viewer {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            position: relative;
        }

        #pdf-canvas {
            display: block;
            width: 100%;
            height: auto;
            background: white;
        }

        .pdf-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 600px;
            background: white;
            flex-direction: column;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e3e3e3;
            border-top: 4px solid var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .error-message {
            text-align: center;
            color: #dc3545;
            padding: 40px;
            background: white;
            border-radius: 8px;
            margin: 20px;
        }

        /* Reading Controls */
        .reading-controls {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-top: 1px solid var(--border-color);
            padding: 20px;
            z-index: 1000;
            box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }

        .reading-controls.hidden {
            transform: translateY(100%);
        }

        .controls-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .page-navigation {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-btn {
            background: linear-gradient(135deg, var(--accent-color) 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .nav-btn:disabled {
            background: var(--secondary-color);
            color: var(--text-secondary);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .page-info {
            background: var(--secondary-color);
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            color: var(--text-color);
            font-weight: 500;
            min-width: 120px;
            text-align: center;
        }

        .progress-container {
            flex: 1;
            max-width: 300px;
            margin: 0 20px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--secondary-color);
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid var(--border-color);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, var(--accent-color) 0%, #0056b3 100%);
            transition: width 0.3s ease;
            border-radius: 4px;
        }

        .reading-tools {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tool-btn {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tool-btn:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        .tool-btn.active {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* Settings Panel */
        .settings-panel {
            position: fixed;
            top: 70px;
            right: -350px;
            width: 350px;
            height: calc(100vh - 70px);
            background: var(--primary-color);
            border-left: 1px solid var(--border-color);
            padding: 30px;
            z-index: 999;
            transition: right 0.3s ease;
            overflow-y: auto;
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.5);
        }

        .settings-panel.open {
            right: 0;
        }

        .settings-group {
            margin-bottom: 30px;
        }

        .settings-title {
            font-size: 16px;
            color: var(--text-color);
            margin-bottom: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        .setting-item {
            margin-bottom: 15px;
        }

        .setting-label {
            display: block;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .setting-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--secondary-color);
            color: var(--text-color);
        }

        .setting-control:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .zoom-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .zoom-btn {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .zoom-btn:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        .zoom-display {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            padding: 8px 15px;
            border-radius: 6px;
            min-width: 80px;
            text-align: center;
            color: var(--text-color);
            font-weight: 500;
        }

        /* Bookmarks Panel */
        .bookmarks-panel {
            position: fixed;
            top: 70px;
            left: -350px;
            width: 350px;
            height: calc(100vh - 70px);
            background: var(--primary-color);
            border-right: 1px solid var(--border-color);
            padding: 30px;
            z-index: 999;
            transition: left 0.3s ease;
            overflow-y: auto;
            box-shadow: 5px 0 20px rgba(0, 0, 0, 0.5);
        }

        .bookmarks-panel.open {
            left: 0;
        }

        .bookmark-item {
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
            background: var(--secondary-color);
        }

        .bookmark-item:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        .bookmark-page {
            font-weight: 600;
            color: var(--accent-color);
            margin-bottom: 5px;
        }

        .bookmark-preview {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        /* Page Input */
        .page-input-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-input {
            width: 80px;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: var(--secondary-color);
            color: var(--text-color);
            text-align: center;
        }

        .go-btn {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .controls-container {
                flex-direction: column;
                gap: 15px;
            }

            .progress-container {
                max-width: 100%;
                order: -1;
                margin: 0;
            }

            .settings-panel,
            .bookmarks-panel {
                width: 100%;
                left: -100%;
                right: -100%;
            }

            .reading-controls {
                padding: 15px;
            }

            .book-title {
                max-width: 200px;
            }

            .pdf-container {
                padding: 10px;
            }

            .header-right {
                gap: 10px;
            }

            .header-btn span {
                display: none;
            }
        }

        /* Fullscreen mode */
        .fullscreen-mode .reader-header {
            display: none;
        }

        .fullscreen-mode .reading-controls {
            display: none;
        }

        .fullscreen-mode .pdf-container {
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        /* Theme variations */
        .theme-sepia {
            --dark-bg: #f4f1ea;
            --primary-color: #8b7355;
            --secondary-color: #a0956b;
            --text-color: #5c4b37;
            --text-secondary: #7a6b57;
        }

        .theme-sepia .pdf-viewer {
            filter: sepia(0.3);
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: var(--accent-color);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }
    </style>

    <!-- PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div>
            <div class="loading-spinner"></div>
            <p style="color: white; margin-top: 20px;">Memuat buku...</p>
        </div>
    </div>

    <!-- Reader Header -->
    <header class="reader-header" id="readerHeader">
        <div class="header-left">
            <button class="back-btn" onclick="goBack()" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="book-title">{{ $buku->judul }}</h1>
        </div>

        <div class="header-right">
            <button class="header-btn" onclick="toggleBookmarks()" title="Bookmark">
                <i class="fas fa-bookmark"></i>
                <span>Bookmark</span>
            </button>
            <button class="header-btn" onclick="toggleSettings()" title="Pengaturan">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </button>
            <button class="header-btn" onclick="toggleFullscreen()" title="Fullscreen" id="fullscreenBtn">
                <i class="fas fa-expand"></i>
                <span>Fullscreen</span>
            </button>
        </div>
    </header>

    <!-- Bookmarks Panel -->
    <div class="bookmarks-panel" id="bookmarksPanel">
        <div class="settings-title">
            <i class="fas fa-bookmark"></i> Bookmark Halaman
        </div>
        <button onclick="addBookmark()" style="width: 100%; margin-bottom: 20px;" class="nav-btn">
            <i class="fas fa-plus"></i> Tandai Halaman Ini
        </button>
        <div id="bookmarksList">
            <!-- Bookmarks will be loaded here -->
        </div>
    </div>

    <!-- Settings Panel -->
    <div class="settings-panel" id="settingsPanel">
        <div class="settings-group">
            <div class="settings-title">
                <i class="fas fa-search"></i> Kontrol Zoom
            </div>

            <div class="setting-item">
                <label class="setting-label">Zoom Level</label>
                <div class="zoom-controls">
                    <button class="zoom-btn" onclick="changeZoom(-0.2)">-</button>
                    <div class="zoom-display" id="zoomDisplay">100%</div>
                    <button class="zoom-btn" onclick="changeZoom(0.2)">+</button>
                </div>
            </div>

            <div class="setting-item">
                <button class="setting-control" onclick="fitToWidth()"
                    style="background: var(--accent-color); color: white;">
                    <i class="fas fa-arrows-alt-h"></i> Sesuaikan Lebar
                </button>
            </div>

            <div class="setting-item">
                <button class="setting-control" onclick="fitToPage()"
                    style="background: var(--accent-color); color: white;">
                    <i class="fas fa-expand-arrows-alt"></i> Sesuaikan Halaman
                </button>
            </div>
        </div>

        <div class="settings-group">
            <div class="settings-title">
                <i class="fas fa-palette"></i> Tema Bacaan
            </div>

            <div class="setting-item">
                <button class="setting-control" onclick="changeTheme('default')"
                    style="background: #333; color: #e0e0e0; border: 2px solid var(--border-color);">
                    <i class="fas fa-moon"></i> Gelap (Default)
                </button>
            </div>

            <div class="setting-item">
                <button class="setting-control" onclick="changeTheme('sepia')"
                    style="background: #f4f1ea; color: #5c4b37; border: 2px solid #d4c5a9;">
                    <i class="fas fa-leaf"></i> Sepia
                </button>
            </div>
        </div>

        <div class="settings-group">
            <div class="settings-title">
                <i class="fas fa-mouse-pointer"></i> Navigasi
            </div>

            <div class="setting-item">
                <label class="setting-label">Langsung ke Halaman</label>
                <div class="page-input-container">
                    <input type="number" class="page-input" id="pageInput" min="1" max="{{ $buku->halaman }}"
                        placeholder="No">
                    <button class="go-btn" onclick="goToInputPage()">Go</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Container -->
    <main class="pdf-container">
        <div class="pdf-viewer">
            <div class="pdf-loading" id="pdfLoading">
                <div class="loading-spinner"></div>
                <p>Memuat halaman PDF...</p>
            </div>
            <canvas id="pdf-canvas"></canvas>
            <div class="error-message" id="errorMessage" style="display: none;">
                <i class="fas fa-exclamation-triangle"
                    style="font-size: 48px; color: #dc3545; margin-bottom: 15px;"></i>
                <h3>Gagal Memuat PDF</h3>
                <p>Terjadi kesalahan saat memuat file PDF. Silakan coba lagi atau hubungi administrator.</p>
                <button onclick="retryLoadPdf()" class="nav-btn" style="margin-top: 15px;">
                    <i class="fas fa-redo"></i> Coba Lagi
                </button>
            </div>
        </div>
    </main>

    <!-- Reading Controls -->
    <footer class="reading-controls" id="readingControls">
        <div class="controls-container">
            <div class="page-navigation">
                <button class="nav-btn" onclick="previousPage()" id="prevBtn" disabled>
                    <i class="fas fa-chevron-left"></i>
                    <span>Sebelumnya</span>
                </button>
            </div>

            <div class="progress-container">
                <div class="progress-bar" onclick="goToProgress(event)">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
            </div>

            <div class="page-info" id="pageInfo">
                Hal. {{ $halaman }} dari {{ $buku->halaman }}
            </div>

            <div class="page-navigation">
                <button class="nav-btn" onclick="nextPage()" id="nextBtn">
                    <span>Selanjutnya</span>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="reading-tools">
                <button class="tool-btn" onclick="toggleAutoScroll()" id="autoScrollBtn" title="Auto Scroll">
                    <i class="fas fa-play"></i>
                </button>
                <button class="tool-btn" onclick="rotateDocument()" id="rotateBtn" title="Putar">
                    <i class="fas fa-redo"></i>
                </button>
            </div>
        </div>
    </footer>

    <script>
        // Include Bookmark Manager
        $ {
            bookmarkManager
        }

        // PDF.js setup
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        // Reading state
        let currentPage = {{ $halaman }};
        let totalPages = {{ $buku->halaman }};
        let bookId = {{ $buku->id }};
        let pdfDoc = null;
        let scale = 1.5;
        let rotation = 0;
        let autoScrollInterval = null;
        let hideControlsTimeout = null;

        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');

        // Initialize reader
        document.addEventListener('DOMContentLoaded', function() {
            loadPDF();
            updateProgress();
            initializeBookmarkManager();
            setupAutoHideControls();
            setupKeyboardShortcuts();
            loadUserPreferences();
            trackBookView();
        });

        // Load PDF
        async function loadPDF() {
            showLoading();

            try {
                const pdfUrl = '{{ Storage::url($buku->file_path) }}';
                console.log('Loading PDF from:', pdfUrl);

                pdfDoc = await pdfjsLib.getDocument(pdfUrl).promise;
                totalPages = pdfDoc.numPages;

                // Update total pages if different from database
                if (totalPages !== {{ $buku->halaman }}) {
                    console.log(`PDF has ${totalPages} pages, database shows {{ $buku->halaman }}`);
                }

                await renderPage(currentPage);
                hideLoading();

                // Update page controls
                updatePageControls();

                // Save reading progress
                saveProgress();

            } catch (error) {
                console.error('Error loading PDF:', error);
                hideLoading();
                showError();
            }
        }

        // Render specific page
        async function renderPage(pageNum) {
            if (!pdfDoc) return;

            try {
                document.getElementById('pdfLoading').style.display = 'flex';

                const page = await pdfDoc.getPage(pageNum);
                const viewport = page.getViewport({
                    scale: scale,
                    rotation: rotation
                });

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                await page.render(renderContext).promise;
                document.getElementById('pdfLoading').style.display = 'none';

                // Update page input max value
                document.getElementById('pageInput').max = totalPages;

            } catch (error) {
                console.error('Error rendering page:', error);
                document.getElementById('pdfLoading').style.display = 'none';
                showError();
            }
        }

        // Navigation functions
        async function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                await renderPage(currentPage);
                updatePageControls();
                saveProgress();
            }
        }

        async function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                await renderPage(currentPage);
                updatePageControls();
                saveProgress();
            }
        }

        async function goToPage(page) {
            if (page >= 1 && page <= totalPages && page !== currentPage) {
                currentPage = page;
                await renderPage(currentPage);
                updatePageControls();
                saveProgress();
            }
        }

        function goToInputPage() {
            const pageInput = document.getElementById('pageInput');
            const page = parseInt(pageInput.value);
            if (page && page >= 1 && page <= totalPages) {
                goToPage(page);
                pageInput.value = '';
            }
        }

        function updatePageControls() {
            document.getElementById('prevBtn').disabled = currentPage <= 1;
            document.getElementById('nextBtn').disabled = currentPage >= totalPages;
            document.getElementById('pageInfo').textContent = `Hal. ${currentPage} dari ${totalPages}`;
            updateProgress();
        }

        function updateProgress() {
            const progress = (currentPage / totalPages) * 100;
            document.getElementById('progressFill').style.width = progress + '%';
        }

        function goToProgress(event) {
            const progressBar = event.currentTarget;
            const rect = progressBar.getBoundingClientRect();
            const clickX = event.clientX - rect.left;
            const progress = clickX / rect.width;
            const targetPage = Math.max(1, Math.min(totalPages, Math.round(progress * totalPages)));
            goToPage(targetPage);
        }

        // Zoom functions
        function changeZoom(delta) {
            scale = Math.max(0.5, Math.min(3.0, scale + delta));
            document.getElementById('zoomDisplay').textContent = Math.round(scale * 100) + '%';
            renderPage(currentPage);
            saveUserPreferences();
        }

        function fitToWidth() {
            const container = document.querySelector('.pdf-viewer');
            scale = (container.clientWidth - 40) / canvas.width * scale;
            document.getElementById('zoomDisplay').textContent = Math.round(scale * 100) + '%';
            renderPage(currentPage);
            saveUserPreferences();
        }

        function fitToPage() {
            const container = document.querySelector('.pdf-viewer');
            const scaleX = (container.clientWidth - 40) / canvas.width * scale;
            const scaleY = (container.clientHeight - 40) / canvas.height * scale;
            scale = Math.min(scaleX, scaleY);
            document.getElementById('zoomDisplay').textContent = Math.round(scale * 100) + '%';
            renderPage(currentPage);
            saveUserPreferences();
        }

        function rotateDocument() {
            rotation = (rotation + 90) % 360;
            renderPage(currentPage);
        }

        // Save progress to server
        function saveProgress() {
            fetch(`/api/buku/${bookId}/update-progress`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    halaman_terakhir: currentPage
                })
            }).catch(error => {
                console.error('Error saving progress:', error);
            });
        }

        // Settings functions
        function toggleSettings() {
            const panel = document.getElementById('settingsPanel');
            panel.classList.toggle('open');
            closeBookmarks();
        }

        function toggleBookmarks() {
            const panel = document.getElementById('bookmarksPanel');
            panel.classList.toggle('open');
            closeSettings();
        }

        function closeSettings() {
            document.getElementById('settingsPanel').classList.remove('open');
        }

        function closeBookmarks() {
            document.getElementById('bookmarksPanel').classList.remove('open');
        }

        function changeTheme(theme) {
            document.body.className = theme !== 'default' ? 'theme-' + theme : '';
            saveUserPreferences();
        }

        // Auto scroll functionality
        function toggleAutoScroll() {
            const btn = document.getElementById('autoScrollBtn');

            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
                autoScrollInterval = null;
                btn.innerHTML = '<i class="fas fa-play"></i>';
                btn.classList.remove('active');
            } else {
                autoScrollInterval = setInterval(() => {
                    const container = document.querySelector('.pdf-container');
                    container.scrollBy(0, 2);
                }, 50);
                btn.innerHTML = '<i class="fas fa-pause"></i>';
                btn.classList.add('active');
            }
        }

        // Fullscreen functionality
        function toggleFullscreen() {
            const btn = document.getElementById('fullscreenBtn');

            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    btn.innerHTML = '<i class="fas fa-compress"></i><span>Exit Fullscreen</span>';
                    document.body.classList.add('fullscreen-mode');
                });
            } else {
                document.exitFullscreen().then(() => {
                    btn.innerHTML = '<i class="fas fa-expand"></i><span>Fullscreen</span>';
                    document.body.classList.remove('fullscreen-mode');
                });
            }
        }

        // Enhanced addBookmark function
        function addBookmark() {
            showBookmarkModal();
        }

        // Enhanced loadBookmarks function
        function loadBookmarks() {
            if (bookmarkManager) {
                bookmarkManager.loadBookmarks();
            }
        }

        // Auto-hide controls
        function setupAutoHideControls() {
            let isMoving = false;

            document.addEventListener('mousemove', function() {
                if (!isMoving) {
                    showControls();
                    isMoving = true;

                    clearTimeout(hideControlsTimeout);
                    hideControlsTimeout = setTimeout(() => {
                        hideControls();
                        isMoving = false;
                    }, 3000);
                }
            });

            document.addEventListener('scroll', showControls);
            document.addEventListener('click', showControls);
        }

        function showControls() {
            document.getElementById('readerHeader').classList.remove('hidden');
            document.getElementById('readingControls').classList.remove('hidden');
        }

        function hideControls() {
            if (!document.getElementById('settingsPanel').classList.contains('open') &&
                !document.getElementById('bookmarksPanel').classList.contains('open')) {
                document.getElementById('readerHeader').classList.add('hidden');
                document.getElementById('readingControls').classList.add('hidden');
            }
        }

        // Keyboard shortcuts
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                // Prevent default for arrow keys and space to avoid page scrolling
                if (['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', ' '].includes(e.key)) {
                    e.preventDefault();
                }

                switch (e.key) {
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        previousPage();
                        break;
                    case 'ArrowRight':
                    case 'ArrowDown':
                    case ' ':
                        nextPage();
                        break;
                    case '+':
                    case '=':
                        changeZoom(0.2);
                        break;
                    case '-':
                        changeZoom(-0.2);
                        break;
                    case '0':
                        scale = 1.5;
                        document.getElementById('zoomDisplay').textContent = '150%';
                        renderPage(currentPage);
                        break;
                    case 'f':
                    case 'F':
                        if (e.ctrlKey) {
                            e.preventDefault();
                            toggleFullscreen();
                        }
                        break;
                    case 's':
                    case 'S':
                        if (e.ctrlKey) {
                            e.preventDefault();
                            toggleSettings();
                        }
                        break;
                    case 'b':
                    case 'B':
                        if (e.ctrlKey) {
                            e.preventDefault();
                            toggleBookmarks();
                        }
                        break;
                    case 'Escape':
                        closeSettings();
                        closeBookmarks();
                        if (document.fullscreenElement) {
                            document.exitFullscreen();
                        }
                        break;
                    case 'Home':
                        goToPage(1);
                        break;
                    case 'End':
                        goToPage(totalPages);
                        break;
                }
            });
        }

        // User preferences
        function saveUserPreferences() {
            const preferences = {
                scale: scale,
                rotation: rotation,
                theme: document.body.className
            };

            localStorage.setItem('pdfReaderPreferences', JSON.stringify(preferences));
        }

        function loadUserPreferences() {
            const saved = localStorage.getItem('pdfReaderPreferences');
            if (saved) {
                const preferences = JSON.parse(saved);

                if (preferences.scale) {
                    scale = preferences.scale;
                    document.getElementById('zoomDisplay').textContent = Math.round(scale * 100) + '%';
                }

                if (preferences.rotation) {
                    rotation = preferences.rotation;
                }

                if (preferences.theme) {
                    document.body.className = preferences.theme;
                }
            }
        }

        // Utility functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('show');
        }

        function showError() {
            document.getElementById('pdfLoading').style.display = 'none';
            document.getElementById('errorMessage').style.display = 'block';
        }

        function retryLoadPdf() {
            document.getElementById('errorMessage').style.display = 'none';
            loadPDF();
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = 'notification show';
            notification.style.background = type === 'error' ? '#dc3545' : 'var(--accent-color)';
            notification.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
                ${message}
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        function goBack() {
            if (document.referrer) {
                window.history.back();
            } else {
                window.location.href = `/buku/${bookId}`;
            }
        }

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchStartY = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        });

        document.addEventListener('touchend', function(e) {
            if (!touchStartX || !touchStartY) {
                return;
            }

            const touchEndX = e.changedTouches[0].clientX;
            const touchEndY = e.changedTouches[0].clientY;

            const diffX = touchStartX - touchEndX;
            const diffY = touchStartY - touchEndY;

            // Only process horizontal swipes
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    // Swipe left - next page
                    nextPage();
                } else {
                    // Swipe right - previous page
                    previousPage();
                }
            }

            touchStartX = 0;
            touchStartY = 0;
        });

        // Track book view for analytics
        function trackBookView() {
            fetch(`/api/buku/${bookId}/view`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).catch(error => {
                console.error('Error tracking view:', error);
            });
        }

        // Close panels when clicking outside
        document.addEventListener('click', function(e) {
            const settingsPanel = document.getElementById('settingsPanel');
            const bookmarksPanel = document.getElementById('bookmarksPanel');

            if (!settingsPanel.contains(e.target) && !e.target.closest('[onclick="toggleSettings()"]')) {
                closeSettings();
            }

            if (!bookmarksPanel.contains(e.target) && !e.target.closest('[onclick="toggleBookmarks()"]')) {
                closeBookmarks();
            }
        });

        // Handle page visibility change
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                saveProgress();
                if (autoScrollInterval) {
                    clearInterval(autoScrollInterval);
                }
            }
        });

        // Save progress when leaving page
        window.addEventListener('beforeunload', function() {
            saveProgress();
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (pdfDoc && currentPage) {
                // Re-render current page to fit new window size
                setTimeout(() => {
                    renderPage(currentPage);
                }, 100);
            }
        });

        // Double-click to toggle fullscreen
        document.getElementById('pdf-canvas').addEventListener('dblclick', function() {
            toggleFullscreen();
        });

        // Mouse wheel zoom (with Ctrl key)
        document.addEventListener('wheel', function(e) {
            if (e.ctrlKey) {
                e.preventDefault();
                const delta = e.deltaY > 0 ? -0.1 : 0.1;
                changeZoom(delta);
            }
        });

        // Page input enter key
        document.getElementById('pageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                goToInputPage();
            }
        });

        // Error handling for PDF.js
        window.addEventListener('error', function(e) {
            if (e.message && e.message.includes('PDF')) {
                console.error('PDF Error:', e);
                showError();
            }
        });

        // Add bookmark manager script
        // (Already included above)

        // Performance optimization: Preload next page
        function preloadNextPage() {
            if (pdfDoc && currentPage < totalPages) {
                pdfDoc.getPage(currentPage + 1).then(page => {
                    // Page is now cached
                }).catch(error => {
                    console.log('Preload failed:', error);
                });
            }
        }

        // Call preload after rendering current page
        function renderPageWithPreload(pageNum) {
            renderPage(pageNum).then(() => {
                preloadNextPage();
            });
        }
    </script>
</body>

</html>
