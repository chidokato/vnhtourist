@extends('frontend.layouts.app')

@section('title', 'Đặt tour thành công')

@section('content')
<main class="main pt-100 pb-100 text-center pages-category">
    <div class="container">
        <i class="far fa-check-circle text-success" style="font-size: 80px; margin-bottom: 20px;"></i>
        <h2 class="mb-3">Đặt tour thành công!</h2>
        <p class="lead">Cảm ơn bạn đã đặt tour. Mã đơn hàng của bạn là: <strong>{{ session('order_code') }}</strong></p>
        <p>Chúng tôi sẽ sớm liên hệ với bạn qua số điện thoại để xác nhận và hướng dẫn thanh toán.</p>
        <a href="{{ route('frontend.home') }}" class="btn btn-primary mt-4">Về trang chủ</a>
    </div>
</main>
@endsection
