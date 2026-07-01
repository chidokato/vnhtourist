@extends('frontend.layouts.app')

@php
    $customerInquiryErrors = $errors->getBag('customerInquiry');

    $resolveImage = function ($value) {
        if (!\App\Support\MediaManager::diskPath($value)) {
            return null;
        }
        return \App\Support\MediaManager::publicUrl($value);
    };

    $plainText = function ($value) {
        $text = trim(strip_tags((string) $value));

        return $text !== '' ? $text : null;
    };

    $formatPrice = function ($value) {
        return $value !== null ? number_format((float) $value, 0, ',', '.') . ' ₫' : 'Liên hệ';
    };

    $extractListItems = function ($value) use ($plainText) {
        $value = (string) $value;
        $items = collect();

        if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $value, $matches)) {
            $items = collect($matches[1] ?? []);
        } else {
            $items = collect(preg_split('/[\r\n;|]+/', $value));
        }

        return $items
            ->map(fn ($item) => $plainText($item))
            ->filter()
            ->values();
    };

    $gallery = $product->galleryImages ?? collect();
    $galleryItems = collect();

    if ($resolveImage($product->image)) {
        $galleryItems->push([
            'src' => $resolveImage($product->image),
            'alt' => $displayValue($product->title),
        ]);
    }

    foreach ($gallery as $image) {
        $src = $resolveImage($image->image_path ?? $image->path ?? $image->image ?? null);

        if (! $src) {
            continue;
        }

        $galleryItems->push([
            'src' => $src,
            'alt' => $displayValue($product->title),
        ]);
    }

    $galleryItems = $galleryItems->unique('src')->values();

    if ($galleryItems->isEmpty()) {
        $placeholderImage = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'%3E%3Crect width='600' height='400' fill='%23f0f0f0'/%3E%3Ctext x='300' y='200' fill='%23999999' font-family='sans-serif' font-size='24' text-anchor='middle' alignment-baseline='middle'%3EĐang cập nhật ảnh%3C/text%3E%3C/svg%3E";
        $galleryItems = collect([[
            'src' => $placeholderImage,
            'alt' => $displayValue($product->title),
        ]]);
    }

    $heroImage = $galleryItems->first()['src'];

    $categoryUrl = $product->category?->slug
        ? route('frontend.categories.show', $product->category->slug)
        : route('frontend.home');

    $departureText = $plainText($product->departure_location) ?: ($plainText($product->address) ?: 'Đang cập nhật');
    $destinationText = $plainText($product->destination) ?: ($plainText($product->attractions) ?: 'Đang cập nhật');
    $durationText = $plainText($product->duration) ?: 'Đang cập nhật';
    $transportText = $plainText($product->transport) ?: 'Đang cập nhật';
    $transportIconClass = 'fas fa-bus';

    if (preg_match('/may bay|hang khong|air|flight|plane/i', $transportText)) {
        $transportIconClass = 'fas fa-plane-departure';
    } elseif (preg_match('/tau|train|rail/i', $transportText)) {
        $transportIconClass = 'fas fa-train';
    } elseif (preg_match('/thuyen|du thuyen|ship|boat/i', $transportText)) {
        $transportIconClass = 'fas fa-ship';
    }

    $itineraryText = $plainText($product->itinerary) ?: 'Đang cập nhật';
    $publishedText = optional($product->published_at)->format('d/m/Y') ?: 'Đang cập nhật';
    $departureDateText = $product->departure_date ? optional($product->departure_date)->format('d/m/Y') : null;

    $addressParts = array_filter([
        $plainText($product->address),
        $plainText(optional($product->ward)->name),
        $plainText(optional($product->province)->name),
    ]);
    $locationText = count($addressParts) ? implode(', ', $addressParts) : 'Đang cập nhật';
    $headerMetaText = $plainText($product->itinerary) ?: ($locationText !== 'Đang cập nhật' ? $locationText : 'Đang cập nhật');

    $attractionItems = collect(preg_split('/[\r\n,;|]+/', (string) $product->attractions))
        ->map(fn ($item) => trim(strip_tags((string) $item)))
        ->filter()
        ->values();
    $attractionColumns = $attractionItems->chunk((int) max(1, ceil(max($attractionItems->count(), 1) / 2)));

    $policyItems = $extractListItems($product->sales_policy);
    $includedItems = $policyItems
        ->filter(fn ($item) => ! preg_match('/\b(khong|chua|ngoai|tru)\b/i', $item))
        ->values();
    $excludedItems = $policyItems
        ->filter(fn ($item) => preg_match('/\b(khong|chua|ngoai|tru)\b/i', $item))
        ->values();

    if ($includedItems->isEmpty()) {
        $includedItems = collect([
            $plainText($product->guide_content),
            $plainText($product->insurance_content),
            $plainText($product->promotion_content),
        ])->filter()->values();
    }

    if ($excludedItems->isEmpty() && $plainText($product->visa_content)) {
        $excludedItems = collect([$plainText($product->visa_content)]);
    }

    $itineraryStops = collect(preg_split('/\s*-\s*/', (string) $product->itinerary))
        ->map(fn ($item) => trim(strip_tags((string) $item)))
        ->filter()
        ->values();

    if ($itineraryStops->isEmpty()) {
        $itineraryStops = $attractionItems->take(3)->values();
    }

    if ($itineraryStops->isEmpty()) {
        $itineraryStops = collect([$displayValue($product->title)]);
    }

    $itinerarySections = collect();
    $contentHtml = (string) ($product->content ?? '');

    if (preg_match_all('/<h([1-6])[^>]*>(.*?)<\/h\1>/is', $contentHtml, $headingMatches, PREG_OFFSET_CAPTURE)) {
        $introHtml = trim(substr($contentHtml, 0, $headingMatches[0][0][1] ?? 0));

        foreach ($headingMatches[0] as $index => $headingMatch) {
            $headingHtml = $headingMatch[0];
            $headingStart = $headingMatch[1];
            $bodyStart = $headingStart + strlen($headingHtml);
            $nextHeadingStart = $headingMatches[0][$index + 1][1] ?? strlen($contentHtml);
            $bodyHtml = trim(substr($contentHtml, $bodyStart, $nextHeadingStart - $bodyStart));

            if ($index === 0 && $introHtml !== '') {
                $bodyHtml = $introHtml . $bodyHtml;
            }

            $itinerarySections->push([
                'index' => $index + 1,
                'title' => $plainText($headingMatches[2][$index][0] ?? '') ?: ('Ngay ' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)),
                'meta' => null,
                'body' => $bodyHtml,
            ]);
        }
    }

    if ($itinerarySections->isEmpty() && $plainText($contentHtml)) {
        $itinerarySections = collect([[
            'index' => 1,
            'title' => $plainText($product->itinerary) ?: $displayValue($product->title),
            'meta' => null,
            'body' => $contentHtml,
        ]]);
    }

    $extractRichTextBlocks = function ($value) use ($plainText) {
        $value = (string) $value;
        $items = collect();

        if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $value, $matches)) {
            $items = collect($matches[1] ?? []);
        } elseif (preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $value, $matches)) {
            $items = collect($matches[1] ?? []);
        } else {
            $items = collect(preg_split('/[\r\n]+/', $value));
        }

        return $items
            ->map(fn ($item) => $plainText($item))
            ->filter()
            ->values();
    };

    $summaryHighlights = $extractRichTextBlocks($product->summary);
    $promotionHighlights = $extractRichTextBlocks($product->promotion_content);

    $tourContentTabs = collect([
        [
            'id' => 'content',
            'label' => 'Lich trinh',
            'content' => $product->content,
        ],
        [
            'id' => 'sales_policy',
            'label' => 'Chinh sach gia',
            'content' => $product->sales_policy,
        ],
        [
            'id' => 'guide_content',
            'label' => 'Huong dan',
            'content' => $product->guide_content,
        ],
        [
            'id' => 'visa_content',
            'label' => 'Visa',
            'content' => $product->visa_content,
        ],
        [
            'id' => 'insurance_content',
            'label' => 'Bao hiem',
            'content' => $product->insurance_content,
        ],
    ])->filter(fn ($tab) => $plainText($tab['content']))->values();

    $infoSections = collect([
        ['id' => 'guide', 'label' => 'Huong dan', 'content' => $product->guide_content],
        ['id' => 'visa', 'label' => 'Visa', 'content' => $product->visa_content],
        ['id' => 'insurance', 'label' => 'Bao hiem', 'content' => $product->insurance_content],
        ['id' => 'promotion', 'label' => 'Khuyen mai', 'content' => $product->promotion_content],
        ['id' => 'policy', 'label' => 'Chinh sach', 'content' => $product->sales_policy],
    ])->filter(fn ($section) => $plainText($section['content']))->values();

    $contactName = $displayValue($contactSeller->name ?? ($settings->company_name ?? 'Tourist'));
    $contactPhone = trim((string) ($contactSeller->phone ?? $settings->hotline ?? ''));
    $contactEmail = trim((string) ($contactSeller->email ?? $settings->email ?? ''));
@endphp

@section('title', $pageTitle ?? $product->title)
@section('meta_description', $pageDescription ?? '')
@section('meta_keywords', $pageKeywords ?? '')

@push('styles')
    <link rel="stylesheet" href="{{ asset('tourit/assets/css/product-show-page.css') }}">
    <link rel="stylesheet" href="{{ asset('tourit/assets/css/booking-sidebar.css') }}">
@endpush

@section('content')
    <main class="main product-show-page">
        <div class="site-breadcrumb d-none d-lg-block" style="background: url({{ $heroImage }}) center center / cover no-repeat;">
            <div class="container">
                <h2 class="breadcrumb-title">{{ $displayValue($product->title) }}</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
                    <li><a href="{{ $categoryUrl }}">{{ $displayValue(optional($product->category)->name, 'Tour') }}</a></li>
                    <li class="active">{{ $displayValue($product->title) }}</li>
                </ul>
            </div>
        </div>

        <div class="tour-single pt-40">
            <div class="container">
                <div class="listing-wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tour-gallery-mosaic">
                                @php
                                    $primaryImage = $galleryItems->get(0);
                                    $secondaryImages = $galleryItems->slice(1, 2)->values();
                                @endphp

                                @if ($primaryImage)
                                    <div class="tour-gallery-mosaic-main" title="{{ $primaryImage['alt'] }}">
                                        <img src="{{ $primaryImage['src'] }}" alt="{{ $primaryImage['alt'] }}">
                                        <span class="tour-gallery-badge">{{ $displayValue(optional($product->category)->name, 'Tour') }}</span>
                                    </div>
                                @endif

                                <div class="tour-gallery-mosaic-side">
                                    @foreach ($secondaryImages as $secondaryImage)
                                        <div class="tour-gallery-mosaic-thumb" title="{{ $secondaryImage['alt'] }}">
                                            <img src="{{ $secondaryImage['src'] }}" alt="{{ $secondaryImage['alt'] }}">
                                        </div>
                                    @endforeach

                                    @if ($secondaryImages->count() < 2)
                                        @for ($i = $secondaryImages->count(); $i < 2; $i++)
                                            <div class="tour-gallery-mosaic-thumb" title="{{ $primaryImage['alt'] ?? $displayValue($product->title) }}">
                                                <img src="{{ $primaryImage['src'] ?? $heroImage }}" alt="{{ $primaryImage['alt'] ?? $displayValue($product->title) }}">
                                            </div>
                                        @endfor
                                    @endif
                                </div>

                                @foreach ($galleryItems->slice(3) as $hiddenImage)
                                    <a href="{{ $hiddenImage['src'] }}" class="tour-gallery-hidden popup-img" title="{{ $hiddenImage['alt'] }}" tabindex="-1" aria-hidden="true">
                                        <img src="{{ $hiddenImage['src'] }}" alt="{{ $hiddenImage['alt'] }}">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="listing-content">
                                <h4 class="listing-title">{{ $displayValue($product->title) }}</h4>
                                <div class="listing-header">
                                    <div class="listing-header-info">
                                        <p class="listing-location"><i class="far fa-map-marker-alt"></i> <b>Lịch trình:</b> {{ $displayValue($headerMetaText) }}</p>
                                        <p class="listing-location"><i class="{{ $transportIconClass }}"></i> <b>Phương tiện:</b> {{ $displayValue($transportText) }}</p>
                                    </div>
                                    <!-- <div class="listing-rate">
                                        <span class="badge"><i class="far fa-star"></i> {{ $product->is_featured ? '5.0' : '4.8' }}</span> <span class="listing-rate-review">(+1k Reviews)</span>
                                        <span class="listing-rate-type">{{ $product->is_featured ? 'Noi bat' : 'Pho bien' }}</span>
                                    </div> -->
                                </div>

                                <div class="listing-item">
                                    <div class="row g-4">
                                        <div class="col-6 col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-clock"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Thời gian</h6>
                                                    <span title="{{ $displayValue($durationText) }}">{{ $displayValue($durationText) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-map-marker-alt"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Khởi hành</h6>
                                                    <span title="{{ $displayValue($departureText) }}">{{ $displayValue($departureText) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-calendar-days"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Ngày khởi hành</h6>
                                                    <span title="{{ $displayValue($departureDateText ?: $publishedText) }}">{{ $displayValue($departureDateText ?: $publishedText) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-earth-americas"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Điểm đến</h6>
                                                    <span title="{{ $displayValue($destinationText) }}">{{ $displayValue($destinationText) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($tourContentTabs->isNotEmpty())
                                <div class="listing-item product-detail-tabs-section">
                                    @if ($summaryHighlights->isNotEmpty() || $plainText($product->summary) || $plainText($product->promotion_content))
                                        <div class="tour-info-boxes">
                                            <div class="tour-info-box tour-info-box--primary">
                                                <div class="tour-info-box-head">
                                                    <span class="tour-info-box-icon"><i class="far fa-star"></i></span>
                                                    <h5>Lịch trình có gì hay?</h5>
                                                </div>
                                                @if ($summaryHighlights->isNotEmpty())
                                                    <ul class="tour-info-box-list">
                                                        @foreach ($summaryHighlights as $item)
                                                            <li><i class="far fa-check"></i><span>{{ $displayValue($item) }}</span></li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="product-detail-richtext">
                                                        {!! $product->summary ?: '<p>Dang cap nhat thong tin noi bat.</p>' !!}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="tour-info-box tour-info-box--accent">
                                                <div class="tour-info-box-head">
                                                    <span class="tour-info-box-icon"><i class="far fa-tag"></i></span>
                                                    <h5>Thông tin khuyến mãi</h5>
                                                </div>
                                                @if ($promotionHighlights->isNotEmpty())
                                                    <ul class="tour-info-box-list tour-info-box-list--accent">
                                                        @foreach ($promotionHighlights as $item)
                                                            <li><i class="far fa-check"></i><span>{{ $displayValue($item) }}</span></li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="product-detail-richtext">
                                                        {!! $product->promotion_content ?: '<p>Dang cap nhat thong tin khuyen mai.</p>' !!}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <ul class="nav nav-tabs product-detail-tabs" id="productDetailTabs" role="tablist">
                                        @foreach ($tourContentTabs as $tab)
                                            <li class="nav-item" role="presentation">
                                                <button
                                                    class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                    id="tab-{{ $tab['id'] }}"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#tab-pane-{{ $tab['id'] }}"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="tab-pane-{{ $tab['id'] }}"
                                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                                >
                                                    {{ $tab['label'] }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content product-detail-tab-content" id="productDetailTabsContent">
                                        @foreach ($tourContentTabs as $tab)
                                            <div
                                                class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                                id="tab-pane-{{ $tab['id'] }}"
                                                role="tabpanel"
                                                aria-labelledby="tab-{{ $tab['id'] }}"
                                                tabindex="0"
                                            >
                                                @if ($tab['id'] === 'content' && $itinerarySections->isNotEmpty())
                                                    <div class="itinerary-content-box itinerary-content-box--plain">
                                                        @foreach ($itinerarySections as $section)
                                                            <div class="itinerary-single-box itinerary-single-box--plain {{ $loop->last ? 'pb-0' : '' }}">
                                                                @if ($section['meta'])
                                                                    <h4>{{ $displayValue($section['meta']) }}</h4>
                                                                @endif
                                                                @php
                                                                    $titleText = $displayValue($section['title']);
                                                                    $titleText = trim(str_replace(['&nbsp;', '&#160;'], ' ', $titleText));
                                                                    if (!preg_match('/^ng[aà]y/i', $titleText)) {
                                                                        $titleText = '<span class="itinerary-day-label">Ngày ' . $section['index'] . ': </span>' . $titleText;
                                                                    } else {
                                                                        $titleText = preg_replace('/^(Ng[aà]y\s+\d+[:\-]*\s*)/i', '<span class="itinerary-day-label">$1</span>', $titleText);
                                                                    }
                                                                @endphp
                                                                <h3>{!! $titleText !!}</h3>
                                                                <div class="product-detail-richtext">
                                                                    {!! $section['body'] !!}
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="product-detail-richtext">
                                                        {!! $tab['content'] !!}
                                                    </div>
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if ($plainText($product->map_embed))
                                    <div class="listing-item">
                                        <h4 class="mb-4">Location Map</h4>
                                        <div class="contact-map">
                                            {!! $product->map_embed !!}
                                        </div>
                                    </div>
                                @endif


                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mobile-booking-overlay js-mobile-booking-overlay"></div>
                            <div class="mobile-bottom-booking-bar d-lg-none">
                                <div class="mbb-price-col">
                                    <span class="mbb-price-label">Giá từ</span>
                                    <div class="mbb-price-value">{!! $product->price ? number_format((float) $product->price, 0, ',', '.') . ' ₫<small>/người</small>' : 'Liên hệ' !!}</div>
                                </div>
                                <div class="mbb-actions-col">
                                    <button type="button" class="btn btn-primary mbb-btn mbb-book-btn js-mobile-booking-open">Đặt tour</button>
                                    <button type="button" class="btn btn-light mbb-btn mbb-consult-btn text-primary" data-bs-toggle="modal" data-bs-target="#tourBookingModal">Tư vấn</button>
                                </div>
                            </div>
                            
                            <div class="tour-booking-sticky">
                            <div class="booking-sidebar listing-side-content booking-sidebar-cta booking-sidebar-new js-booking-sidebar" data-child-percent="{{ $product->child_price_percent ?? 0 }}" data-infant-percent="{{ $product->infant_price_percent ?? 0 }}">
                                <div class="booking-item booking-item-new">
                                    <!-- Header -->
                                    <div class="booking-sidebar-header">
                                        <button type="button" class="mobile-booking-close js-mobile-booking-close"><i class="fas fa-times"></i></button>
                                        <div class="booking-price-label">Giá từ</div>
                                        <div class="d-flex align-items-baseline mb-3">
                                            <h3 class="booking-price-value js-main-price-display">{{ $formatPrice($product->price) }}</h3>
                                            <span class="booking-price-unit">/người</span>
                                        </div>
                                        <hr class="booking-header-divider">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="booking-tour-code-label">Mã tour:</span>
                                            <strong class="booking-tour-code-value">{{ $product->tour_code ?: 'Đang cập nhật' }}</strong>
                                        </div>
                                    </div>

                                    <form action="{{ route('frontend.checkout.step1', $product->id) }}" method="GET" class="booking-sidebar-body">
                                        @if (session('customer_inquiry_success'))
                                            <div class="alert alert-success mb-3">{{ session('customer_inquiry_success') }}</div>
                                        @endif

                                        @if ($product->departurePrices && $product->departurePrices->isNotEmpty())
                                        <div class="mb-4">
                                            <div class="booking-section-title">Ngày khởi hành</div>
                                            <div class="d-flex gap-2 flex-wrap">
                                                @foreach ($product->departurePrices as $index => $dp)
                                                @php
                                                    $dateParts = explode('/', $dp->departure_date);
                                                    $shortDate = count($dateParts) >= 2 ? $dateParts[0] . '/' . $dateParts[1] : $dp->departure_date;
                                                    $rawPrice = $dp->price ?: $product->price;
                                                    $displayPrice = $rawPrice > 0 ? ($rawPrice < 1000 ? $rawPrice : $rawPrice / 1000000) : 0;
                                                    $realPrice = $rawPrice > 0 ? ($rawPrice < 1000 ? $rawPrice * 1000000 : $rawPrice) : 0;
                                                @endphp
                                                <label class="btn btn-outline-primary p-2 text-center booking-date-btn {{ $index === 0 ? 'active' : '' }}" data-price="{{ $realPrice }}" style="cursor: pointer;">
                                                    <input type="radio" name="departure_date" value="{{ $dp->departure_date }}" class="d-none" {{ $index === 0 ? 'checked' : '' }}>
                                                    <div class="booking-date-value">{{ $shortDate }}</div>
                                                    <div class="booking-date-price">{{ $displayPrice > 0 ? rtrim(rtrim(number_format($displayPrice, 1, ',', '.'), '0'), ',') . 'tr' : 'Liên hệ' }}</div>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        @elseif($departureDateText)
                                        <div class="mb-4">
                                            <div class="booking-section-title">Ngày khởi hành</div>
                                            <label class="btn btn-primary p-2 text-center booking-date-btn active">
                                                <input type="radio" name="departure_date" value="{{ $displayValue($departureDateText) }}" class="d-none" checked>
                                                <div class="booking-date-value">{{ $displayValue($departureDateText) }}</div>
                                                @php
                                                    $fallbackPrice = $product->price > 0 ? $product->price / 1000000 : 0;
                                                @endphp
                                                <div class="booking-date-price">{{ $fallbackPrice > 0 ? rtrim(rtrim(number_format($fallbackPrice, 1, ',', '.'), '0'), ',') . 'tr' : 'Liên hệ' }}</div>
                                            </label>
                                        </div>
                                        @endif

                                        <div class="mb-3">
                                            <div class="booking-section-title">Số người</div>
                                            
                                            <!-- Người lớn -->
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <div class="booking-qty-label">Người lớn</div>
                                                    <div class="booking-qty-price js-adult-price-display">{{ $formatPrice($product->price) }}</div>
                                                </div>
                                                <div class="input-group" style="width: 100px;">
                                                    <button class="btn btn-outline-secondary btn-sm rounded-circle js-qty-minus booking-qty-btn" type="button">-</button>
                                                    <input type="text" name="adult_quantity" class="form-control form-control-sm text-center border-0 bg-transparent js-qty-input js-adult-input booking-qty-input" value="1" readonly data-price="{{ $product->price }}" data-label="Người lớn">
                                                    <button class="btn btn-outline-secondary btn-sm rounded-circle js-qty-plus booking-qty-btn" type="button">+</button>
                                                </div>
                                            </div>

                                            <!-- Trẻ em -->
                                            @if ($product->child_price_percent)
                                            @php $childPrice = $product->price * $product->child_price_percent / 100; @endphp
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <div class="booking-qty-label">Trẻ em</div>
                                                    <div class="booking-qty-price js-child-price-display">{{ $formatPrice($childPrice) }}</div>
                                                </div>
                                                <div class="input-group" style="width: 100px;">
                                                    <button class="btn btn-outline-secondary btn-sm rounded-circle js-qty-minus booking-qty-btn" type="button">-</button>
                                                    <input type="text" name="child_quantity" class="form-control form-control-sm text-center border-0 bg-transparent js-qty-input js-child-input booking-qty-input" value="0" readonly data-price="{{ $childPrice }}" data-label="Trẻ em">
                                                    <button class="btn btn-outline-secondary btn-sm rounded-circle js-qty-plus booking-qty-btn" type="button">+</button>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Em bé -->
                                            @if ($product->infant_price_percent)
                                            @php $infantPrice = $product->price * $product->infant_price_percent / 100; @endphp
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <div class="booking-qty-label">Em bé</div>
                                                    <div class="booking-qty-price js-infant-price-display">{{ $formatPrice($infantPrice) }}</div>
                                                </div>
                                                <div class="input-group" style="width: 100px;">
                                                    <button class="btn btn-outline-secondary btn-sm rounded-circle js-qty-minus booking-qty-btn" type="button">-</button>
                                                    <input type="text" name="infant_quantity" class="form-control form-control-sm text-center border-0 bg-transparent js-qty-input js-infant-input booking-qty-input" value="0" readonly data-price="{{ $infantPrice }}" data-label="Em bé">
                                                    <button class="btn btn-outline-secondary btn-sm rounded-circle js-qty-plus booking-qty-btn" type="button">+</button>
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="p-3 mb-4 rounded booking-summary-box">
                                            <div class="js-booking-summary-list mb-1 booking-summary-list">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Người lớn x 1</span>
                                                    <strong class="booking-summary-item-price">{{ $formatPrice($product->price) }}</strong>
                                                </div>
                                            </div>
                                            <hr class="my-2 booking-summary-divider">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="booking-total-label">Tổng cộng</span>
                                                <strong class="js-booking-total booking-total-value">{{ $formatPrice($product->price) }}</strong>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 mb-4">
                                            <button type="submit" class="btn btn-primary w-50 booking-action-btn m-0">
                                                Đặt tour ngay
                                            </button>
                                            <button type="button" class="btn btn-outline-primary w-50 booking-action-btn m-0" data-bs-toggle="modal" data-bs-target="#tourBookingModal">
                                                Tư vấn miễn phí
                                            </button>
                                        </div>

                                        @if(!empty($settings->hotline))
                                        <div class="text-center booking-hotline-box">
                                            <i class="fas fa-phone me-2"></i> Hotline: <strong class="booking-hotline-number">{{ $settings->hotline }}</strong>
                                        </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            </div>

                            @push('scripts')
                            <script src="{{ asset('tourit/assets/js/booking-sidebar.js') }}"></script>
                            @endpush
                        </div>
                    </div>
                    
                    @if ($relatedProducts->isNotEmpty())
                        <div class="related-tours-wrapper mt-5 pt-3 border-top">
                            <div class="listing-item">
                                <h4 class="mb-4">Tour liên quan</h4>
                                <div class="row g-4">
                                    @foreach ($relatedProducts as $relatedProduct)
                                        <div class="col-md-6 col-lg-3">
                                            @include('frontend.products._tour_card', [
                                                'product' => $relatedProduct,
                                                'currentCategoryName' => $displayValue(optional($product->category)->name, 'Tour'),
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade tour-booking-modal" id="tourBookingModal" tabindex="-1" aria-labelledby="tourBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="modal-title" id="tourBookingModalLabel">Đăng ký tư vấn tour</h4>
                        <p class="mb-0 text-muted" style="font-size: 14px;">{{ $displayValue($product->title) }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    @if (session('customer_inquiry_success'))
                        <div class="alert alert-success mb-3">{{ session('customer_inquiry_success') }}</div>
                    @endif

                    @if ($customerInquiryErrors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0 ps-3">
                                @foreach ($customerInquiryErrors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('frontend.customer-inquiries.store') }}" method="POST" class="tour-booking-popup-form">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $product->id }}">
                        <input type="hidden" name="project_title" value="{{ $displayValue($product->title) }}">
                        <input type="hidden" name="source_url" value="{{ url()->current() }}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Họ tên *</label>
                                    <input type="text" name="name" class="form-control @if($customerInquiryErrors->has('name')) is-invalid @endif" value="{{ old('name') }}" placeholder="Nhập họ tên" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Số điện thoại *</label>
                                    <input type="text" name="phone" class="form-control @if($customerInquiryErrors->has('phone')) is-invalid @endif" value="{{ old('phone') }}" placeholder="Nhập số điện thoại" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @if($customerInquiryErrors->has('email')) is-invalid @endif" value="{{ old('email') }}" placeholder="Nhập email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="form-label">Lời nhắn</label>
                                    <textarea name="message" rows="4" class="form-control @if($customerInquiryErrors->has('message')) is-invalid @endif" placeholder="Bạn cần tư vấn thêm về lịch trình, giá cả, dịch vụ...">{{ old('message') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="listing-side-btn booking-modal-actions d-flex gap-2">
                            <button type="submit" class="theme-btn flex-grow-1 m-0"><span class="far fa-paper-plane me-1"></span> Gửi yêu cầu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var gallery = document.querySelector('.tour-gallery-mosaic');

            if (gallery) {
                gallery.querySelectorAll('.popup-img').forEach(function (item) {
                    item.classList.remove('popup-img');
                    item.addEventListener('click', function (event) {
                        event.preventDefault();
                    });
                });

                gallery.classList.remove('popup-gallery');
            }

            var bookingModalElement = document.getElementById('tourBookingModal');

            if (bookingModalElement && {{ $customerInquiryErrors->any() || session('customer_inquiry_success') ? 'true' : 'false' }}) {
                var bookingModal = bootstrap.Modal.getOrCreateInstance(bookingModalElement);
                bookingModal.show();
            }
        });
    </script>
@endpush
