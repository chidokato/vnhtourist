@extends('backend.layouts.app')

@section('title', 'Them tuy chon tour')
@section('page_title', 'Them tuy chon tour')
@section('breadcrumb', 'Them tuy chon tour')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Them tuy chon tour moi</h4>
            <div class="d-flex gap-2">
                <button type="submit" form="tour-option-form" class="btn btn-primary">Luu tuy chon</button>
                <a href="{{ route('backend.tour-options.index') }}" class="btn btn-light">Quay lai</a>
            </div>
        </div>
        <div class="card-body">
            <form id="tour-option-form" action="{{ route('backend.tour-options.store') }}" method="POST">
                @include('backend.tour-options._form')
            </form>
        </div>
    </div>
@endsection
