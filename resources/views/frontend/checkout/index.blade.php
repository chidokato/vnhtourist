@extends('frontend.layouts.app')

@section('title', 'Thanh toán')

@push('styles')
<link rel="stylesheet" href="{{ asset('tourit/assets/css/checkout.css') }}">
@endpush

@section('content')
<main class="main bg-light py-5 pages-category">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        @php $item = reset($cart); @endphp
        @if($item)
        <form action="{{ route('frontend.checkout.process') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="booking-step-block">
                        <div class="booking-step-title">
                            <span class="booking-step-number">3</span>
                            Thông tin liên hệ
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Họ và tên *</label>
                                <input type="text" name="name" class="form-control px-3 py-2" placeholder="Nhập họ và tên" value="{{ old('name', $user->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại *</label>
                                <input type="text" name="phone" class="form-control px-3 py-2" placeholder="Nhập số điện thoại" value="{{ old('phone', $user->phone ?? '') }}" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Email *</label>
                                <input type="email" name="email" class="form-control px-3 py-2" placeholder="Nhập địa chỉ Email" value="{{ old('email', $user->email ?? '') }}" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Ghi chú thêm</label>
                                <textarea name="notes" rows="4" class="form-control px-3 py-2" placeholder="Yêu cầu đặc biệt (nếu có)...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4 mb-4">
                        <a href="{{ route('frontend.checkout.step1', $item['post_id']) }}" class="btn btn-light d-flex justify-content-center align-items-center" style="padding: 12px 24px; font-size: 16px; font-weight: 600; border-radius: 8px; border: 1px solid #eef2f6; color: #666; min-width: 150px;">
                            <i class="fas fa-arrow-left me-2"></i> Quay lại
                        </a>
                        <button type="submit" class="theme-btn d-flex justify-content-center align-items-center gap-2" style="padding: 12px 24px; font-size: 16px; font-weight: 600; margin-top: 0; min-width: 150px; border-radius: 8px;">
                            Xác nhận
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="summary-card">
                        <div class="summary-img">
                            <img src="{{ $item['image'] ?? asset('tourit/assets/img/hotel/room/01.jpg') }}" alt="{{ $item['tour_name'] }}">
                        </div>
                        <div class="summary-content">
                            <h4 class="summary-title">{{ $item['tour_name'] }}</h4>
                            
                            <ul class="summary-info-list">
                                <li><span>Mã tour:</span> <span>{{ $item['tour_code'] ?? 'Đang cập nhật' }}</span></li>
                                <li><span>Thời gian:</span> <span>{{ $item['duration'] ?? 'Đang cập nhật' }}</span></li>
                                <li><span>Phương tiện:</span> <span class="text-end" style="max-width: 60%;">{{ $item['transport'] ?? 'Đang cập nhật' }}</span></li>
                                <li><span>Ngày đi:</span> <span id="summary_date">{{ $item['departure_date'] ?: 'Đang cập nhật' }}</span></li>
                            </ul>

                            <div class="summary-divider"></div>

                            <p class="text-muted mb-2" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Chi tiết giá</p>
                            
                            <ul class="price-breakdown" id="price_breakdown">
                                <li>
                                    <span>Người lớn x {{ $item['adult_quantity'] }}</span>
                                    <span>{{ number_format($item['price'], 0, ',', '.') }} ₫</span>
                                </li>
                                @if($item['child_quantity'] > 0)
                                <li>
                                    <span>Trẻ em x {{ $item['child_quantity'] }}</span>
                                    <span>{{ number_format($item['child_price'] ?? 0, 0, ',', '.') }} ₫</span>
                                </li>
                                @endif
                                @if($item['infant_quantity'] > 0)
                                <li>
                                    <span>Em bé x {{ $item['infant_quantity'] }}</span>
                                    <span>{{ number_format($item['infant_price'] ?? 0, 0, ',', '.') }} ₫</span>
                                </li>
                                @endif
                            </ul>

                            <div class="total-row">
                                <span>Tổng cộng</span>
                                <strong>{{ number_format($total, 0, ',', '.') }} ₫</strong>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
        @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag text-muted mb-3" style="font-size: 60px;"></i>
            <h4>Chưa có thông tin đặt tour</h4>
            <p class="text-muted">Có vẻ như bạn chưa chọn tour nào hoặc phiên làm việc đã hết hạn.</p>
            <a href="{{ route('frontend.home') }}" class="theme-btn mt-3">Quay lại trang chủ</a>
        </div>
        @endif
    </div>
</main>
@endsection
