@extends('backend.layouts.app')

@section('title', 'Chi tiết Đơn hàng')
@section('page_title', 'Chi tiết Đơn hàng: ' . $order->order_code)
@section('breadcrumb', 'Đơn hàng')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Danh sách Tour/Sản phẩm</h5>
                        <div class="flex-shrink-0">
                            Mã ĐH: <span class="fw-bold text-primary">{{ $order->order_code }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th scope="col">Chi tiết Tour</th>
                                    <th scope="col">Ngày khởi hành</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col" class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <h5 class="fs-14"><a href="{{ route('frontend.products.show.legacy', $item->post->slug ?? 'updating') }}" target="_blank" class="text-dark">{{ $item->tour_name }}</a></h5>
                                            <p class="text-muted mb-0">Mã tour: <span class="fw-medium">{{ optional($item->post)->tour_code ?? 'Đang cập nhật' }}</span></p>
                                        </td>
                                        <td>{{ $item->departure_date ?: 'Đang cập nhật' }}</td>
                                        <td>
                                            <div>Người lớn: {{ $item->adult_quantity }}</div>
                                            @if($item->child_quantity > 0)
                                            <div>Trẻ em: {{ $item->child_quantity }}</div>
                                            @endif
                                            @if($item->infant_quantity > 0)
                                            <div>Em bé: {{ $item->infant_quantity }}</div>
                                            @endif
                                        </td>
                                        <td class="fw-medium text-end">
                                            {{ number_format($item->total, 0, ',', '.') }} đ
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="border-top border-top-dashed">
                                    <td colspan="3" class="text-end fw-medium p-3">Tổng cộng:</td>
                                    <td class="text-end fw-bold text-danger p-3 fs-15">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ghi chú của khách hàng</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">{{ $order->notes ?: 'Không có ghi chú' }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0">Trạng thái đơn hàng</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select class="form-select" name="status">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cập nhật trạng thái</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0">Thông tin khách hàng</h5>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 vstack gap-3">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-user-line fs-16 text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-1">{{ $order->name }}</h6>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-mail-line fs-16 text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-1">{{ $order->email }}</h6>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-phone-line fs-16 text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-1">{{ $order->phone }}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
