@extends('backend.layouts.app')

@section('title', 'Sửa chuyên gia')
@section('page_title', 'Sửa chuyên gia')
@section('breadcrumb', 'Sửa chuyên gia')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sửa thông tin chuyên gia</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.experts.update', $expert) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('backend.experts._form', ['expert' => $expert])
                        
                        <div class="text-end mt-4">
                            <a href="{{ route('backend.experts.index') }}" class="btn btn-light me-2">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
