@extends('backend.layouts.app')

@section('title', 'Sửa Slider')
@section('page_title', 'Sửa Slider')
@section('breadcrumb', 'Sửa Slider')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Sửa thông tin Slider</h4>
            <div class="d-flex gap-2">
                <button type="submit" form="slider-form" class="btn btn-primary">Lưu thay đổi</button>
                <a href="{{ route('backend.sliders.index') }}" class="btn btn-light">Quay lại</a>
            </div>
        </div>
        <div class="card-body bg-light">
            <form id="slider-form" action="{{ route('backend.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('backend.sliders._form', ['slider' => $slider])
            </form>
        </div>
    </div>
@endsection
