@extends('frontend.layouts.app')

@php
    $customerInquiryErrors = $errors->getBag('customerInquiry');

    $resolveImage = function ($value) {
        return \App\Support\MediaManager::publicUrl($value);
    };

    $plainText = function ($value) {
        $text = trim(strip_tags((string) $value));

        return $text !== '' ? $text : null;
    };

    $formatPrice = function ($value) {
        return $value !== null ? number_format((float) $value, 0, ',', '.') . ' d' : 'Lien he';
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
        $galleryItems = collect([[
            'src' => asset('tourit/assets/img/tour/01.jpg'),
            'alt' => $displayValue($product->title),
        ]]);
    }

    $heroImage = $galleryItems->first()['src'];

    $categoryUrl = $product->category?->slug
        ? route('frontend.categories.show', $product->category->slug)
        : route('frontend.home');

    $departureText = $plainText($product->departure_location) ?: ($plainText($product->address) ?: 'Dang cap nhat');
    $destinationText = $plainText($product->destination) ?: ($plainText($product->attractions) ?: 'Dang cap nhat');
    $durationText = $plainText($product->duration) ?: 'Dang cap nhat';
    $transportText = $plainText($product->transport) ?: 'Dang cap nhat';
    $itineraryText = $plainText($product->itinerary) ?: 'Dang cap nhat';
    $publishedText = optional($product->published_at)->format('d/m/Y') ?: 'Dang cap nhat';
    $departureDateText = $product->departure_date ? optional($product->departure_date)->format('d/m/Y') : null;

    $addressParts = array_filter([
        $plainText($product->address),
        $plainText(optional($product->ward)->name),
        $plainText(optional($product->province)->name),
    ]);
    $locationText = count($addressParts) ? implode(', ', $addressParts) : 'Dang cap nhat';
    $headerMetaText = $plainText($product->itinerary) ?: ($locationText !== 'Dang cap nhat' ? $locationText : 'Dang cap nhat');

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
    <link rel="stylesheet" href="assets/css/product-show-page.css">
@endpush

@section('content')
    <main class="main product-show-page">
        <div class="site-breadcrumb" style="background: url({{ $heroImage }}) center center / cover no-repeat;">
            <div class="container">
                <h2 class="breadcrumb-title">{{ $displayValue($product->title) }}</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home') }}">Trang chu</a></li>
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
                                

                                <div class="listing-header">
                                    <div class="listing-header-info">
                                        <h4 class="listing-title">{{ $displayValue($product->title) }}</h4>
                                        <p class="listing-location"><i class="far fa-location-dot"></i> {{ $displayValue($headerMetaText) }}</p>
                                    </div>
                                    <div class="listing-rate">
                                        <span class="badge"><i class="far fa-star"></i> {{ $product->is_featured ? '5.0' : '4.8' }}</span>
                                        <span class="listing-rate-type">{{ $product->is_featured ? 'Noi bat' : 'Pho bien' }}</span>
                                        <span class="listing-rate-review">({{ $displayValue(optional($product->category)->name, 'Tour') }})</span>
                                    </div>
                                </div>

                                <div class="listing-item">
                                    <div class="row g-4">
                                        <div class="col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-clock"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Duration</h6>
                                                    <span>{{ $displayValue($durationText) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-calendar-days"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Date</h6>
                                                    <span>{{ $displayValue($departureDateText ?: $publishedText) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-plane-departure"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Departure</h6>
                                                    <span>{{ $displayValue($departureText) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-earth-americas"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Destination</h6>
                                                    <span>{{ $displayValue($destinationText) }}</span>
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
                                                                <span>Ngày {{ $section['index'] }}</span>
                                                                @if ($section['meta'])
                                                                    <h4>{{ $displayValue($section['meta']) }}</h4>
                                                                @endif
                                                                <h3>{{ $displayValue($section['title']) }}</h3>
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

                                @if ($relatedProducts->isNotEmpty())
                                    <div class="listing-item">
                                        <h4 class="mb-4">Tour lien quan</h4>
                                        <div class="row g-4">
                                            @foreach ($relatedProducts as $relatedProduct)
                                                @php
                                                    $relatedImage = $resolveImage($relatedProduct->image) ?: asset('tourit/assets/img/tour/01.jpg');
                                                @endphp
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="hotel-item">
                                                        <div class="hotel-img">
                                                            <a href="{{ $relatedProduct->frontend_url }}">
                                                                <img src="{{ $relatedImage }}" alt="{{ $displayValue($relatedProduct->title) }}">
                                                            </a>
                                                        </div>
                                                        <div class="hotel-content">
                                                            <h4 class="hotel-title">
                                                                <a href="{{ $relatedProduct->frontend_url }}">{{ $displayValue($relatedProduct->title) }}</a>
                                                            </h4>
                                                            <div class="hotel-bottom">
                                                                <div class="hotel-price">
                                                                    <span class="hotel-price-amount">{{ $formatPrice($relatedProduct->price) }}</span>
                                                                </div>
                                                                <div class="hotel-text-btn">
                                                                    <a href="{{ $relatedProduct->frontend_url }}">Xem chi tiet <i class="far fa-arrow-right"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="tour-booking-sticky">
                            <div class="booking-sidebar listing-side-content booking-sidebar-cta">
                                <div class="booking-item">
                                    <div class="listing-price">
                                        <h4 class="listing-price-tag">{{ $product->is_featured ? 'Bestseller' : 'Tour hot' }}</h4>
                                        <div class="listing-price-amount">
                                            From <span>{{ $formatPrice($product->price) }}</span>
                                        </div>
                                    </div>

                                    @if (session('customer_inquiry_success'))
                                        <div class="alert alert-success mb-3">{{ session('customer_inquiry_success') }}</div>
                                    @endif

                                    <ul class="tour-cta-meta">
                                        <li>
                                            <span>Ngay khoi hanh</span>
                                            <strong>{{ $displayValue($departureDateText ?: $publishedText) }}</strong>
                                        </li>
                                        <li>
                                            <span>Phuong tien</span>
                                            <strong>{{ $displayValue($transportText) }}</strong>
                                        </li>
                                    </ul>

                                    <div class="listing-side-btn booking-sidebar-cta-actions">
                                        <button type="button" class="theme-btn" data-bs-toggle="modal" data-bs-target="#tourBookingModal">
                                            <span class="far fa-paper-plane"></span> Dang ky ngay
                                        </button>
                                        <a href="{{ route('frontend.contact') }}" class="border-btn"><i class="far fa-envelope"></i> Lien he ngay</a>
                                    </div>
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade tour-booking-modal" id="tourBookingModal" tabindex="-1" aria-labelledby="tourBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="modal-title" id="tourBookingModalLabel">Dang ky tu van tour</h4>
                        <p class="mb-0">{{ $displayValue($product->title) }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Dong"></button>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ngay khoi hanh</label>
                                    <div class="form-group-icon">
                                        <input type="text" class="form-control" value="{{ $displayValue($departureDateText ?: $publishedText) }}" readonly>
                                        <i class="fal fa-calendar-days"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phuong tien</label>
                                    <div class="form-group-icon">
                                        <input type="text" class="form-control" value="{{ $displayValue($transportText) }}" readonly>
                                        <i class="fal fa-bus"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ho ten</label>
                                    <input type="text" name="name" class="form-control @if($customerInquiryErrors->has('name')) is-invalid @endif" value="{{ old('name') }}" placeholder="Nhap ho ten">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">So dien thoai</label>
                                    <input type="text" name="phone" class="form-control @if($customerInquiryErrors->has('phone')) is-invalid @endif" value="{{ old('phone') }}" placeholder="Nhap so dien thoai">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @if($customerInquiryErrors->has('email')) is-invalid @endif" value="{{ old('email') }}" placeholder="Nhap email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Noi dung</label>
                                    <textarea name="message" rows="4" class="form-control @if($customerInquiryErrors->has('message')) is-invalid @endif" placeholder="Can tu van them ve lich trinh, gia, dich vu...">{{ old('message') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="listing-side-btn booking-modal-actions">
                            <button type="submit" class="theme-btn"><span class="far fa-paper-plane"></span> Gui yeu cau</button>
                            <button type="button" class="border-btn" data-bs-dismiss="modal"><i class="far fa-xmark"></i> Dong</button>
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
