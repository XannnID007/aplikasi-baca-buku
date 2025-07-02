@if ($paginator->hasPages())
    <nav style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span
                style="padding: 10px 20px; background: #333; color: #666; border: 1px solid #444; border-radius: 6px; cursor: not-allowed; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-chevron-left"></i> Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                style="padding: 10px 20px; background: #2d2d2d; color: #e0e0e0; border: 1px solid #555; border-radius: 6px; text-decoration: none; transition: all 0.3s; display: flex; align-items: center; gap: 8px;"
                onmouseover="this.style.background='#007bff'; this.style.borderColor='#007bff'; this.style.color='white';"
                onmouseout="this.style.background='#2d2d2d'; this.style.borderColor='#555'; this.style.color='#e0e0e0';">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
        @endif

        {{-- Page Info --}}
        <span style="color: #b0b0b0; font-size: 14px;">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </span>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                style="padding: 10px 20px; background: #2d2d2d; color: #e0e0e0; border: 1px solid #555; border-radius: 6px; text-decoration: none; transition: all 0.3s; display: flex; align-items: center; gap: 8px;"
                onmouseover="this.style.background='#007bff'; this.style.borderColor='#007bff'; this.style.color='white';"
                onmouseout="this.style.background='#2d2d2d'; this.style.borderColor='#555'; this.style.color='#e0e0e0';">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span
                style="padding: 10px 20px; background: #333; color: #666; border: 1px solid #444; border-radius: 6px; cursor: not-allowed; display: flex; align-items: center; gap: 8px;">
                Next <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif
