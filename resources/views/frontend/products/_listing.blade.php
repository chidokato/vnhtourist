<div class="row g-4">
    @forelse ($products as $product)
        <div class="col-md-6 col-xl-4">
            @include('frontend.products._tour_card', [
                'product' => $product,
                'currentCategoryName' => $currentCategoryName ?? 'Tour',
            ])
        </div>
    @empty
        <div class="col-12">
            <div class="widget category-empty-state">
                <h4 class="widget-title">Chưa có dữ liệu</h4>
                <p class="mb-0">Chưa tìm thấy tour phù hợp với bộ lọc hiện tại.</p>
            </div>
        </div>
    @endforelse
</div>

@if ($products->lastPage() > 1)
    <div class="pagination-area">
        <div aria-label="Phân trang">
            <ul class="pagination">
                <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $products->previousPageUrl() ?: '#' }}" aria-label="Trang trước">
                        <span aria-hidden="true"><i class="fas fa-arrow-left"></i></span>
                    </a>
                </li>
                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    <li class="page-item {{ $page === $products->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach
                <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $products->nextPageUrl() ?: '#' }}" aria-label="Trang sau">
                        <span aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="pagination-showing">
            Trang {{ $products->currentPage() }}/{{ $products->lastPage() }}
        </div>
    </div>
@endif
