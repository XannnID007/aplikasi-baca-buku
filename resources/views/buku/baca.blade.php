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

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #fafafa;
            color: #333;
            line-height: 1.7;
        }

        /* Reader Header */
        .reader-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
            border-bottom: 1px solid #e3f2fd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(66, 165, 245, 0.1);
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
            background: none;
            border: none;
            color: #42a5f5;
            font-size: 18px;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            margin-right: 15px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #e3f2fd;
        }

        .book-title {
            font-size: 18px;
            color: #2c3e50;
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
            background: none;
            border: 1px solid #e3f2fd;
            color: #42a5f5;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-btn:hover {
            background: #e3f2fd;
        }

        .header-btn.active {
            background: #42a5f5;
            color: white;
        }

        /* Reading Container */
        .reading-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 100px 20px 120px;
            min-height: 100vh;
        }

        .reading-content {
            background: white;
            padding: 60px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(66, 165, 245, 0.1);
            border: 1px solid #e3f2fd;
            font-size: 18px;
            line-height: 1.8;
            text-align: justify;
        }

        /* Reading Controls */
        .reading-controls {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
            border-top: 1px solid #e3f2fd;
            padding: 20px;
            z-index: 1000;
            box-shadow: 0 -2px 10px rgba(66, 165, 245, 0.1);
            transition: transform 0.3s ease;
        }

        .reading-controls.hidden {
            transform: translateY(100%);
        }

        .controls-container {
            max-width: 800px;
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
            background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66, 165, 245, 0.3);
        }

        .nav-btn:disabled {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .page-info {
            background: white;
            padding: 10px 20px;
            border-radius: 20px;
            border: 1px solid #e3f2fd;
            color: #546e7a;
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
            background: #e3f2fd;
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
            transition: width 0.3s ease;
            border-radius: 4px;
        }

        .reading-tools {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tool-btn {
            background: white;
            border: 1px solid #e3f2fd;
            color: #42a5f5;
            padding: 10px;
            border-radius: 50%;
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
            background: #e3f2fd;
        }

        .tool-btn.active {
            background: #42a5f5;
            color: white;
        }

        /* Settings Panel */
        .settings-panel {
            position: fixed;
            top: 70px;
            right: -350px;
            width: 350px;
            height: calc(100vh - 70px);
            background: white;
            border-left: 1px solid #e3f2fd;
            padding: 30px;
            z-index: 999;
            transition: right 0.3s ease;
            overflow-y: auto;
            box-shadow: -5px 0 20px rgba(66, 165, 245, 0.1);
        }

        .settings-panel.open {
            right: 0;
        }

        .settings-group {
            margin-bottom: 30px;
        }

        .settings-title {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .setting-item {
            margin-bottom: 15px;
        }

        .setting-label {
            display: block;
            color: #546e7a;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .setting-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e3f2fd;
            border-radius: 8px;
            background: white;
            color: #2c3e50;
        }

        .font-size-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .font-btn {
            background: #f8faff;
            border: 1px solid #e3f2fd;
            color: #42a5f5;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .font-btn:hover {
            background: #e3f2fd;
        }

        .font-size-display {
            background: white;
            border: 1px solid #e3f2fd;
            padding: 8px 15px;
            border-radius: 6px;
            min-width: 60px;
            text-align: center;
            color: #2c3e50;
            font-weight: 500;
        }

        /* Bookmarks Panel */
        .bookmarks-panel {
            position: fixed;
            top: 70px;
            left: -350px;
            width: 350px;
            height: calc(100vh - 70px);
            background: white;
            border-right: 1px solid #e3f2fd;
            padding: 30px;
            z-index: 999;
            transition: left 0.3s ease;
            overflow-y: auto;
            box-shadow: 5px 0 20px rgba(66, 165, 245, 0.1);
        }

        .bookmarks-panel.open {
            left: 0;
        }

        .bookmark-item {
            padding: 15px;
            border: 1px solid #e3f2fd;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .bookmark-item:hover {
            background: #f8faff;
            border-color: #42a5f5;
        }

        .bookmark-page {
            font-weight: 600;
            color: #42a5f5;
            margin-bottom: 5px;
        }

        .bookmark-preview {
            font-size: 14px;
            color: #546e7a;
            line-height: 1.4;
        }

        /* Reading Themes */
        .theme-sepia .reading-content {
            background: #f4f1ea;
            color: #5c4b37;
        }

        .theme-sepia body {
            background: #f0ead6;
        }

        .theme-dark .reading-content {
            background: #1a1a1a;
            color: #e0e0e0;
        }

        .theme-dark body {
            background: #121212;
        }

        .theme-dark .reader-header,
        .theme-dark .reading-controls {
            background: #1e1e1e;
            border-color: #333;
        }

        .theme-dark .book-title {
            color: #e0e0e0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .reading-content {
                padding: 30px 20px;
                font-size: 16px;
            }

            .controls-container {
                flex-direction: column;
                gap: 15px;
            }

            .progress-container {
                max-width: 100%;
                order: -1;
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
        }

        /* Animation for page turns */
        .page-turn-animation {
            animation: pageTurn 0.5s ease-in-out;
        }

        @keyframes pageTurn {
            0% {
                opacity: 0.7;
                transform: translateX(20px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Selection highlight */
        ::selection {
            background: rgba(66, 165, 245, 0.2);
        }

        /* Loading state */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
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

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e3f2fd;
            border-top: 4px solid #42a5f5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
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
                <i class="fas fa-font"></i> Pengaturan Teks
            </div>

            <div class="setting-item">
                <label class="setting-label">Ukuran Font</label>
                <div class="font-size-controls">
                    <button class="font-btn" onclick="changeFontSize(-2)">A-</button>
                    <div class="font-size-display" id="fontSizeDisplay">18px</div>
                    <button class="font-btn" onclick="changeFontSize(2)">A+</button>
                </div>
            </div>

            <div class="setting-item">
                <label class="setting-label">Jenis Font</label>
                <select class="setting-control" onchange="changeFont(this.value)">
                    <option value="Georgia, serif">Georgia (Default)</option>
                    <option value="Times New Roman, serif">Times New Roman</option>
                    <option value="Arial, sans-serif">Arial</option>
                    <option value="Helvetica, sans-serif">Helvetica</option>
                    <option value="Verdana, sans-serif">Verdana</option>
                </select>
            </div>

            <div class="setting-item">
                <label class="setting-label">Spasi Baris</label>
                <select class="setting-control" onchange="changeLineHeight(this.value)">
                    <option value="1.4">Rapat</option>
                    <option value="1.6">Normal</option>
                    <option value="1.8" selected>Longgar</option>
                    <option value="2.0">Sangat Longgar</option>
                </select>
            </div>
        </div>

        <div class="settings-group">
            <div class="settings-title">
                <i class="fas fa-palette"></i> Tema Bacaan
            </div>

            <div class="setting-item">
                <button class="setting-control" onclick="changeTheme('default')"
                    style="background: white; color: #333; border: 2px solid #e3f2fd;">
                    <i class="fas fa-sun"></i> Terang (Default)
                </button>
            </div>

            <div class="setting-item">
                <button class="setting-control" onclick="changeTheme('sepia')"
                    style="background: #f4f1ea; color: #5c4b37; border: 2px solid #d4c5a9;">
                    <i class="fas fa-leaf"></i> Sepia
                </button>
            </div>

            <div class="setting-item">
                <button class="setting-control" onclick="changeTheme('dark')"
                    style="background: #1a1a1a; color: #e0e0e0; border: 2px solid #333;">
                    <i class="fas fa-moon"></i> Gelap
                </button>
            </div>
        </div>
    </div>

    <!-- Reading Container -->
    <main class="reading-container">
        <div class="reading-content" id="readingContent">
            <!-- Content will be loaded here -->
            <div style="text-align: center; padding: 60px 0; color: #666;">
                <div class="loading-spinner" style="margin: 0 auto 20px;"></div>
                <p>Memuat konten buku...</p>
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
                <button class="tool-btn" onclick="toggleNightMode()" id="nightModeBtn" title="Mode Malam">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </footer>

    <script>
        // Reading state
        let currentPage = {{ $halaman }};
        let totalPages = {{ $buku->halaman }};
        let bookId = {{ $buku->id }};
        let fontSize = 18;
        let autoScrollInterval = null;
        let hideControlsTimeout = null;

        // Initialize reader
        document.addEventListener('DOMContentLoaded', function() {
            loadBookContent();
            updateProgress();
            loadBookmarks();

            // Auto-hide controls
            setupAutoHideControls();

            // Keyboard shortcuts
            setupKeyboardShortcuts();

            // Load saved preferences
            loadUserPreferences();
        });

        // Load book content
        function loadBookContent() {
            showLoading();

            // Simulate loading content (replace with actual content loading)
            setTimeout(() => {
                const content = generateSampleContent();
                document.getElementById('readingContent').innerHTML = content;
                document.getElementById('readingContent').classList.add('page-turn-animation');
                hideLoading();

                // Update page controls
                updatePageControls();

                // Save reading progress
                saveProgress();
            }, 1000);
        }

        // Generate sample content (replace with actual book content)
        function generateSampleContent() {
            return `
                <h2 style="color: #2c3e50; margin-bottom: 30px; text-align: center;">
                    Halaman ${currentPage}
                </h2>
                
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                
                <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                
                <p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
                
                <p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur.</p>
            `;
        }

        // Navigation functions
        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                loadBookContent();
            }
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                loadBookContent();
            }
        }

        function goToPage(page) {
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                loadBookContent();
            }
        }

        function updatePageControls() {
            document.getElementById('prevBtn').disabled = currentPage <= 1;
            document.getElementById('nextBtn').disabled = currentPage >= totalPages;
            document.getElementById('pageInfo').textContent = `Hal. ${currentPage} dari ${totalPages}`;
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

            updateProgress();
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

        function changeFontSize(delta) {
            fontSize = Math.max(12, Math.min(28, fontSize + delta));
            document.getElementById('readingContent').style.fontSize = fontSize + 'px';
            document.getElementById('fontSizeDisplay').textContent = fontSize + 'px';
            saveUserPreferences();
        }

        function changeFont(fontFamily) {
            document.getElementById('readingContent').style.fontFamily = fontFamily;
            saveUserPreferences();
        }

        function changeLineHeight(lineHeight) {
            document.getElementById('readingContent').style.lineHeight = lineHeight;
            saveUserPreferences();
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
                    window.scrollBy(0, 1);
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
                });
            } else {
                document.exitFullscreen().then(() => {
                    btn.innerHTML = '<i class="fas fa-expand"></i><span>Fullscreen</span>';
                });
            }
        }

        // Bookmark functions
        function addBookmark() {
            fetch(`/buku/${bookId}/bookmark-page`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        halaman: currentPage
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadBookmarks();
                        showNotification('Bookmark ditambahkan');
                    }
                })
                .catch(error => {
                    console.error('Error adding bookmark:', error);
                });
        }

        function loadBookmarks() {
            // Load bookmarks from server
            // Implementation depends on your bookmark system
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
                switch (e.key) {
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        e.preventDefault();
                        previousPage();
                        break;
                    case 'ArrowRight':
                    case 'ArrowDown':
                    case ' ':
                        e.preventDefault();
                        nextPage();
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
                        break;
                }
            });
        }

        // User preferences
        function saveUserPreferences() {
            const preferences = {
                fontSize: fontSize,
                fontFamily: document.getElementById('readingContent').style.fontFamily,
                lineHeight: document.getElementById('readingContent').style.lineHeight,
                theme: document.body.className
            };

            localStorage.setItem('readerPreferences', JSON.stringify(preferences));
        }

        function loadUserPreferences() {
            const saved = localStorage.getItem('readerPreferences');
            if (saved) {
                const preferences = JSON.parse(saved);

                if (preferences.fontSize) {
                    fontSize = preferences.fontSize;
                    document.getElementById('readingContent').style.fontSize = fontSize + 'px';
                    document.getElementById('fontSizeDisplay').textContent = fontSize + 'px';
                }

                if (preferences.fontFamily) {
                    document.getElementById('readingContent').style.fontFamily = preferences.fontFamily;
                }

                if (preferences.lineHeight) {
                    document.getElementById('readingContent').style.lineHeight = preferences.lineHeight;
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

        function showNotification(message) {
            // Simple notification (can be enhanced)
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 20px;
                background: #4caf50;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                z-index: 10000;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function goBack() {
            if (document.referrer) {
                window.history.back();
            } else {
                window.location.href = `/buku/${bookId}`;
            }
        }

        function toggleNightMode() {
            const btn = document.getElementById('nightModeBtn');
            const isDark = document.body.classList.contains('theme-dark');

            if (isDark) {
                changeTheme('default');
                btn.classList.remove('active');
            } else {
                changeTheme('dark');
                btn.classList.add('active');
            }
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
            }
        });

        // Save progress when leaving page
        window.addEventListener('beforeunload', function() {
            saveProgress();
        });
    </script>
</body>

</html>
