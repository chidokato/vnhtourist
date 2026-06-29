
@extends('frontend.layouts.app')

@section('title', $pageTitle ?? 'Vietnam homes Tourist')
@section('meta_description', $pageDescription ?? '')
@section('meta_keywords', $pageKeywords ?? '')

@section('content')
    <main class="main">

        <!-- hero area -->
        <div class="hero-section">
            <div class="hero-slider-2 owl-carousel owl-theme">
                @if(isset($sliders) && $sliders->count() > 0)
                    @foreach($sliders as $slider)
                        <div class="hero-single" style="background-image: url('{{ \App\Support\MediaManager::publicUrl($slider->image) }}')">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-12 mx-auto">
                                        <div class="hero-content text-center">
                                            <div class="hero-content-wrapper">
                                                @if($slider->title)
                                                    <h1 class="hero-title">{{ $slider->title }}</h1>
                                                @endif
                                                @if($slider->subtitle)
                                                    <p>{{ $slider->subtitle }}</p>
                                                @endif
                                                @if($slider->link)
                                                    <a href="{{ $slider->link }}" class="theme-btn mt-4">{{ $slider->button_text ?: 'Khám phá ngay' }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="hero-single" style="background-image: url('{{ asset('tourit/assets/img/slider.webp') }}')">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-12 mx-auto">
                                    <div class="hero-content text-center">
                                        <div class="hero-content-wrapper">
                                            <h1 class="hero-title">Find Your Next Place To Visit</h1>
                                            <p>Discover Amzaing Places At Exclusive Deals And Enjoy Your Holiday</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- hero area end -->


        <!-- search area -->
        <div class="search-area">
            <div class="container">
                <div class="search-wrapper">
                    <!-- search header -->
                    <div class="search-header">
                        <div class="search-nav">
                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-tab-7" data-bs-toggle="pill"
                                        data-bs-target="#pills-7" type="button" role="tab" aria-controls="pills-7"
                                        aria-selected="true"><i class="far fa-earth-americas"></i>Tour</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- search header end -->

                    <!-- tab content -->
                    <div class="tab-content" id="pills-tabContent">

                        <!-- tab 2 -->
                        <div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-tab-2"
                            tabindex="0">
                            <div class="hotel-search">
                                <div class="search-form">
                                    <form action="#">
                                        <div class="hotel-search-wrapper">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Khách sạn hoặc điểm đến</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="destination" class="form-control"
                                                                value="Capital Elite, Hà Nội">
                                                            <i class="fal fa-earth-americas"></i>
                                                        </div>
                                                        <p>Nhập khách sạn hoặc khu vực bạn muốn lưu trú</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="search-form-date">
                                                            <div class="search-form-journey">
                                                                <label>Ngày nhận phòng</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="journey-date"
                                                                        class="form-control date-picker journey-date">
                                                                    <i class="fal fa-calendar-days"></i>
                                                                </div>
                                                                <p class="journey-day-name"></p>
                                                            </div>
                                                            <div class="search-form-return">
                                                                <label>Ngày trả phòng</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="return-date"
                                                                        class="form-control date-picker return-date">
                                                                </div>
                                                                <p class="return-day-name"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group dropdown passenger-box">
                                                        <div class="passenger-class" role="menu"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <label>Phòng, khách</label>
                                                            <div class="form-group-icon">
                                                                <div class="passenger-total">
                                                                    <span class="passenger-total-room">2</span> phòng,
                                                                    <span class="passenger-total-amount">2</span> khách
                                                                </div>
                                                                <i class="fal fa-user-tie-hair"></i>
                                                            </div>
                                                            <p class="passenger-class-name">Phòng đôi</p>
                                                        </div>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Người lớn</h6>
                                                                        <p>Từ 12 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="adult"
                                                                            class="qty-amount passenger-adult" value="2"
                                                                            readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Trẻ em</h6>
                                                                        <p>Từ 2-12 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="children"
                                                                            class="qty-amount passenger-children"
                                                                            value="0" readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Em bé</h6>
                                                                        <p>Dưới 2 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="infant"
                                                                            class="qty-amount passenger-infant"
                                                                            value="0" readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Số phòng</h6>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="room"
                                                                            class="qty-amount passenger-room" value="2"
                                                                            readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <h6 class="mb-3 mt-2">Loại phòng</h6>
                                                                <div class="passenger-class-info">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            value="Đơn" name="room-type"
                                                                            id="room-type1">
                                                                        <label class="form-check-label"
                                                                            for="room-type1">
                                                                            Đơn
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" checked
                                                                            type="radio" value="Đôi"
                                                                            name="room-type" id="room-type2">
                                                                        <label class="form-check-label"
                                                                            for="room-type2">
                                                                            Đôi
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            value="Cao cấp" name="room-type"
                                                                            id="room-type3">
                                                                        <label class="form-check-label"
                                                                            for="room-type3">
                                                                            Cao cấp
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="search-btn">
                                                <button type="submit" class="theme-btn"><span
                                                        class="far fa-search"></span>Đăng ký đặt khách sạn</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- tab 3 -->
                        <div class="tab-pane fade" id="pills-3" role="tabpanel" aria-labelledby="pills-tab-3"
                            tabindex="0">
                            <div class="activity-search">
                                <div class="search-form">
                                    <form action="#">
                                        <div class="activity-search-wrapper">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Địa điểm</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="location" class="form-control"
                                                                value="New York, United States">
                                                            <i class="fal fa-earth-americas"></i>
                                                        </div>
                                                        <p>Bạn muốn đi đâu?</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="search-form-date">
                                                            <div class="search-form-journey">
                                                                <label>Ngày nhận phòng</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="journey-date"
                                                                        class="form-control date-picker journey-date">
                                                                    <i class="fal fa-calendar-days"></i>
                                                                </div>
                                                                <p class="journey-day-name"></p>
                                                            </div>
                                                            <div class="search-form-return">
                                                                <label>Ngày trả phòng</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="return-date"
                                                                        class="form-control date-picker return-date">
                                                                </div>
                                                                <p class="return-day-name"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group dropdown passenger-box">
                                                        <div class="passenger-class" role="menu"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <label>Phòng, khách</label>
                                                            <div class="form-group-icon">
                                                                <div class="passenger-total">
                                                                    <span class="passenger-total-room">2</span> phòng,
                                                                    <span class="passenger-total-amount">2</span> khách
                                                                </div>
                                                                <i class="fal fa-user-tie-hair"></i>
                                                            </div>
                                                            <p class="passenger-class-name">Phòng đôi</p>
                                                        </div>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Người lớn</h6>
                                                                        <p>Từ 12 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="adult"
                                                                            class="qty-amount passenger-adult" value="2"
                                                                            readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Trẻ em</h6>
                                                                        <p>Từ 2-12 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="children"
                                                                            class="qty-amount passenger-children"
                                                                            value="0" readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Em bé</h6>
                                                                        <p>Dưới 2 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="infant"
                                                                            class="qty-amount passenger-infant"
                                                                            value="0" readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Số phòng</h6>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="room"
                                                                            class="qty-amount passenger-room" value="2"
                                                                            readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <h6 class="mb-3 mt-2">Loại phòng</h6>
                                                                <div class="passenger-class-info">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            value="Phòng đơn" name="room-type"
                                                                            id="room-type4">
                                                                        <label class="form-check-label"
                                                                            for="room-type4">
                                                                            Single Room
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" checked
                                                                            type="radio" value="Phòng đôi"
                                                                            name="room-type" id="room-type5">
                                                                        <label class="form-check-label"
                                                                            for="room-type5">
                                                                            Double Room
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            value="Phòng cao cấp" name="room-type"
                                                                            id="room-type6">
                                                                        <label class="form-check-label"
                                                                            for="room-type6">
                                                                            Deluxe Room
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="search-btn">
                                                <button type="submit" class="theme-btn"><span
                                                        class="far fa-search"></span>Tìm ngay</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- tab 4 -->
                        <div class="tab-pane fade" id="pills-4" role="tabpanel" aria-labelledby="pills-tab-4"
                            tabindex="0">
                            <div class="holiday-search">
                                <div class="search-form">
                                    <form action="#">
                                        <!-- holiday type -->
                                        <div class="flight-type">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" checked value="holiday1"
                                                    name="holiday-type" id="holiday-type1">
                                                <label class="form-check-label" for="holiday-type1">
                                                    Tour + Khách sạn + Xe
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="holiday2"
                                                    name="holiday-type" id="holiday-type2">
                                                <label class="form-check-label" for="holiday-type2">
                                                    Tour + Khách sạn
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="holiday3"
                                                    name="holiday-type" id="holiday-type3">
                                                <label class="form-check-label" for="holiday-type3">
                                                    Tour + Xe
                                                </label>
                                            </div>
                                        </div>
                                        <!-- holiday type end -->
                                        <div class="holiday-search-wrapper">
                                            <div class="flight-search-item">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label>Nơi khởi hành</label>
                                                            <div class="form-group-icon">
                                                                <input type="text" name="from-destination"
                                                                    class="form-control swap-from" value="New York">
                                                                <i class="fal fa-plane-departure"></i>
                                                            </div>
                                                            <p>JFK - John F. Kennedy International Airport
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <div class="search-form-swap"><i class="far fa-repeat"></i>
                                                            </div>
                                                            <label>Điểm đến</label>
                                                            <div class="form-group-icon">
                                                                <input type="text" name="to-destination"
                                                                    class="form-control swap-to" value="Los Angeles">
                                                                <i class="fal fa-plane-arrival"></i>
                                                            </div>
                                                            <p>LAX - Los Angeles International Airport</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <div class="search-form-date">
                                                                <div class="search-form-journey">
                                                                    <label>Ngày nhận phòng</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="journey-date"
                                                                            class="form-control date-picker journey-date">
                                                                        <i class="fal fa-calendar-days"></i>
                                                                    </div>
                                                                    <p class="journey-day-name"></p>
                                                                </div>
                                                                <div class="search-form-return">
                                                                    <label>Ngày trả phòng</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="return-date"
                                                                            class="form-control date-picker return-date">
                                                                    </div>
                                                                    <p class="return-day-name"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group dropdown passenger-box">
                                                            <div class="passenger-class" role="menu"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <label>Phòng, khách</label>
                                                                <div class="form-group-icon">
                                                                    <div class="passenger-total">
                                                                        <span class="passenger-total-room">2</span>
                                                                        phòng,
                                                                        <span class="passenger-total-amount">2</span>
                                                                        khách
                                                                    </div>
                                                                    <i class="fal fa-user-tie-hair"></i>
                                                                </div>
                                                                <p class="passenger-class-name">Phòng đôi</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Người lớn</h6>
                                                                            <p>Từ 12 tuổi</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button" class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="adult"
                                                                                class="qty-amount passenger-adult"
                                                                                value="2" readonly>
                                                                            <button type="button" class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Trẻ em</h6>
                                                                            <p>Từ 2-12 tuổi</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button" class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="children"
                                                                                class="qty-amount passenger-children"
                                                                                value="0" readonly>
                                                                            <button type="button" class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Em bé</h6>
                                                                            <p>Dưới 2 tuổi</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button" class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="infant"
                                                                                class="qty-amount passenger-infant"
                                                                                value="0" readonly>
                                                                            <button type="button" class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Số phòng</h6>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button" class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="room"
                                                                                class="qty-amount passenger-room"
                                                                                value="2" readonly>
                                                                            <button type="button" class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <h6 class="mb-3 mt-2">Loại phòng</h6>
                                                                    <div class="passenger-class-info">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                value="Phòng đơn" name="room-type"
                                                                                id="room-type7">
                                                                            <label class="form-check-label"
                                                                                for="room-type7">
                                                                                Single Room
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" checked
                                                                                type="radio" value="Phòng đôi"
                                                                                name="room-type" id="room-type8">
                                                                            <label class="form-check-label"
                                                                                for="room-type8">
                                                                                Double Room
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                value="Phòng cao cấp" name="room-type"
                                                                                id="room-type9">
                                                                            <label class="form-check-label"
                                                                                for="room-type9">
                                                                                Deluxe Room
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="search-btn">
                                                <button type="submit" class="theme-btn"><span
                                                        class="far fa-search"></span>Đăng ký gói nghỉ dưỡng</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- tab 5 -->
                        <div class="tab-pane fade" id="pills-5" role="tabpanel" aria-labelledby="pills-tab-5"
                            tabindex="0">
                            <div class="car-search">
                                <div class="search-form">
                                    <form action="#">
                                        <div class="car-search-wrapper">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Địa điểm nhận xe</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="picking-up" class="form-control"
                                                                value="New York, United States">
                                                            <i class="fal fa-location-dot"></i>
                                                        </div>
                                                        <p>Nhập nơi bạn muốn nhận xe</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="search-form-date">
                                                            <div class="search-form-journey">
                                                                <label>Ngày nhận xe</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="pickup-date"
                                                                        class="form-control date-picker journey-date">
                                                                    <i class="fal fa-calendar-days"></i>
                                                                </div>
                                                                <p class="journey-day-name"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Giờ nhận xe</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="pick-up-time"
                                                                class="form-control time-picker" value="11:00 PM">
                                                            <i class="fal fa-clock"></i>
                                                        </div>
                                                        <p>Chọn thời gian nhận xe</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group mt-lg-4">
                                                        <label>Địa điểm trả xe</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="picking-up" class="form-control"
                                                                value="New York, United States">
                                                            <i class="fal fa-location-dot"></i>
                                                        </div>
                                                        <p>Nhập nơi bạn muốn trả xe</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group mt-lg-4">
                                                        <div class="search-form-date">
                                                            <div class="search-form-journey">
                                                                <label>Ngày trả xe</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="pickup-date"
                                                                        class="form-control date-picker journey-date">
                                                                    <i class="fal fa-calendar-days"></i>
                                                                </div>
                                                                <p class="journey-day-name"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group mt-lg-4">
                                                        <label>Giờ trả xe</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="pick-up-time"
                                                                class="form-control time-picker" value="11:00 PM">
                                                            <i class="fal fa-clock"></i>
                                                        </div>
                                                        <p>Chọn thời gian trả xe</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="search-btn">
                                                <button type="submit" class="theme-btn"><span
                                                        class="far fa-search"></span>Đăng ký đặt xe</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- tab 6 -->
                        <div class="tab-pane fade" id="pills-6" role="tabpanel" aria-labelledby="pills-tab-6"
                            tabindex="0">
                            <div class="cruise-search">
                                <div class="search-form">
                                    <form action="#">
                                        <div class="cruise-search-wrapper">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Hành trình du thuyền</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="destination" class="form-control"
                                                                value="New York, United States">
                                                            <i class="fal fa-earth-americas"></i>
                                                        </div>
                                                        <p>Nhập hành trình du thuyền bạn muốn đặt</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="search-form-date">
                                                            <div class="search-form-journey">
                                                                <label>Ngày đi</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="journey-date"
                                                                        class="form-control date-picker journey-date">
                                                                    <i class="fal fa-calendar-days"></i>
                                                                </div>
                                                                <p class="journey-day-name"></p>
                                                            </div>
                                                            <div class="search-form-return">
                                                                <label>Ngày về</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="return-date"
                                                                        class="form-control date-picker return-date">
                                                                </div>
                                                                <p class="return-day-name"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group dropdown passenger-box">
                                                        <div class="passenger-class" role="menu"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <label>Du khách</label>
                                                            <div class="form-group-icon">
                                                                <div class="passenger-total">
                                                                    <span class="passenger-total-amount">2</span>
                                                                    Du khách
                                                                </div>
                                                                <i class="fal fa-user-tie-hair"></i>
                                                            </div>
                                                            <p class="passenger-class-name">In Cabin</p>
                                                        </div>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Người lớn</h6>
                                                                        <p>Từ 12 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="adult"
                                                                            class="qty-amount passenger-adult" value="2"
                                                                            readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Trẻ em</h6>
                                                                        <p>Từ 2-12 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="children"
                                                                            class="qty-amount passenger-children"
                                                                            value="0" readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Em bé</h6>
                                                                        <p>Dưới 2 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="infant"
                                                                            class="qty-amount passenger-infant"
                                                                            value="0" readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <h6 class="mb-3 mt-2">Hạng du thuyền</h6>
                                                                <div class="passenger-class-info">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            value="In Cabin" name="cruise-class"
                                                                            id="cruise-class1">
                                                                        <label class="form-check-label"
                                                                            for="cruise-class1">
                                                                            In Cabin
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" checked
                                                                            type="radio" value="In Chair"
                                                                            name="cruise-class" id="cruise-class2">
                                                                        <label class="form-check-label"
                                                                            for="cruise-class2">
                                                                            In Chair
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            value="Hạng nhất" name="cruise-class"
                                                                            id="cruise-class3">
                                                                        <label class="form-check-label"
                                                                            for="cruise-class3">
                                                                            Hạng nhất
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="search-btn">
                                                <button type="submit" class="theme-btn"><span
                                                        class="far fa-search"></span>Đăng ký du thuyền</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- tab 7 -->
                        <div class="tab-pane fade show active" id="pills-7" role="tabpanel" aria-labelledby="pills-tab-7"
                            tabindex="0">
                            <div class="tour-search">
                                <div class="search-form">
                                    <form action="{{ url('/tour-trong-nuoc') }}" method="GET">
                                        <div class="tour-search-wrapper">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label>Điểm đến tour</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="destination" class="form-control"
                                                                value="" placeholder="Đà Nẵng">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="search-form-date">
                                                            <div class="search-form-journey">
                                                                <label>Từ ngày</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="journey-date"
                                                                        class="form-control date-picker journey-date">
                                                                </div>
                                                            </div>
                                                            <div class="search-form-return">
                                                                <label>Đến ngày</label>
                                                                <div class="form-group-icon">
                                                                    <input type="text" name="return-date"
                                                                        class="form-control date-picker return-date">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group dropdown passenger-box">
                                                        <div class="passenger-class form-group " role="menu"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <label>Khách tham gia</label>
                                                            <div class="form-group-icon">
                                                                <div class="passenger-total form-control">
                                                                    <span class="passenger-total-amount">2</span> khách đăng ký
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Người lớn</h6>
                                                                        <p>Từ 12 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="adult"
                                                                            class="qty-amount passenger-adult" value="2"
                                                                            readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Trẻ em</h6>
                                                                        <p>Từ 2-12 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="children"
                                                                            class="qty-amount passenger-children"
                                                                            value="0" readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dropdown-item">
                                                                <div class="passenger-item">
                                                                    <div class="passenger-info">
                                                                        <h6>Em bé</h6>
                                                                        <p>Dưới 2 tuổi</p>
                                                                    </div>
                                                                    <div class="passenger-qty">
                                                                        <button type="button" class="minus-btn"><i
                                                                                class="far fa-minus"></i></button>
                                                                        <input type="text" name="infant"
                                                                            class="qty-amount passenger-infant"
                                                                            value="0" readonly>
                                                                        <button type="button" class="plus-btn"><i
                                                                                class="far fa-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <button type="submit" class="theme-btn btn-submit"><span
                                                        class="far fa-search"></span>Tìm kiếm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- tab content end -->
                </div>
            </div>
        </div>
        <!-- search area end -->


        <!-- about-area -->
        <div class="about-area py-120 d-none">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                            <div class="about-img">
                                <div class="row">
                                    <div class="col-6">
                                        <img class="img-1" src="assets/img/about/01.jpg" alt="">
                                    </div>
                                    <div class="col-6">
                                        <img class="img-2" src="assets/img/about/02.jpg" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="about-experience">
                                <h5>30<span>+</span></h5>
                                <p>Năm kinh nghiệm</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-right wow fadeInUp" data-wow-delay=".25s">
                            <div class="site-heading mb-3">
                                <span class="site-title-tagline"><i class="far fa-plane"></i> Về chúng tôi</span>
                                <h2 class="site-title">Chúng tôi là thương hiệu <span>du lịch và lưu trú</span> hàng đầu
                                </h2>
                            </div>
                            <p class="about-text">Chúng tôi mang đến nhiều lựa chọn du lịch linh hoạt,
                                được tối ưu để phù hợp với từng nhu cầu của khách hàng,
                                từ chuyến đi ngắn ngày đến kỳ nghỉ dài ngày.
                                Tất cả đều được xây dựng để bạn dễ dàng đặt và an tâm trải nghiệm.</p>
                            <div class="about-content">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="about-item">
                                            <div class="icon">
                                                <img src="assets/img/icon1.png" alt="">
                                            </div>
                                            <div class="content">
                                                <h6>Nhận ưu đãi tốt nhất</h6>
                                                <p>Lựa chọn gói dịch vụ và mức giá phù hợp nhất cho bạn.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="about-item">
                                            <div class="icon">
                                                <img src="assets/img/icon2.svg" alt="">
                                            </div>
                                            <div class="content">
                                                <h6>Đặt chỗ dễ dàng</h6>
                                                <p>Lựa chọn gói dịch vụ và mức giá phù hợp nhất cho bạn.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="theme-btn">Khám phá thêm <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- about-area end -->


        <!-- feature area -->
        <!-- <div class="feature-area pb-120">
            <div class="container">
                <div class="feature-wrapper">
                    <div class="row g-4">
                        <div class="col-lg-6 col-xl-4">
                            <div class="wow fadeInLeft" data-wow-delay=".25s">
                                <div class="site-heading mb-3">
                                    <span class="site-title-tagline"><i class="far fa-plane"></i> Tính năng</span>
                                    <h2 class="site-title">Cùng khám phá những <span>tính năng nổi bật</span></h2>
                                </div>
                                <p>
                                    Nền tảng của chúng tôi được thiết kế để tối ưu quá trình tìm kiếm và đặt chỗ,
                                    giúp bạn so sánh và lựa chọn nhanh hơn,
                                    đồng thời mang lại thông tin rõ ràng và trình bày dễ hiểu,
                                    để bạn luôn nắm được chi phí, lịch trình và tiện ích cần thiết,
                                    trong một trải nghiệm liền mạch.
                                </p>
                                <a href="#" class="theme-btn mt-30">Tìm hiểu thêm <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-4">
                            <div class="feature-img wow fadeInUp" data-wow-delay=".25s">
                                <img src="assets/img/anh1.webp" alt="">
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-4">
                            <div class="wow fadeInRight" data-wow-delay=".25s">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <img src="assets/img/world.svg" alt="">
                                    </div>
                                    <div class="feature-content">
                                        <h4 class="feature-title">Phủ sóng toàn cầu</h4>
                                        <p>Thông tin được trình bày rõ ràng, giúp bạn theo dõi và lựa chọn dễ dàng trong
                                            quá trình đặt chỗ.</p>
                                    </div>
                                </div>
                                <div class="feature-item mt-20">
                                    <div class="feature-icon">
                                        <img src="assets/img/quality.svg" alt="">
                                    </div>
                                    <div class="feature-content">
                                        <h4 class="feature-title">Dịch vụ chất lượng cao</h4>
                                        <p>Thông tin được trình bày rõ ràng, giúp bạn theo dõi và lựa chọn dễ dàng trong
                                            quá trình đặt chỗ.</p>
                                    </div>
                                </div>
                                <div class="feature-item mt-20">
                                    <div class="feature-icon">
                                        <img src="assets/img/support.svg" alt="">
                                    </div>
                                    <div class="feature-content">
                                        <h4 class="feature-title">Cham soc khách hang 24/7</h4>
                                        <p>Thông tin được trình bày rõ ràng, giúp bạn theo dõi và lựa chọn dễ dàng trong
                                            quá trình đặt chỗ.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- feature area end -->
        <!-- flight area -->
        <div class="flight-area pd-50 mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Tour nước ngoài</span>
                            <h2 class="site-title">Tour du lịch dành cho bạn</h2>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    @forelse ($foreignTours ?? [] as $product)
                        <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            @include('frontend.products._tour_card', [
                                'product' => $product,
                                'imageFallback' => 'assets/img/flight/01.jpg',
                            ])
                        </div>
                        @if (false)
                        @php
                            $resolveImage = function ($value) {
                                return \App\Support\MediaManager::publicUrl($value);
                            };
                            $formatPrice = function ($product) {
                                if (! filled($product->price)) return 'Liên hệ';
                                return number_format((float) $product->price, 0, ',', '.') . ' đ';
                            };
                            $plainText = function ($value) {
                                return trim(preg_replace('/\s+/u', ' ', strip_tags((string) $value)));
                            };
                            $durationText = $plainText($product->duration) ?: 'Đang cập nhật';
                            $departureText = $plainText($product->departure_location) ?: ($plainText($product->address) ?: 'Đang cập nhật địa điểm');
                            $transportText = $plainText($product->transport) ?: 'Liên hệ để biết thêm';
                            $promoText = $plainText($product->promotion_content);
                            $primaryTag = $product->category?->name ?? 'Tour';
                            $secondaryTag = $promoText !== '' ? \Illuminate\Support\Str::limit($promoText, 28) : ($product->is_featured ? 'Tour nổi bật' : 'Mới cập nhật');
                        @endphp
                        <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <div class="flight-item">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                                @if($product->is_featured)
                                    <span class="badge" style="position: absolute; top: 15px; right: 15px; z-index: 1; background: var(--theme-color); color: #fff; padding: 5px 10px; border-radius: 5px;">Nổi bật</span>
                                @endif
                                <div class="flight-img">
                                    @if ($resolveImage($product->image))
                                        <img src="{{ $resolveImage($product->image) }}" alt="{{ $product->title }}" style="height: 250px; object-fit: cover;">
                                    @else
                                        <img src="assets/img/flight/01.jpg" alt="" style="height: 250px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="flight-content">
                                    <div class="flight-title">
                                        <h4 style="font-size: 18px; margin-bottom: 10px; min-height: 44px;"><a href="{{ $product->frontend_url }}">{{ \Illuminate\Support\Str::limit($product->title, 50) }}</a></h4>
                                        <p class="flight-date" style="margin-bottom: 5px;"><i class="far fa-clock"></i> {{ $durationText }}</p>
                                        <p class="flight-date"><i class="far fa-map-marker-alt"></i> Khởi hành: {{ $departureText }}</p>
                                    </div>
                                    <div class="flight-bottom">
                                        <div class="flight-price">
                                            Giá từ <span>{{ $formatPrice($product) }}</span>
                                        </div>
                                        <div class="flight-text-btn">
                                            <a href="{{ $product->frontend_url }}">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="col-12 text-center">
                            <p>Đang cập nhật tour nước ngoài...</p>
                        </div>
                    @endforelse
                    
                    <div class="text-center mt-3 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                        <a href="/tour-nuoc-ngoai" class="theme-btn">Khám phá thêm<i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- flight area end -->

        <!-- tour area -->
        <div class="tour-area pb-120 bg pt-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Tour trong nước</span>
                            <h2 class="site-title">Các tour được yêu thích nhất</h2>
                            <div class="filter-controls mt-20">
                                <ul class="filter-btns">
                                    <li class="active" data-filter=".mien-bac">Miền Bắc</li>
                                    <li data-filter=".mien-trung">Miền Trung</li>
                                    <li data-filter=".mien-nam">Miền Nam</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row filter-box">
                    @forelse ($domesticTours ?? [] as $product)
                        @php
                            $regionLabel = match ($product->home_region ?? '') {
                                'mien-bac' => 'Miền Bắc',
                                'mien-trung' => 'Miền Trung',
                                'mien-nam' => 'Miền Nam',
                                default => 'Trong nước',
                            };
                        @endphp
                        <div class="col-md-6 col-lg-4 col-xl-3 filter-item {{ $product->home_region ?? 'mien-bac' }}">
                            @include('frontend.products._tour_card', [
                                'product' => $product,
                            ])
                        </div>
                        @if (false)
                        @php
                            $resolveImage = function ($value) {
                                return \App\Support\MediaManager::publicUrl($value);
                            };
                            $formatPrice = function ($product) {
                                if (! filled($product->price)) return 'Liên hệ';
                                return number_format((float) $product->price, 0, ',', '.') . ' đ';
                            };
                            $plainText = function ($value) {
                                return trim(preg_replace('/\s+/u', ' ', strip_tags((string) $value)));
                            };
                            $durationText = $plainText($product->duration) ?: 'Đang cập nhật';
                            $addressText = $plainText($product->address) ?: ($plainText($product->destination) ?: 'Đang cập nhật địa điểm');
                            $transportText = $plainText($product->transport) ?: 'Liên hệ để biết thêm';
                            $departureText = $plainText($product->departure_location) ?: 'Đang cập nhật';
                            $regionLabel = match ($product->home_region ?? '') {
                                'mien-bac' => 'Miền Bắc',
                                'mien-trung' => 'Miền Trung',
                                'mien-nam' => 'Miền Nam',
                                default => 'Trong nước',
                            };
                            $inWishlist = session()->has('wishlist.' . $product->id);
                        @endphp
                        <div class="col-md-6 col-lg-4 col-xl-3 filter-item {{ $product->home_region ?? 'mien-bac' }}">
                            <div class="tour-item">
                                <div class="tour-img">
                                    @if($product->is_featured)
                                        <span class="badge">Nổi bật</span>
                                    @endif
                                    @if ($resolveImage($product->image))
                                        <img src="{{ $resolveImage($product->image) }}" alt="{{ $product->title }}" style="height: 250px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('tourit/assets/img/tour/01.webp') }}" alt="" style="height: 250px; object-fit: cover;">
                                    @endif
                                    <a href="#" class="add-wishlist {{ $inWishlist ? 'active' : '' }}" onclick="event.preventDefault(); toggleWishlist(this, {{ $product->id }});"><i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart"></i></a>
                                </div>
                                <div class="tour-content">
                                    <div class="tour-top">
                                        <a href="{{ $product->frontend_url }}" class="tour-category"><i class="far fa-tag"></i> {{ $regionLabel }}</a>
                                        <span class="tour-place"><i class="far fa-bus"></i> {{ $transportText }}</span>
                                    </div>
                                    <h4 class="tour-title"><a href="{{ $product->frontend_url }}">{{ \Illuminate\Support\Str::limit($product->title, 50) }}</a></h4>
                                    <p><i class="far fa-location-dot"></i> {{ $addressText }}</p>
                                    <div class="hotel-rate">
                                        <span class="badge"><i class="far fa-location-arrow"></i> {{ $departureText }}</span>
                                        <span class="hotel-rate-type">Khởi hành linh hoạt</span>
                                    </div>
                                    <div class="tour-duration"><i class="far fa-clock"></i> {{ $durationText }}</div>
                                    <div class="tour-bottom">
                                        <div class="tour-price">
                                            Từ <span>{{ $formatPrice($product) }}</span>
                                        </div>
                                        <div class="tour-text-btn">
                                            <a href="{{ $product->frontend_url }}">Xem thêm <i class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="col-12 text-center">
                            <p>Đang cập nhật tour trong nước...</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- tour area end -->


        <!-- hotel area -->
        <div class="hotel-area bg pt-80 pb-80" style="display: none;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Khách sạn</span>
                            <h2 class="site-title">Những khách sạn được yêu thích nhất</h2>
                        </div>
                    </div>
                </div>
                <div class="hotel-slider owl-carousel owl-theme">
                    <div class="hotel-item">
                        <div class="hotel-img">
                            <span class="badge">Nổi bật</span>
                            <img src="assets/img/hotel/01.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="hotel-content">
                            <h4 class="hotel-title"><a href="#">Khách sạn Western Grant Park</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="hotel-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="hotel-rate-type">Excellent</span>
                                <span class="hotel-rate-review">(2.5k Reviews)</span>
                            </div>
                            <div class="hotel-bottom">
                                <div class="hotel-price">
                                    <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                            Night</span></span>
                                </div>
                                <div class="hotel-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hotel-item">
                        <div class="hotel-img">
                            <img src="assets/img/hotel/02.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="hotel-content">
                            <h4 class="hotel-title"><a href="#">Khách sạn Western Grant Park</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="hotel-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="hotel-rate-type">Excellent</span>
                                <span class="hotel-rate-review">(2.5k Reviews)</span>
                            </div>
                            <div class="hotel-bottom">
                                <div class="hotel-price">
                                    <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                            Night</span></span>
                                </div>
                                <div class="hotel-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hotel-item">
                        <div class="hotel-img">
                            <span class="badge">Pho bien</span>
                            <img src="assets/img/hotel/03.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="hotel-content">
                            <h4 class="hotel-title"><a href="#">Khách sạn Western Grant Park</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="hotel-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="hotel-rate-type">Excellent</span>
                                <span class="hotel-rate-review">(2.5k Reviews)</span>
                            </div>
                            <div class="hotel-bottom">
                                <div class="hotel-price">
                                    <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                            Night</span></span>
                                </div>
                                <div class="hotel-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hotel-item">
                        <div class="hotel-img">
                            <span class="badge badge-discount">25% Save</span>
                            <img src="assets/img/hotel/04.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="hotel-content">
                            <h4 class="hotel-title"><a href="#">Khách sạn Western Grant Park</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="hotel-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="hotel-rate-type">Excellent</span>
                                <span class="hotel-rate-review">(2.5k Reviews)</span>
                            </div>
                            <div class="hotel-bottom">
                                <div class="hotel-price">
                                    <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                            Night</span></span>
                                </div>
                                <div class="hotel-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hotel-item">
                        <div class="hotel-img">
                            <img src="assets/img/hotel/05.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="hotel-content">
                            <h4 class="hotel-title"><a href="#">Khách sạn Western Grant Park</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="hotel-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="hotel-rate-type">Excellent</span>
                                <span class="hotel-rate-review">(2.5k Reviews)</span>
                            </div>
                            <div class="hotel-bottom">
                                <div class="hotel-price">
                                    <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                            Night</span></span>
                                </div>
                                <div class="hotel-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hotel-item">
                        <div class="hotel-img">
                            <img src="assets/img/hotel/06.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="hotel-content">
                            <h4 class="hotel-title"><a href="#">Khách sạn Western Grant Park</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="hotel-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="hotel-rate-type">Excellent</span>
                                <span class="hotel-rate-review">(2.5k Reviews)</span>
                            </div>
                            <div class="hotel-bottom">
                                <div class="hotel-price">
                                    <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                            Night</span></span>
                                </div>
                                <div class="hotel-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- hotel area end -->


        <!-- video-area -->
        <div class="video-area py-120">
            <div class="container-fluid pe-0 p-lg-0">
                <div class="col-lg-10 ms-lg-auto">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-8 col-lg-5 wow fadeInLeft" data-wow-delay=".25s">
                            <div class="site-heading mb-3">
                                <span class="site-title-tagline"><i class="far fa-plane"></i> KHÔNG GIAN SỰ KIỆN CHUYÊN NGHIỆP</span>
                                <h2 class="site-title">
                                    Dịch Vụ Phòng Tiệc & Hội Trường <br> Đẳng Cấp Cho Mọi Sự Kiện
                                </h2>
                            </div>
                            <p class="about-text">
                                Chúng tôi cung cấp hệ thống phòng tiệc và hội trường hiện đại, phù hợp cho hội nghị, hội thảo, tiệc cưới, tiệc sinh nhật, sự kiện doanh nghiệp và các chương trình đặc biệt. Không gian sang trọng, trang thiết bị âm thanh ánh sáng chuyên nghiệp cùng đội ngũ phục vụ tận tâm sẽ mang đến cho quý khách những trải nghiệm hoàn hảo và đáng nhớ.
                            </p>
                            <a href="#" class="theme-btn mt-30">Xem Chi Tiết<i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                        <div class="col-lg-7 wow fadeInRight" data-wow-delay=".25s">
                            <div class="video-content" style="background-image: url(assets/img/video/01.jpg);">
                                <div class="row align-items-center">
                                    <div class="col-lg-12">
                                        <div class="video-wrapper">
                                            <a class="play-btn popup-youtube"
                                                href="https://www.youtube.com/watch?v=hF3EtNWI74k">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- video-area end -->


        <!-- banner area -->
        <div class="banner-area bg pt-50 pb-50" style="display: none;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Ưu đãi</span>
                            <h2 class="site-title">Khám phá ưu đãi đặc quyền</h2>
                        </div>
                    </div>
                </div>
                <div class="banner-slider owl-carousel owl-theme">
                    <div class="banner-item">
                        <div class="banner-img">
                            <img src="assets/img/banner/01.jpg" alt="">
                        </div>
                        <div class="banner-content">
                            <h6>Nhận ưu đãi đến <span>70%</span>!</h6>
                            <p>It is a long established fact that reader distracted.</p>
                            <a href="#" class="theme-btn">Tìm hiểu thêm<i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="banner-item">
                        <div class="banner-img">
                            <img src="assets/img/banner/02.jpg" alt="">
                        </div>
                        <div class="banner-content">
                            <h6>Nhận ưu đãi đến <span>70%</span>!</h6>
                            <p>It is a long established fact that reader distracted.</p>
                            <a href="#" class="theme-btn">Tìm hiểu thêm<i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="banner-item">
                        <div class="banner-img">
                            <img src="assets/img/banner/03.jpg" alt="">
                        </div>
                        <div class="banner-content">
                            <h6>Nhận ưu đãi đến <span>70%</span>!</h6>
                            <p>It is a long established fact that reader distracted.</p>
                            <a href="#" class="theme-btn">Tìm hiểu thêm<i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="banner-item">
                        <div class="banner-img">
                            <img src="assets/img/banner/04.jpg" alt="">
                        </div>
                        <div class="banner-content">
                            <h6>Nhận ưu đãi đến <span>70%</span>!</h6>
                            <p>It is a long established fact that reader distracted.</p>
                            <a href="#" class="theme-btn">Tìm hiểu thêm<i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- banner area end -->


        @if (false)
        <!-- tour area -->
        <div class="tour-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading-inline mb-50">
                            <div>
                                <span class="site-title-tagline"><i class="far fa-plane"></i> Tour</span>
                                <h2 class="site-title">Những tour được yêu thích nhất</h2>
                            </div>
                            <div class="filter-controls">
                                <ul class="filter-btns">
                                    <li class="active" data-filter="*">Tất cả tour</li>
                                    <li data-filter=".cat1">Historical</li>
                                    <li data-filter=".cat2">Weekend Trip</li>
                                    <li data-filter=".cat3">Tour đặc biệt</li>
                                    <li data-filter=".cat4">Tour nghỉ dưỡng</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row filter-box">
                    <div class="col-md-6 col-lg-4 col-xl-3 filter-item cat1">
                        <div class="tour-item">
                            <div class="tour-img">
                                <span class="badge badge-discount">25% Save</span>
                                <img src="assets/img/tour/01.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Historical</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Tour lịch sử Canada</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        Tu <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 filter-item cat2">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/02.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Weekend</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Tour cuối tuần Canada</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        Tu <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 filter-item cat3">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/03.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Special</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Tour đặc biệt Canada</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        Tu <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 filter-item cat4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/04.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Nghi duong</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Tour nghỉ dưỡng Canada</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        Tu <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 filter-item cat2">
                        <div class="tour-item">
                            <div class="tour-img">
                                <span class="badge">Nổi bật</span>
                                <img src="assets/img/tour/05.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Weekend</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Tour cuối tuần Canada</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        Tu <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 filter-item cat4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/06.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Nghi duong</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Tour nghỉ dưỡng Canada</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        Tu <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 filter-item cat3">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/07.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Special</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Tour đặc biệt Canada</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        Tu <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 filter-item cat1">
                        <div class="tour-item">
                            <div class="tour-img">
                                <span class="badge badge-discount">25% Save</span>
                                <img src="assets/img/tour/08.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Historical</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Tour lịch sử Canada</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        Tu <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- tour area end -->
        @endif


        <!-- cta-area -->
        <div class="cta-area">
            <div class="container">
                <div class="cta-wrapper">
                    <div class="col-md-10 col-lg-8 col-xl-6 mx-auto">
                        <div class="cta-content">
                            <div class="cta-text">
                                <h1>Đặt lần đầu <span>giảm đến 70%</span>!</h1>
                                <p>It is a long established fact that a reader will be distracted by the readable
                                    content web page editors now use of a page when looking at its quá trình đặt chỗ.</p>
                            </div>
                            <a href="#" class="theme-btn mt-20">Đặt ngay <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="cta-img">
                        <img class="w-100" src="assets/img/Booking.webp" alt="">
                    </div>
                </div>
            </div>
        </div>
        <!-- cta-area end -->


        <!-- choose area -->
        <div class="choose-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Lý do chọn chúng tôi</span>
                            <h2 class="site-title">Khám phá những điểm đến đẹp cùng chúng tôi</h2>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="choose-item">
                            <span class="count">01</span>
                            <div class="icon">
                                <img src="assets/img/safety.svg" alt="">
                            </div>
                            <div class="content">
                                <h4>An Toàn Và Tin Cậy</h4>
                                <p>Chúng tôi luôn đặt sự an toàn của khách hàng lên hàng đầu. Mọi quá trình đặt chỗ đều được bảo mật tuyệt đối, giúp bạn hoàn toàn an tâm.</p>
                            </div>
                        </div>
                        <div class="choose-item">
                            <span class="count">02</span>
                            <div class="icon">
                                <img src="assets/img/price.svg" alt="">
                            </div>
                            <div class="content">
                                <h4>Minh Bạch Giá Cả 100%</h4>
                                <p>Không có phí ẩn, không phụ thu bất ngờ. Mọi thông tin về chi phí luôn được công khai và minh bạch trước khi bạn hoàn tất đặt chỗ.</p>
                            </div>
                        </div>
                        <div class="choose-item">
                            <span class="count">03</span>
                            <div class="icon">
                                <img src="assets/img/booking-confirm.svg" alt="">
                            </div>
                            <div class="content">
                                <h4>Đặt Chỗ Nhanh Chóng</h4>
                                <p>Trải nghiệm hệ thống đặt vé thông minh và tiện lợi. Bạn có thể dễ dàng quản lý lịch trình và chuyến đi của mình mọi lúc mọi nơi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeInRight" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="choose-img">
                            <img class="shape" src="assets/img/bay.png" alt="">
                            <img class="img-1" src="assets/img/anh3.webp" alt="">
                            <img class="img-2" src="assets/img/anh4.webp" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- choose area end -->


        <!-- car area -->
        <div class="car-area bg pt-80 pb-80" style="display: none;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Thuê xe</span>
                            <h2 class="site-title">Nhung mau xe thue duoc quan tam nhieu nhat</h2>
                        </div>
                    </div>
                </div>
                <div class="car-slider owl-carousel owl-theme">
                    <div class="car-item">
                        <div class="car-img">
                            <span class="badge">Nổi bật</span>
                            <img src="assets/img/car/01.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="car-content">
                            <h4 class="car-title"><a href="#">Xe Toyota Corolla</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="car-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="car-rate-type">Excellent</span>
                                <span class="car-rate-review">(2.5k Reviews)</span>
                            </div>
                            <ul class="car-info-list">
                                <li><i class="far fa-car"></i>Model: 2025</li>
                                <li><i class="far fa-user-tie"></i>4 People</li>
                                <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                <li><i class="far fa-steering-wheel"></i>Automatic</li>
                            </ul>
                            <div class="car-bottom">
                                <div class="car-price">
                                    <span class="car-price-amount">$62 <span class="car-price-type">/Per
                                            Day</span></span>
                                </div>
                                <div class="car-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="car-item">
                        <div class="car-img">
                            <img src="assets/img/car/02.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="car-content">
                            <h4 class="car-title"><a href="#">Xe Toyota Corolla</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="car-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="car-rate-type">Excellent</span>
                                <span class="car-rate-review">(2.5k Reviews)</span>
                            </div>
                            <ul class="car-info-list">
                                <li><i class="far fa-car"></i>Model: 2025</li>
                                <li><i class="far fa-user-tie"></i>4 People</li>
                                <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                <li><i class="far fa-steering-wheel"></i>Automatic</li>
                            </ul>
                            <div class="car-bottom">
                                <div class="car-price">
                                    <span class="car-price-amount">$45 <span class="car-price-type">/Per
                                            Day</span></span>
                                </div>
                                <div class="car-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="car-item">
                        <div class="car-img">
                            <span class="badge">Pho bien</span>
                            <img src="assets/img/car/03.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="car-content">
                            <h4 class="car-title"><a href="#">Xe Toyota Corolla</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="car-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="car-rate-type">Excellent</span>
                                <span class="car-rate-review">(2.5k Reviews)</span>
                            </div>
                            <ul class="car-info-list">
                                <li><i class="far fa-car"></i>Model: 2025</li>
                                <li><i class="far fa-user-tie"></i>4 People</li>
                                <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                <li><i class="far fa-steering-wheel"></i>Automatic</li>
                            </ul>
                            <div class="car-bottom">
                                <div class="car-price">
                                    <span class="car-price-amount">$50 <span class="car-price-type">/Per
                                            Day</span></span>
                                </div>
                                <div class="car-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="car-item">
                        <div class="car-img">
                            <span class="badge badge-discount">25% Save</span>
                            <img src="assets/img/car/04.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="car-content">
                            <h4 class="car-title"><a href="#">Xe Toyota Corolla</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="car-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="car-rate-type">Excellent</span>
                                <span class="car-rate-review">(2.5k Reviews)</span>
                            </div>
                            <ul class="car-info-list">
                                <li><i class="far fa-car"></i>Model: 2025</li>
                                <li><i class="far fa-user-tie"></i>4 People</li>
                                <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                <li><i class="far fa-steering-wheel"></i>Automatic</li>
                            </ul>
                            <div class="car-bottom">
                                <div class="car-price">
                                    <span class="car-price-amount">$70 <span class="car-price-type">/Per
                                            Day</span></span>
                                </div>
                                <div class="car-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="car-item">
                        <div class="car-img">
                            <img src="assets/img/car/05.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="car-content">
                            <h4 class="car-title"><a href="#">Xe Toyota Corolla</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="car-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="car-rate-type">Excellent</span>
                                <span class="car-rate-review">(2.5k Reviews)</span>
                            </div>
                            <ul class="car-info-list">
                                <li><i class="far fa-car"></i>Model: 2025</li>
                                <li><i class="far fa-user-tie"></i>4 People</li>
                                <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                <li><i class="far fa-steering-wheel"></i>Automatic</li>
                            </ul>
                            <div class="car-bottom">
                                <div class="car-price">
                                    <span class="car-price-amount">$65 <span class="car-price-type">/Per
                                            Day</span></span>
                                </div>
                                <div class="car-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="car-item">
                        <div class="car-img">
                            <img src="assets/img/car/06.jpg" alt="">
                            <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="car-content">
                            <h4 class="car-title"><a href="#">Xe Toyota Corolla</a></h4>
                            <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                            <div class="car-rate">
                                <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                <span class="car-rate-type">Excellent</span>
                                <span class="car-rate-review">(2.5k Reviews)</span>
                            </div>
                            <ul class="car-info-list">
                                <li><i class="far fa-car"></i>Model: 2025</li>
                                <li><i class="far fa-user-tie"></i>4 People</li>
                                <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                <li><i class="far fa-steering-wheel"></i>Automatic</li>
                            </ul>
                            <div class="car-bottom">
                                <div class="car-price">
                                    <span class="car-price-amount">$58 <span class="car-price-type">/Per
                                            Day</span></span>
                                </div>
                                <div class="car-text-btn">
                                    <a href="#">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- car area end -->


        <!-- team-area -->
        <div class="team-area py-120 d-none">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Đội ngũ</span>
                            <h2 class="site-title">Gặp gỡ đội ngũ chuyên gia</h2>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    @forelse ($experts ?? [] as $expert)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <div class="team-img">
                                @if ($expert->image)
                                    <img src="{{ \App\Support\MediaManager::publicUrl($expert->image) }}" alt="{{ $expert->name }}">
                                @else
                                    <img src="{{ asset('assets/img/team/01.jpg') }}" alt="{{ $expert->name }}">
                                @endif
                            </div>
                            <div class="team-content">
                                <div class="team-bio">
                                    <h5><a href="javascript:void(0)">{{ $expert->name }}</a></h5>
                                    <span>{{ $expert->role }}</span>
                                </div>
                                <div class="team-social">
                                    <ul class="team-social-btn">
                                        <li><span><i class="far fa-share-alt"></i></span></li>
                                        @if($expert->facebook_url)<li><a href="{{ $expert->facebook_url }}"><i class="fab fa-facebook-f"></i></a></li>@endif
                                        @if($expert->twitter_url)<li><a href="{{ $expert->twitter_url }}"><i class="fab fa-x-twitter"></i></a></li>@endif
                                        @if($expert->instagram_url)<li><a href="{{ $expert->instagram_url }}"><i class="fab fa-instagram"></i></a></li>@endif
                                        @if($expert->linkedin_url)<li><a href="{{ $expert->linkedin_url }}"><i class="fab fa-linkedin-in"></i></a></li>@endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center">
                        <p>Đang cập nhật đội ngũ chuyên gia...</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- team-area end -->


       


        <!-- testimonial area -->
        <div class="testimonial-area ts-bg py-120">
            <div class="shadow-text">Vietnam homes Tourist</div>
            <div class="container pb-30">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center mb-4">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Cảm nhận khách hàng</span>
                            <h2 class="site-title text-white">Khách hàng nói gì về chúng tôi?</h2>
                        </div>
                    </div>
                </div>
                <div class="testimonial-slider owl-carousel owl-theme wow fadeInUp" data-wow-duration="1s"
                    data-wow-delay=".25s">
                    <div class="testimonial-single">
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="assets/img/testimonial/01.jpg" alt="">
                            </div>
                        </div>
                        <div class="testimonial-quote">
                            <span class="count">01</span>
                            <div class="testimonial-author-info">
                                <h4>Diana Carter</h4>
                                <p>Khách hàng của chúng tôi</p>
                            </div>
                            <p>
                                There are many variations passages of available but to the majority have
                                suffered for the alteration in some form injected  words which look even slig
                                believable.
                            </p>
                            <div class="testimonial-quote-icon">
                                <img src="assets/img/icon/quote.svg" alt="">
                            </div>
                            <div class="testimonial-rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-single">
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="assets/img/testimonial/02.jpg" alt="">
                            </div>
                        </div>
                        <div class="testimonial-quote">
                            <span class="count">02</span>
                            <div class="testimonial-author-info">
                                <h4>Brandon Wigfall</h4>
                                <p>Khách hàng của chúng tôi</p>
                            </div>
                            <p>
                                There are many variations passages of available but to the majority have
                                suffered for the alteration in some form injected  words which look even slig
                                believable.
                            </p>
                            <div class="testimonial-quote-icon">
                                <img src="assets/img/icon/quote.svg" alt="">
                            </div>
                            <div class="testimonial-rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-single">
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="assets/img/testimonial/03.jpg" alt="">
                            </div>
                        </div>
                        <div class="testimonial-quote">
                            <span class="count">03</span>
                            <div class="testimonial-author-info">
                                <h4>Sylvia Green</h4>
                                <p>Khách hàng của chúng tôi</p>
                            </div>
                            <p>
                                There are many variations passages of available but to the majority have
                                suffered for the alteration in some form injected  words which look even slig
                                believable.
                            </p>
                            <div class="testimonial-quote-icon">
                                <img src="assets/img/icon/quote.svg" alt="">
                            </div>
                            <div class="testimonial-rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-single">
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="assets/img/testimonial/04.jpg" alt="">
                            </div>
                        </div>
                        <div class="testimonial-quote">
                            <span class="count">04</span>
                            <div class="testimonial-author-info">
                                <h4>Miguel Woodworth</h4>
                                <p>Khách hàng của chúng tôi</p>
                            </div>
                            <p>
                                There are many variations passages of available but to the majority have
                                suffered for the alteration in some form injected  words which look even slig
                                believable.
                            </p>
                            <div class="testimonial-quote-icon">
                                <img src="assets/img/icon/quote.svg" alt="">
                            </div>
                            <div class="testimonial-rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- testimonial area end -->


        <!-- blog area -->
        <div class="blog-area pt-120 pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Blog du lịch</span>
                            <h2 class="site-title">Bài viết và tin tức mới nhất</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <span class="blog-date">25 tháng 8, 2025</span>
                            <div class="blog-item-img">
                                <img src="assets/img/blog/01.jpg" alt="Thumb">
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><a href="#"><i class="far fa-user-circle"></i> Bởi Alicia Davis</a></li>
                                        <li><a href="#"><i class="far fa-comments"></i> 25.5k bình luận</a></li>
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <a href="#">Nhiều bí quyết lên lịch trình thông minh giúp chuyến đi nhẹ nhàng hơn
                                        </a>
                                </h4>
                                <a class="theme-btn mt-3" href="#">Xem thêm <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".50s">
                            <span class="blog-date">27 tháng 8, 2025</span>
                            <div class="blog-item-img">
                                <img src="assets/img/blog/02.jpg" alt="Thumb">
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><a href="#"><i class="far fa-user-circle"></i> Bởi Alicia Davis</a></li>
                                        <li><a href="#"><i class="far fa-comments"></i> 25.5k bình luận</a></li>
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <a href="#">Cách chọn nơi lưu trú và di chuyển tối ưu cho từng ngân sách</a>
                                </h4>
                                <a class="theme-btn mt-3" href="#">Xem thêm <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".75s">
                            <span class="blog-date">30 tháng 8, 2025</span>
                            <div class="blog-item-img">
                                <img src="assets/img/blog/03.jpg" alt="Thumb">
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><a href="#"><i class="far fa-user-circle"></i> Bởi Alicia Davis</a></li>
                                        <li><a href="#"><i class="far fa-comments"></i> 25.5k bình luận</a></li>
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <a href="#">Nhung xu huong du lich moi dang duoc nhieu du khách quan tam
                                        </a>
                                </h4>
                                <a class="theme-btn mt-3" href="#">Xem thêm <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- blog area end -->


        <!-- partner area -->
        <div class="partner-area">
            @php
                $partnerLogos = [
                    '/tourit/img/logdoitac.webp',
                    '/tourit/img/logdoitac1.webp',
                    '/tourit/img/logdoitac2.webp',
                    '/tourit/img/logdoitac3.webp',
                    '/tourit/img/logdoitac4.webp',
                    '/tourit/img/logdoitac5.webp',
                    '/tourit/img/logdoitac6.webp',
                ];
            @endphp
            <div class="col-lg-8">
                <div class="partner-wrap partner-negative">
                    <div class="col-lg-11 mx-auto">
                        <div class="partner-marquee" aria-label="Danh sach doi tac">
                            <div class="partner-marquee-track">
                                @foreach ($partnerLogos as $index => $logoPath)
                                    <div class="partner-card">
                                        <img src="{{ url($logoPath) }}" alt="Logo doi tac {{ $index + 1 }}">
                                    </div>
                                @endforeach
                                @foreach ($partnerLogos as $index => $logoPath)
                                    <div class="partner-card" aria-hidden="true">
                                        <img src="{{ url($logoPath) }}" alt="">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- partner area end -->

    </main>
@endsection


