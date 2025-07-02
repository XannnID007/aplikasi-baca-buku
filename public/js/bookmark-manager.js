/**
 * Bookmark Manager for PDF Reader
 * Handles page-specific bookmarks with notes
 */
class BookmarkManager {
    constructor(bookId) {
        this.bookId = bookId;
        this.bookmarks = [];
        this.init();
    }

    init() {
        this.loadBookmarks();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Listen for bookmark panel toggle
        document.addEventListener('bookmarkPanelToggle', () => {
            this.loadBookmarks();
        });
    }

    async loadBookmarks() {
        try {
            const response = await fetch(`/api/buku/${this.bookId}/bookmarks`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                this.bookmarks = data.bookmarks;
                this.renderBookmarks();
            } else {
                console.error('Failed to load bookmarks:', data.message);
            }
        } catch (error) {
            console.error('Error loading bookmarks:', error);
        }
    }

    renderBookmarks() {
        const container = document.getElementById('bookmarksList');
        
        if (this.bookmarks.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 20px; color: var(--text-secondary);">
                    <i class="fas fa-bookmark" style="font-size: 32px; margin-bottom: 10px; opacity: 0.5;"></i>
                    <p>Belum ada bookmark</p>
                    <p style="font-size: 12px;">Klik "Tandai Halaman Ini" untuk menambah bookmark</p>
                </div>
            `;
            return;
        }

        const bookmarksHtml = this.bookmarks.map(bookmark => `
            <div class="bookmark-item" onclick="goToBookmark(${bookmark.halaman})" data-bookmark-id="${bookmark.id}">
                <div class="bookmark-page">
                    <i class="fas fa-bookmark" style="margin-right: 8px; color: var(--accent-color);"></i>
                    Halaman ${bookmark.halaman}
                </div>
                ${bookmark.note ? `
                    <div class="bookmark-preview">${this.truncateText(bookmark.note, 80)}</div>
                ` : ''}
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                    <small style="color: var(--text-secondary); font-size: 11px;">
                        ${this.formatDate(bookmark.created_at)}
                    </small>
                    <button onclick="event.stopPropagation(); deleteBookmark(${bookmark.id})" 
                            class="btn-sm" 
                            style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 10px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');

        container.innerHTML = bookmarksHtml;
    }

    async addBookmark(page, note = null) {
        try {
            const response = await fetch(`/api/buku/${this.bookId}/bookmark-page`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    halaman: page,
                    note: note
                })
            });

            const data = await response.json();

            if (data.success) {
                this.loadBookmarks(); // Reload bookmarks
                showNotification('Bookmark berhasil ditambahkan');
                return true;
            } else {
                showNotification(data.message, 'error');
                return false;
            }
        } catch (error) {
            console.error('Error adding bookmark:', error);
            showNotification('Gagal menambahkan bookmark', 'error');
            return false;
        }
    }

    async deleteBookmark(bookmarkId) {
        if (!confirm('Hapus bookmark ini?')) {
            return;
        }

        try {
            const response = await fetch(`/api/buku/${this.bookId}/bookmarks/${bookmarkId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                this.loadBookmarks(); // Reload bookmarks
                showNotification('Bookmark berhasil dihapus');
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error deleting bookmark:', error);
            showNotification('Gagal menghapus bookmark', 'error');
        }
    }

    hasBookmarkOnPage(page) {
        return this.bookmarks.some(bookmark => bookmark.halaman === page);
    }

    getBookmarkOnPage(page) {
        return this.bookmarks.find(bookmark => bookmark.halaman === page);
    }

    truncateText(text, maxLength) {
        if (text.length <= maxLength) {
            return text;
        }
        return text.substring(0, maxLength) + '...';
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInHours = (now - date) / (1000 * 60 * 60);

        if (diffInHours < 24) {
            return 'Hari ini';
        } else if (diffInHours < 48) {
            return 'Kemarin';
        } else {
            return date.toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short' 
            });
        }
    }
}

// Global bookmark manager instance
let bookmarkManager;

// Enhanced bookmark functions for the main script
function initializeBookmarkManager() {
    bookmarkManager = new BookmarkManager(bookId);
}

function addBookmark() {
    // Show modal for adding bookmark with note
    showBookmarkModal();
}

function showBookmarkModal() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Bookmark</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div style="padding: 20px;">
                <p style="margin-bottom: 15px; color: var(--text-color);">
                    Menandai halaman <strong>${currentPage}</strong>
                </p>
                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea id="bookmarkNote" class="form-control" rows="3" 
                              placeholder="Tambahkan catatan untuk bookmark ini..."></textarea>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button onclick="closeModal()" class="btn btn-outline">Batal</button>
                    <button onclick="saveBookmark()" class="btn btn-primary">Simpan Bookmark</button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    document.getElementById('bookmarkNote').focus();
}

function closeModal() {
    const modal = document.querySelector('.modal');
    if (modal) {
        modal.remove();
    }
}

async function saveBookmark() {
    const note = document.getElementById('bookmarkNote').value.trim();
    const success = await bookmarkManager.addBookmark(currentPage, note || null);
    
    if (success) {
        closeModal();
    }
}

function goToBookmark(page) {
    goToPage(page);
    closeBookmarks();
}

function deleteBookmark(bookmarkId) {
    bookmarkManager.deleteBookmark(bookmarkId);
}

// Enhanced loadBookmarks function
function loadBookmarks() {
    if (bookmarkManager) {
        bookmarkManager.loadBookmarks();
    }
}

// Add CSS for modal
const modalStyles = `
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10001;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: var(--primary-color);
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            animation: slideIn 0.3s ease;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 20px 0;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 0;
        }

        .modal-header h3 {
            color: var(--text-color);
            margin: 0;
            font-size: 18px;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: var(--secondary-color);
            color: var(--text-color);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .form-control {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .form-control:focus {
            border-color: var(--accent-color);
            outline: none;
        }
    </style>
`;

// Add modal styles to head
document.head.insertAdjacentHTML('beforeend', modalStyles);