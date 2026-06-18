@php
    $resolveImage = function ($value) {
        return \App\Support\MediaManager::publicUrl($value);
    };

    $formatPrice = function ($product) {
        if (! filled($product->price)) {
            return 'Liên hệ';
        }

        return number_format((float) $product->price, 0, ',', '.') . ' đ';
    };

    $plainText = function ($value) {
        return trim(preg_replace('/\s+/u', ' ', strip_tags((string) $value)));
    };
@endphp


<div class="row g-4">
    @forelse ($products as $product)
        @php
            $durationText = $plainText($product->duration) ?: 'Đang cập nhật';
            $departureText = $plainText($product->departure_location) ?: ($plainText($product->address) ?: 'Đang cập nhật địa điểm');
            $transportText = $plainText($product->transport) ?: 'Liên hệ để biết thêm';
            $promoText = $plainText($product->promotion_content);
            $primaryTag = $product->category?->name ?? ($currentCategoryName ?? 'Tour');
            $secondaryTag = $promoText !== '' ? \Illuminate\Support\Str::limit($promoText, 28) : ($product->is_featured ? 'Tour nổi bật' : 'Mới cập nhật');
        @endphp
        <div class="col-md-6 col-xl-4">
            <article class="tour-listing-card">
                <a href="{{ $product->frontend_url }}" class="tour-card-media" aria-label="Xem chi tiết {{ $displayValue($product->title) }}">
                    @if ($resolveImage($product->image))
                        <img src="{{ $resolveImage($product->image) }}" alt="{{ $displayValue($product->title) }}">
                    @endif
                    <span class="tour-card-badge-top">{{ $product->is_featured ? 'Đề xuất' : 'Mới' }}</span>
                    <span class="tour-card-duration">{{ $durationText }}</span>
                </a>
                

                <div class="tour-card-body">
                    <div class="tour-card-tags">
                        <span class="tour-card-tag">{{ $displayValue($primaryTag) }}</span>
                        <span class="tour-card-tag tour-card-tag--accent">{{ $displayValue($secondaryTag) }}</span>
                    </div>
                    <h4 class="tour-card-title">
                        <a href="{{ $product->frontend_url }}">{{ $displayValue($product->title) }}</a>
                    </h4>
                    <div class="tour-card-meta">
                        <div class="tour-card-meta-item">
                            <span class="tour-card-meta-symbol" aria-hidden="true">📍</span>
                            <span>Khởi hành từ {{ $displayValue($departureText) }}</span>
                        </div>
                        <div class="tour-card-meta-item">
                            <span class="tour-card-meta-symbol" aria-hidden="true">✈</span>
                            <span>{{ $displayValue($transportText) }}</span>
                        </div>
                    </div>

                    <div class="tour-card-footer">
                        <div>
                            <div class="tour-card-price-label">Giá từ</div>
                            <div class="tour-card-price-value">{{ $formatPrice($product) }}</div>
                        </div>
                        <a href="{{ $product->frontend_url }}" class="tour-card-link">Chi tiết</a>
                    </div>
                </div>
            </article>
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
                        <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                    </a>
                </li>
                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    <li class="page-item {{ $page === $products->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach
                <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $products->nextPageUrl() ?: '#' }}" aria-label="Trang sau">
                        <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="pagination-showing">
            Trang {{ $products->currentPage() }}/{{ $products->lastPage() }}
        </div>
    </div>
@endif
