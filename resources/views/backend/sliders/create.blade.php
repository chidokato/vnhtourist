@extends('backend.layouts.app')

@section('title', 'Thêm mới Slider')
@section('page_title', 'Thêm mới Slider')
@section('breadcrumb', 'Thêm mới Slider')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Thêm Slider mới</h4>
            <div class="d-flex gap-2">
                <button type="submit" form="slider-form" class="btn btn-primary">Lưu lại</button>
                <a href="{{ route('backend.sliders.index') }}" class="btn btn-light">Quay lại</a>
            </div>
        </div>
        <div class="card-body bg-light">
            <form id="slider-form" action="{{ route('backend.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('backend.sliders._form', ['slider' => new \App\Models\Slider()])
            </form>
        </div>
    </div>
@endsection
