@extends('frontend.layouts.app')

@section('title', $pageTitle ?? $category->name)
@section('meta_description', $pageDescription ?? '')
@section('meta_keywords', $pageKeywords ?? '')

@push('styles')
    <link rel="stylesheet" href="assets/css/product-category-page.css">
@endpush

@push('scripts')
    <script src="assets/js/product-category-page.js"></script>
@endpush



@section('content')
    <main class="main product-category-page">
        <div class="site-breadcrumb" style="background: url(assets/img/banner/04.jpg)">
            <div class="container pt-10">
                <h2 class="breadcrumb-title">{{ $displayValue($category->name) }}</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home') }}">Trang chu</a></li>
                    <li class="active">{{ $displayValue($category->name) }}</li>
                </ul>
            </div>
        </div>

        <div class="py-50">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-xl-3">
                        <aside class="sidebar-area">
                            <div class="widget product-filter-widget">
                                <div class="product-filter-header">
                                    <h4 class="widget-title">Tìm tuor nhanh</h4>
                                </div>

                                <form
                                    id="product-filter-form"
                                    class="product-filter-form"
                                    data-product-filter
                                    data-ajax-url="{{ $ajaxUrl }}"
                                >
                                    <div class="product-filter-section">
                                        <label class="product-filter-section-title" for="filter-category">Danh mục</label>
                                        <div class="product-filter-select-box">
                                            <select id="filter-category" name="category_id" class="form-select">
                                                @foreach ($filterCategoryOptions as $option)
                                                    <option value="{{ $option['id'] }}" {{ (string) $selectedCategoryId === (string) $option['id'] ? 'selected' : '' }}>
                                                        {{ str_repeat('-- ', $option['depth']) . $displayValue($option['name']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="product-filter-section">
                                        <div class="product-filter-section-title">Điểm đến</div>
                                        <div class="product-filter-option-box" data-destination-options>
                                            @foreach ($destinationOptions as $destination)
                                                <label class="product-filter-option product-filter-option-checkbox">
                                                    <input
                                                        type="checkbox"
                                                        name="destinations[]"
                                                        value="{{ $destination }}"
                                                        @checked(in_array($destination, $selectedDestinations ?? [], true))
                                                    >
                                                    
                                                </label>
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

                                    <!-- <div class="product-filter-actions">
                                        <button type="submit" class="theme-btn">Tim kiem</button>
                                        <button type="button" class="theme-btn theme-btn-outline" data-filter-reset>Lam moi</button>
                                    </div> -->
                                </form>
                            </div>
                        </aside>
                    </div>

                    <div class="col-lg-8 col-xl-9">
                        <div>
                            <div class="product-results-head">
                                <div class="product-results-status" data-product-results-status style="display: flex; align-items: center;">
                                    {{ $products->total() }} tour
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
