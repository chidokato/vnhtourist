@extends('frontend.layouts.app')

@php
    $contactAddress = $settings->address ?? null;
    $contactHotline = $settings->hotline ?? null;
    $contactEmail = $settings->email ?? null;
    $customerInquiryErrors = $errors->getBag('customerInquiry');
@endphp

@section('title', $pageTitle ?? 'Liên hệ')
@section('meta_description', $pageDescription ?? '')
@section('meta_keywords', $pageKeywords ?? '')

@section('content')
    <main class="main">
        <div class="site-breadcrumb" style="background: url(assets/img/banner/04.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">Liên hệ</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
                    <li class="active">Liên hệ</li>
                </ul>
            </div>
        </div>

        <div class="contact-area py-120">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="contact-content">
                                <div class="contact-info">
                                    <div class="contact-info-icon">
                                        <i class="far fa-map-marker-alt"></i>
                                    </div>
                                    <div class="contact-info-content">
                                        <h5>Địa chỉ văn phòng</h5>
                                        <p>{{ $displayValue($contactAddress) }}</p>
                                    </div>
                                </div>
                                <div class="contact-info">
                                    <div class="contact-info-icon">
                                        <i class="far fa-phone"></i>
                                    </div>
                                    <div class="contact-info-content">
                                        <h5>Gọi cho chúng tôi</h5>
                                        <p>
                                            @if ($hasDisplayValue($contactHotline))
                                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactHotline) }}">{{ $displayValue($contactHotline) }}</a>
                                            @else
                                                <span>{{ $displayValue($contactHotline) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="contact-info">
                                    <div class="contact-info-icon">
                                        <i class="far fa-envelopes"></i>
                                    </div>
                                    <div class="contact-info-content">
                                        <h5>Email liên hệ</h5>
                                        <p>
                                            @if ($hasDisplayValue($contactEmail))
                                                <a href="mailto:{{ $contactEmail }}">{{ $displayValue($contactEmail) }}</a>
                                            @else
                                                <span>{{ $displayValue($contactEmail) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="contact-info">
                                    <div class="contact-info-icon">
                                        <i class="far fa-clock"></i>
                                    </div>
                                    <div class="contact-info-content">
                                        <h5>Thời gian làm việc</h5>
                                        <p>Thứ 2 - Thứ 7 (08:00 - 17:30)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="contact-form">
                                <div class="contact-form-header">
                                    <h2>Liên hệ với chúng tôi</h2>
                                    <p>Để lại thông tin, chúng tôi sẽ liên hệ lại để tư vấn tour, khách sạn, xe hoặc gói dịch vụ phù hợp.</p>
                                </div>

                                @if (session('customer_inquiry_success'))
                                    <div class="alert alert-success mb-4">{{ session('customer_inquiry_success') }}</div>
                                @endif

                                @if ($customerInquiryErrors->any())
                                    <div class="alert alert-danger mb-4">
                                        <ul class="mb-0">
                                            @foreach ($customerInquiryErrors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('frontend.customer-inquiries.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="source_url" value="{{ url()->current() }}">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    name="name"
                                                    class="form-control"
                                                    value="{{ old('name') }}"
                                                    placeholder="Họ và tên"
                                                    required
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    name="phone"
                                                    class="form-control"
                                                    value="{{ old('phone') }}"
                                                    placeholder="Số điện thoại"
                                                    required
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input
                                                    type="email"
                                                    name="email"
                                                    class="form-control"
                                                    value="{{ old('email') }}"
                                                    placeholder="Email của bạn"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    name="subject"
                                                    class="form-control"
                                                    value="{{ old('subject') }}"
                                                    placeholder="Nội dung cần hỗ trợ"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea
                                                    name="message"
                                                    class="form-control"
                                                    cols="30"
                                                    rows="6"
                                                    placeholder="Mô tả nhu cầu của bạn"
                                                >{{ old('message') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="theme-btn">
                                                Gửi liên hệ <i class="far fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
