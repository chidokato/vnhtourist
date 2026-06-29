@extends('frontend.layouts.app')

@section('title', 'Thông tin tài khoản')

@section('content')
<main class="main pages-category">
    
    <div class="user-profile pt-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('frontend.profile.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="user-profile-wrapper">
                        <div class="user-profile-card user-profile-listing">
                            <div class="user-profile-card-header">
                                <h4 class="user-profile-card-title">Tour Đã Đặt</h4>
                                <div class="user-profile-card-header-right">
                                    <div class="user-profile-search">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Tìm kiếm...">
                                            <i class="far fa-search"></i>
                                        </div>
                                    </div>
                                    <a href="{{ route('frontend.home') }}" class="theme-btn"><span class="far fa-plus-circle"></span>Đặt thêm tour</a>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Thông tin Tour</th>
                                                <th>Mã đơn</th>
                                                <th>Trạng thái</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $order)
                                                @php
                                                    $firstItem = $order->items->first();
                                                    $tour = $firstItem ? $firstItem->post : null;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="table-listing-info">
                                                            <a href="{{ $tour ? $tour->frontend_url : '#' }}">
                                                                @php
                                                                    $tourImg = $tour ? \App\Support\MediaManager::publicUrl($tour->image) : null;
                                                                    if (!$tourImg) $tourImg = asset('tourit/assets/img/tour/01.webp');
                                                                @endphp
                                                                <img src="{{ $tourImg }}" alt="" style="object-fit: cover;">
                                                                <div class="table-listing-content">
                                                                    <h6>{{ $firstItem ? \Illuminate\Support\Str::limit($firstItem->tour_name, 40) : 'N/A' }}</h6>
                                                                    <p><i class="far fa-calendar-alt"></i> Ngày đặt đơn: {{ $order->created_at->format('d/m/Y') }}</p>
                                                                    <span>{{ number_format($order->total_amount) }} đ</span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6>#{{ $order->order_code }}</h6>
                                                    </td>
                                                   
                                                    <td>
                                                        @if($order->status == 'pending')
                                                            <span class="badge badge-warning">Chờ xử lý</span>
                                                        @elseif($order->status == 'confirmed')
                                                            <span class="badge badge-info">Đã xác nhận</span>
                                                        @elseif($order->status == 'paid')
                                                            <span class="badge badge-success">Đã thanh toán</span>
                                                        @else
                                                            <span class="badge badge-danger">Đã huỷ</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-outline-secondary btn-sm" title="Xem chi tiết"><i class="far fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">Bạn chưa có đơn đặt tour nào.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- pagination -->
                                <div class="pagination-area my-3">
                                    {{ $orders->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
