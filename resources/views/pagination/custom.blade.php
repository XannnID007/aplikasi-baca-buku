@if ($paginator->hasPages())
    <nav style="display: flex; justify-content: center; margin-top: 30px;">
        <ul style="display: flex; list-style: none; margin: 0; padding: 0; gap: 5px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span
                        style="padding: 10px 15px; background: #333; color: #666; border: 1px solid #444; border-radius: 6px; cursor: not-allowed;">
                        <i class="fas fa-chevron-left"></i> Previous
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                        style="padding: 10px 15px; background: #2d2d2d; color: #e0e0e0; border: 1px solid #555; border-radius: 6px; text-decoration: none; transition: all 0.3s; display: flex; align-items: center; gap: 5px;"
                        onmouseover="this.style.background='#007bff'; this.style.borderColor='#007bff'; this.style.color='white';"
                        onmouseout="this.style.background='#2d2d2d'; this.style.borderColor='#555'; this.style.color='#e0e0e0';">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span
                            style="padding: 10px 15px; background: #333; color: #666; border: 1px solid #444; border-radius: 6px;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span
                                    style="padding: 10px 15px; background: #007bff; color: white; border: 1px solid #007bff; border-radius: 6px; font-weight: bold;">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                    style="padding: 10px 15px; background: #2d2d2d; color: #e0e0e0; border: 1px solid #555; border-radius: 6px; text-decoration: none; transition: all 0.3s; min-width: 44px; text-align: center; display: block;"
                                    onmouseover="this.style.background='#007bff'; this.style.borderColor='#007bff'; this.style.color='white';"
                                    onmouseout="this.style.background='#2d2d2d'; this.style.borderColor='#555'; this.style.color='#e0e0e0';">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                        style="padding: 10px 15px; background: #2d2d2d; color: #e0e0e0; border: 1px solid #555; border-radius: 6px; text-decoration: none; transition: all 0.3s; display: flex; align-items: center; gap: 5px;"
                        onmouseover="this.style.background='#007bff'; this.style.borderColor='#007bff'; this.style.color='white';"
                        onmouseout="this.style.background='#2d2d2d'; this.style.borderColor='#555'; this.style.color='#e0e0e0';">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li>
                    <span
                        style="padding: 10px 15px; background: #333; color: #666; border: 1px solid #444; border-radius: 6px; cursor: not-allowed;">
                        Next <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    {{-- Page Info --}}
    <div style="text-align: center; margin-top: 15px; color: #b0b0b0; font-size: 14px;">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </div>
@endif
