@extends('backend.layouts.app')

@section('title', 'Tuy chon tour')
@section('page_title', 'Tuy chon tour')
@section('breadcrumb', 'Tuy chon tour')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Quan ly tuy chon tour</h4>
            <a href="{{ route('backend.tour-options.create') }}" class="btn btn-primary">Them tuy chon</a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @foreach ($groups as $groupKey => $label)
                    @php
                        $groupOptions = $options->get($groupKey, collect());
                    @endphp
                    <div class="col-12">
                        <div class="border rounded">
                            <div class="border-bottom px-3 py-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="mb-1">{{ $displayValue($label) }}</h5>
                                    <p class="text-muted mb-0">Danh sach tuy chon cho {{ strtolower($label) }}.</p>
                                </div>
                                <a href="{{ route('backend.tour-options.create', ['group_key' => $groupKey]) }}" class="btn btn-soft-primary btn-sm">Them {{ strtolower($label) }}</a>
                            </div>
                            <div class="p-3">
                                @if ($groupOptions->isEmpty())
                                    <div class="text-muted">Chua co tuy chon nao.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-nowrap align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Ten tuy chon</th>
                                                    <th>Thu tu</th>
                                                    <th>Trang thai</th>
                                                    <th class="text-end">Thao tac</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($groupOptions as $option)
                                                    <tr>
                                                        <td>{{ $displayValue($option->name) }}</td>
                                                        <td>{{ $displayValue($option->sort_order) }}</td>
                                                        <td>
                                                            <span class="badge {{ $option->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                                {{ $option->is_active ? 'Hien thi' : 'An' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ route('backend.tour-options.edit', $option) }}" class="btn btn-sm btn-soft-warning">Sua</a>
                                                            <form action="{{ route('backend.tour-options.destroy', $option) }}" method="POST" class="d-inline" data-confirm-delete data-confirm-message="Ban co chac muon xoa tuy chon nay?">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-soft-danger">Xoa</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
