@php
    $resolveImage = function ($value) {
        if (!\App\Support\MediaManager::diskPath($value)) {
            return null;
        }
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
    $placeholderImage = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'%3E%3Crect width='600' height='400' fill='%23f0f0f0'/%3E%3Ctext x='300' y='200' fill='%23999999' font-family='sans-serif' font-size='24' text-anchor='middle' alignment-baseline='middle'%3EĐang cập nhật ảnh%3C/text%3E%3C/svg%3E";
    $imageUrl = $resolveImage($product->image) ?: ($imageFallback ?? $placeholderImage);
    $inWishlist = session()->has('wishlist.' . $product->id);
@endphp

<article class="tour-listing-card mb-4">
    <a href="{{ $product->frontend_url }}" class="tour-card-media" aria-label="Xem chi tiết {{ $displayValue($product->title) }}">
        <img src="{{ $imageUrl }}" alt="{{ $displayValue($product->title) }}">
        <span class="tour-card-badge-top">{{ $displayValue($badgeText) }}</span>
        <span class="tour-card-duration">{{ $displayValue($durationText) }}</span>
        <span class="add-wishlist {{ $inWishlist ? 'active' : '' }}" data-id="{{ $product->id }}" onclick="event.preventDefault(); toggleWishlist(this, {{ $product->id }});">
            <i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart"></i>
        </span>
    </a>

    <div class="tour-card-body">
        <div class="tour-card-tags">
            <span class="tour-card-tag">{{ $displayValue($primaryTag) }}</span>
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
