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

    $durationText = $durationText ?? ($plainText($product->duration) ?: 'Đang cập nhật');
    $departureText = $departureText ?? ($plainText($product->departure_location) ?: ($plainText($product->address) ?: 'Đang cập nhật địa điểm'));
    $transportText = $transportText ?? ($plainText($product->transport) ?: 'Liên hệ để biết thêm');
    $promoText = $plainText($product->promotion_content);
    $primaryTag = $primaryTag ?? ($product->category?->name ?? ($currentCategoryName ?? 'Tour'));
    $secondaryTag = $secondaryTag ?? ($promoText !== '' ? \Illuminate\Support\Str::limit($promoText, 28) : ($product->is_featured ? 'Tour nổi bật' : 'Mới cập nhật'));
    $badgeText = $badgeText ?? ($product->is_featured ? 'Đề xuất' : 'Mới');
    $imageUrl = $resolveImage($product->image) ?: ($imageFallback ?? 'assets/img/tour/01.jpg');
@endphp

<article class="tour-listing-card">
    <a href="{{ $product->frontend_url }}" class="tour-card-media" aria-label="Xem chi tiết {{ $displayValue($product->title) }}">
        <img src="{{ $imageUrl }}" alt="{{ $displayValue($product->title) }}">
        <span class="tour-card-badge-top">{{ $displayValue($badgeText) }}</span>
        <span class="tour-card-duration">{{ $displayValue($durationText) }}</span>
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
                <i class="far fa-location-dot" aria-hidden="true"></i>
                <span>Khởi hành từ {{ $displayValue($departureText) }}</span>
            </div>
            <div class="tour-card-meta-item">
                <i class="far fa-plane-departure" aria-hidden="true"></i>
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
