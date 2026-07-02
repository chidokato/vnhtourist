@extends('frontend.layouts.app')

@php
    $select2CssVersion = @filemtime(public_path('tourit/assets/vendor/select2/select2.min.css')) ?: time();
    $select2JsVersion = @filemtime(public_path('tourit/assets/vendor/select2/select2.min.js')) ?: time();
    $pageCssVersion = @filemtime(public_path('tourit/assets/css/product-category-page.css')) ?: time();
    $pageJsVersion = @filemtime(public_path('tourit/assets/js/product-category-page.js')) ?: time();
@endphp

@section('title', $pageTitle ?? $category->name)
@section('meta_description', $pageDescription ?? '')
@section('meta_keywords', $pageKeywords ?? '')

@push('styles')
    <link rel="stylesheet" href="assets/vendor/select2/select2.min.css?v={{ $select2CssVersion }}">
    <link rel="stylesheet" href="assets/css/product-category-page.css?v={{ $pageCssVersion }}">
@endpush

@push('scripts')
    <script src="assets/vendor/select2/select2.min.js?v={{ $select2JsVersion }}"></script>
    <script src="assets/js/product-category-page.js?v={{ $pageJsVersion }}"></script>
@endpush

@section('content')
    <main class="main product-category-page">
        <div class="site-breadcrumb d-none d-lg-block" style="background: url(assets/img/banner/04.jpg)">
            <div class="container pt-10">
                <h2 class="breadcrumb-title">{{ $displayValue($category->name) }}</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
                    <li class="active">{{ $displayValue($category->name) }}</li>
                </ul>
            </div>
        </div>

        <div class="py-50 mt-5 mt-lg-0">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-xl-3">
                        <aside class="sidebar-area offcanvas-lg offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterSidebarLabel">
                            <div class="offcanvas-header border-bottom d-lg-none">
                                <h5 class="offcanvas-title" id="filterSidebarLabel">Bộ lọc tour</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body p-0 flex-column">
                                <div class="widget product-filter-widget w-100 border-0">
                                <div class="product-filter-header">
                                    <h4 class="widget-title">Tìm tour nhanh</h4>
                                </div>

                                <form
                                    id="product-filter-form"
                                    class="product-filter-form"
                                    data-product-filter
                                    data-ajax-url="{{ $ajaxUrl }}"
                                >
                                    <div class="product-filter-section">
                                        <label class="product-filter-section-title" for="filter-category">Khởi hành từ</label>
                                        <div class="product-filter-select-box">
                                            <select id="filter-category" name="departure_location" class="form-select product-filter-category-select" data-filter-category-select>
                                                <option value="">Tất cả</option>
                                                @foreach ($departureLocationOptions as $option)
                                                    <option value="{{ $option['name'] }}" {{ (string) $selectedDepartureLocation === (string) $option['name'] ? 'selected' : '' }}>
                                                        {{ $displayValue($option['name']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="product-filter-section">
                                        <div class="product-filter-section-title">Điểm đến</div>
                                        <div class="product-filter-option-box" data-destination-options>
                                            @foreach ($destinationOptions as $destination)
                                                <div class="form-check custom-filter-check">
                                                    <input class="form-check-input" name="destinations[]" type="checkbox" value="{{ $destination['name'] }}" id="destination_{{ $loop->index }}" @checked(in_array($destination['name'], $selectedDestinations ?? [], true))>
                                                    <label class="form-check-label" for="destination_{{ $loop->index }}">
                                                        {{ $displayValue($destination['label'] ?? $destination['name']) }} <span>({{ $destination['count'] }})</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>


                                    <div class="product-filter-section">
                                        <label class="product-filter-section-title" for="filter-departure-date">Ngày khởi hành</label>
                                        <div class="product-filter-date-box">
                                            <input
                                                id="filter-departure-date"
                                                type="date"
                                                name="departure_date"
                                                class="form-control"
                                                value="{{ $selectedDepartureDate }}"
                                            >
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </aside>
                    </div>

                    <div class="col-lg-8 col-xl-9">
                        <div>
                            <div class="product-results-head">
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-primary d-lg-none py-2 px-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar" aria-controls="filterSidebar">
                                        <i class="fas fa-filter me-1"></i> Bộ lọc
                                    </button>
                                    <div class="product-results-status" data-product-results-status data-total="{{ $products->total() }}" style="display: flex; align-items: center;">
                                        {{ $products->total() }} tour hiển thị
                                    </div>
                                </div>
                                <div>
                                    <div class="input-group" style="max-width: 320px;">
                                        <input type="text" form="product-filter-form" name="keyword" class="form-control" placeholder="Tìm kiếm" value="{{ request('keyword') }}">
                                        <button type="submit" form="product-filter-form" class="theme-btn" style="padding: 0 20px;"><i class="far fa-search"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="product-results-body" data-product-results>
                                @include('frontend.products._listing', [
                                    'products' => $products,
                                    'currentCategoryName' => $category->name,
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
