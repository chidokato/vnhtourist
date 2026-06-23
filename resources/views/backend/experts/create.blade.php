@extends('backend.layouts.app')

@section('title', 'Thêm chuyên gia')
@section('page_title', 'Thêm chuyên gia')
@section('breadcrumb', 'Thêm chuyên gia')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Thêm chuyên gia mới</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.experts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('backend.experts._form', ['expert' => new \App\Models\Expert()])
                        
                        <div class="text-end mt-4">
                            <a href="{{ route('backend.experts.index') }}" class="btn btn-light me-2">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
