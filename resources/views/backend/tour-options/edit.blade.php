@extends('backend.layouts.app')

@section('title', 'Sua tuy chon tour')
@section('page_title', 'Sua tuy chon tour')
@section('breadcrumb', 'Sua tuy chon tour')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Cap nhat tuy chon tour</h4>
            <div class="d-flex gap-2">
                <button type="submit" form="tour-option-form" class="btn btn-primary">Cap nhat tuy chon</button>
                <a href="{{ route('backend.tour-options.index') }}" class="btn btn-light">Quay lai</a>
            </div>
        </div>
        <div class="card-body">
            <form id="tour-option-form" action="{{ route('backend.tour-options.update', $tourOption) }}" method="POST">
                @csrf
                @method('PUT')
                @include('backend.tour-options._form', ['tourOption' => $tourOption])
            </form>
        </div>
    </div>
@endsection
