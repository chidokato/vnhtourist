@extends('backend.layouts.app')

@section('title', 'Tùy chọn tour')
@section('page_title', 'Tùy chọn tour')
@section('breadcrumb', 'Tùy chọn tour')

@section('content')
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <form action="{{ route('backend.tour-options.index') }}" method="GET" class="d-flex gap-2">
                <select name="group_key" class="form-select form-select-sm" style="width: auto; min-width: 150px;">
                    <option value="">-- Tất cả nhóm --</option>
                    @foreach($groups as $key => $label)
                        <option value="{{ $key }}" {{ request('group_key') == $key ? 'selected' : '' }}>{{ $displayValue($label) }}</option>
                    @endforeach
                </select>
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm kiếm tùy chọn..." value="{{ request('keyword') }}">
                <button type="submit" class="btn btn-sm btn-primary">Tìm</button>
                <a href="{{ route('backend.tour-options.index') }}" class="btn btn-sm btn-outline-secondary text-nowrap">Làm mới</a>
            </form>
            <a href="{{ route('backend.tour-options.create') }}" class="btn btn-sm btn-primary">Thêm tùy chọn</a>
        </div>
        <div class="card-body">
            @if ($options->isEmpty())
                <div class="text-muted">Chưa có tùy chọn nào.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nhóm</th>
                                <th>Tên tùy chọn</th>
                                <th>Thứ tự</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($options as $option)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $groups[$option->group_key] ?? $option->group_key }}</span>
                                    </td>
                                    <td>{{ $displayValue($option->name) }}</td>
                                    <td>{{ $displayValue($option->sort_order) }}</td>
                                    <td>
                                        <span class="badge {{ $option->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                            {{ $option->is_active ? 'Hiển thị' : 'Ẩn' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('backend.tour-options.edit', $option) }}" class="btn btn-sm btn-soft-warning">Sửa</a>
                                        <form action="{{ route('backend.tour-options.destroy', $option) }}" method="POST" class="d-inline" data-confirm-delete data-confirm-message="Bạn có chắc muốn xóa tùy chọn này?">
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
                
                <div class="mt-4">
                    {{ $options->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
@endsection
