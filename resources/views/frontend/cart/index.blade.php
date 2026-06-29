@extends('frontend.layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<main class="main pages-category">
    <div class="cart-area py-120">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif

            @if(count($cart) > 0)
            <div class="cart-wrapper">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tạm tính</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $key => $item)
                            <tr>
                                <td>
                                    <div class="cart-product">
                                        <div class="cart-img">
                                            <img src="{{ $item['image'] ?? asset('tourit/assets/img/cart/01.jpg') }}" alt="{{ $item['tour_name'] }}">
                                        </div>
                                        <div class="cart-product-content">
                                            <a href="{{ $item['url'] ?? '#' }}" class="cart-product-title">{{ $item['tour_name'] }}</a>
                                            <div class="cart-product-info">
                                                <div class="cart-product-reservation">
                                                    <span><span>Khởi hành:</span> {{ $item['departure_date'] ?: 'Đang cập nhật' }}</span>
                                                </div>
                                                <div class="cart-product-geust">
                                                    <span>
                                                        <span>Số khách:</span> {{ $item['adult_quantity'] }} Người lớn 
                                                        @if($item['child_quantity'] > 0), {{ $item['child_quantity'] }} Trẻ em @endif 
                                                        @if($item['infant_quantity'] > 0), {{ $item['infant_quantity'] }} Em bé @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="cart-price">
                                        <span>{{ number_format($item['price']) }} đ</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="cart-qty passenger-qty">
                                        <input class="qty-amount" type="text" value="1" disabled="">
                                    </div>
                                </td>
                                <td>
                                    <div class="cart-sub-total">
                                        <span>{{ number_format($item['total']) }} đ</span>
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('frontend.cart.remove') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <button type="submit" class="cart-remove bg-transparent border-0" style="cursor: pointer;"><i class="fas fa-times"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="cart-footer">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="cart-coupon">
                                <input type="text" class="form-control" placeholder="Mã giảm giá (nếu có)">
                                <button class="coupon-btn" type="button">Áp dụng</button>
                            </div>
                        </div>
                        <div class="col-lg-3 offset-lg-5">
                            <div class="cart-summary">
                                <ul>
                                    <li><strong>Tạm tính:</strong> <span>{{ number_format($total) }} đ</span></li>
                                    <li class="cart-total"><strong>Tổng thanh toán:</strong> <span>{{ number_format($total) }} đ</span></li>
                                </ul>
                                <div class="text-end mt-40">
                                    <a href="{{ route('frontend.checkout.index') }}" class="theme-btn">Tiến hành thanh toán <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-bag text-muted mb-3" style="font-size: 60px;"></i>
                <h4>Giỏ hàng của bạn đang trống</h4>
                <p class="text-muted">Có vẻ như bạn chưa chọn tour nào.</p>
                <a href="{{ route('frontend.home') }}" class="theme-btn mt-3">Tiếp tục tìm kiếm</a>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection
