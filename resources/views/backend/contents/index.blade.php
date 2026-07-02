@extends('backend.layouts.app')

@section('title', $typeLabel)
@section('page_title', $typeLabel)
@section('breadcrumb', $typeLabel)

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-2">
                        <select name="category_id" class="form-select">
                            <option value="">Danh mục</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($type === 'product')
                    <div class="col-md-2">
                        <select name="is_featured" class="form-select">
                            <option value="">Tour nổi bật</option>
                            <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Có</option>
                            <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Không</option>
                        </select>
                    </div>
                    @endif
                    <div class="col-md-2">
                        <select name="is_active" class="form-select">
                            <option value="">Trạng thái</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Đã ẩn</option>
                        </select>
                    </div>
                    @if ($postsHasUserIdColumn)
                    <div class="col-md-2">
                        <select name="user_id" class="form-select">
                            <option value="">Người đăng</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tên {{ $type === 'product' ? 'hoặc mã tour' : 'bài viết' }}..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Quan ly {{ strtolower($typeLabel) }}</h4>
            <a href="{{ route($type === 'product' ? 'backend.products.create' : 'backend.news.create') }}" class="btn btn-primary">
                Them {{ strtolower($typeLabel) }}
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            @if ($type === 'product')
                                <th>Mã tour</th>
                            @endif
                            <th>Tiêu đề</th>
                            <th>Category</th>
                            @if ($type === 'product')
                                <th>Gia</th>
                                <th>Tour nổi bật</th>
                                <th>Người đăng</th>
                            @endif
                            <th>Trạng thái</th>
                            <th>Ngày đăng</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                            <tr>
                                @if ($type === 'product')
                                    <td>{{ $displayValue($post->tour_code) }}</td>
                                @endif
                                <td style="max-width: 300px;">
                                    <div class="fw-medium text-truncate" title="{{ $post->title }}">{{ $displayValue($post->title) }}</div>
                                    <div class="text-muted small text-truncate" title="{{ $post->slug }}">{{ $displayValue($post->slug) }}</div>
                                </td>
                                <td>{{ $displayValue(optional($post->category)->name) }}</td>
                                @if ($type === 'product')
                                    <td>{{ $displayValue($post->price !== null ? number_format((float) $post->price, 0, ',', '.') . ' đ' : null) }}</td>
                                    <td>
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <button
                                                type="button"
                                                class="status-toggle {{ $post->is_featured ? 'is-active' : 'is-inactive' }}"
                                                data-toggle-status
                                                data-url="{{ route('backend.products.toggle-featured', $post) }}"
                                                aria-pressed="{{ $post->is_featured ? 'true' : 'false' }}"
                                            ></button>
                                            <span class="status-toggle-label {{ $post->is_featured ? 'text-success' : 'text-danger' }}" data-status-label>
                                                {{ $post->is_featured ? 'Bat' : 'Tat' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ ($postsHasUserIdColumn ?? false)
                                            ? $displayValue(optional($post->user)->name, 'Chua gan')
                                            : 'Chua cap nhat DB' }}
                                    </td>
                                @endif
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <button
                                            type="button"
                                            class="status-toggle {{ $post->is_active ? 'is-active' : 'is-inactive' }}"
                                            data-toggle-status
                                            data-url="{{ route($type === 'product' ? 'backend.products.toggle-status' : 'backend.news.toggle-status', $post) }}"
                                            aria-pressed="{{ $post->is_active ? 'true' : 'false' }}"
                                        ></button>
                                        <span class="status-toggle-label {{ $post->is_active ? 'text-success' : 'text-danger' }}" data-status-label>
                                            {{ $post->is_active ? 'Hien thi' : 'An' }}
                                        </span>
                                    </div>
                                </td>
                                <td>{{ $displayValue(($post->published_at ?? $post->created_at)?->format('d/m/Y H:i')) }}</td>
                                <td class="text-end">
                                    <a href="{{ route($type === 'product' ? 'backend.products.edit' : 'backend.news.edit', $post) }}" class="btn btn-sm btn-soft-warning">Sua</a>
                                    <form action="{{ route($type === 'product' ? 'backend.products.destroy' : 'backend.news.destroy', $post) }}" method="POST" class="d-inline" data-confirm-delete data-confirm-message="Ban co chac muon xoa {{ strtolower($typeLabel) }} nay?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft-danger">Xoa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $type === 'product' ? 9 : 5 }}" class="text-center text-muted py-4">
                                    Chua co {{ strtolower($typeLabel) }} nao.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
