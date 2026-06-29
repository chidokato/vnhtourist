@extends('backend.layouts.app')

@section('title', 'Quản lý Đơn hàng')
@section('page_title', 'Quản lý Đơn hàng')
@section('breadcrumb', 'Đơn hàng')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Danh sách đơn hàng</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Thông tin liên hệ</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thời gian</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>
                                    <span class="fw-medium text-primary">{{ $order->order_code }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $order->name }}</div>
                                    @if($order->user_id)
                                        <div class="text-muted small">TV: {{ optional($order->user)->name }}</div>
                                    @else
                                        <div class="text-muted small">Khách vãng lai</div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $order->phone }}</div>
                                    <div class="text-muted small">{{ $order->email }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} đ</div>
                                </td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                    @elseif($order->status == 'confirmed')
                                        <span class="badge bg-info">Đã xác nhận</span>
                                    @elseif($order->status == 'paid')
                                        <span class="badge bg-success">Đã thanh toán</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('backend.orders.show', $order->id) }}" class="btn btn-sm btn-soft-info">Xem chi tiết</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Chưa có đơn hàng nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
