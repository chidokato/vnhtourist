@extends('backend.layouts.app')

@section('title', 'Quản lý Slider')
@section('page_title', 'Quản lý Slider')
@section('breadcrumb', 'Quản lý Slider')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Danh sách Slider</h4>
            <a href="{{ route('backend.sliders.create') }}" class="btn btn-primary">Thêm mới</a>
        </div>
        <div class="card-body">
            @if ($sliders->isEmpty())
                <div class="text-center text-muted py-4">Chưa có dữ liệu Slider.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 150px;">Hình ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Tiêu đề phụ</th>
                                <th>Link</th>
                                <th>Thứ tự</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sliders as $slider)
                                <tr>
                                    <td>
                                        @if ($slider->image)
                                            <img src="{{ \App\Support\MediaManager::publicUrl($slider->image) }}" alt="" class="img-fluid rounded" style="max-height: 60px;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px;">
                                                <i class="far fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <h5 class="font-size-14 mb-1">{{ $slider->title ?: 'N/A' }}</h5>
                                    </td>
                                    <td>
                                        <p class="text-muted mb-0">{{ $slider->subtitle ?: 'N/A' }}</p>
                                    </td>
                                    <td>
                                        @if($slider->link)
                                            <a href="{{ $slider->link }}" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $slider->sort_order }}</td>
                                    <td>
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <button
                                                type="button"
                                                class="status-toggle {{ $slider->is_active ? 'is-active' : 'is-inactive' }}"
                                                data-toggle-status
                                                data-url="{{ route('backend.sliders.toggle-status', $slider) }}"
                                                aria-pressed="{{ $slider->is_active ? 'true' : 'false' }}"
                                            ></button>
                                            <span class="status-toggle-label {{ $slider->is_active ? 'text-success' : 'text-danger' }}" data-status-label>
                                                {{ $slider->is_active ? 'Hiển thị' : 'Ẩn' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('backend.sliders.edit', $slider) }}" class="btn btn-sm btn-soft-warning">Sửa</a>
                                        <form action="{{ route('backend.sliders.destroy', $slider) }}" method="POST" class="d-inline" data-confirm-delete data-confirm-message="Bạn có chắc chắn muốn xóa Slider này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $sliders->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
@endsection
