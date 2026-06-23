@extends('backend.layouts.app')

@section('title', 'Đội ngũ chuyên gia')
@section('page_title', 'Đội ngũ chuyên gia')
@section('breadcrumb', 'Đội ngũ chuyên gia')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Quản lý đội ngũ chuyên gia</h4>
            <a href="{{ route('backend.experts.create') }}" class="btn btn-primary">Thêm mới</a>
        </div>
        <div class="card-body">
            @if ($experts->isEmpty())
                <div class="text-center text-muted py-4">Chưa có dữ liệu đội ngũ chuyên gia.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">Hình ảnh</th>
                                <th>Tên / Chức vụ</th>
                                <th>MXH</th>
                                <th>Thứ tự</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($experts as $expert)
                                <tr>
                                    <td>
                                        @if ($expert->image)
                                            <img src="{{ \App\Support\MediaManager::publicUrl($expert->image) }}" alt="" class="avatar-sm rounded-circle object-cover">
                                        @else
                                            <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="far fa-user text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <h5 class="font-size-14 mb-1">{{ $expert->name }}</h5>
                                        <p class="text-muted mb-0">{{ $expert->role }}</p>
                                    </td>
                                    <td>
                                        @if($expert->facebook_url)<a href="{{ $expert->facebook_url }}" target="_blank" class="text-primary me-1"><i class="fab fa-facebook"></i></a>@endif
                                        @if($expert->twitter_url)<a href="{{ $expert->twitter_url }}" target="_blank" class="text-info me-1"><i class="fab fa-twitter"></i></a>@endif
                                        @if($expert->instagram_url)<a href="{{ $expert->instagram_url }}" target="_blank" class="text-danger me-1"><i class="fab fa-instagram"></i></a>@endif
                                        @if($expert->linkedin_url)<a href="{{ $expert->linkedin_url }}" target="_blank" class="text-primary"><i class="fab fa-linkedin"></i></a>@endif
                                    </td>
                                    <td>{{ $expert->sort_order }}</td>
                                    <td>
                                        <div class="form-check form-switch form-switch-md mb-0" dir="ltr">
                                            <input type="checkbox" class="form-check-input" id="status_{{ $expert->id }}" 
                                                onchange="toggleStatus({{ $expert->id }})"
                                                {{ $expert->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('backend.experts.edit', $expert) }}" class="btn btn-sm btn-soft-primary"><i class="fas fa-edit"></i></a>
                                        <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete({{ $expert->id }})"><i class="fas fa-trash"></i></button>
                                        <form id="delete-form-{{ $expert->id }}" action="{{ route('backend.experts.destroy', $expert) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $experts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleStatus(id) {
        fetch(`{{ url('admin/experts') }}/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(res => res.json()).then(data => {
            if (data.success) {
                // Success
            }
        });
    }

    function confirmDelete(id) {
        if (confirm('Bạn có chắc chắn muốn xóa chuyên gia này?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
