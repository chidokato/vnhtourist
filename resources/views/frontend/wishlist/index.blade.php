@extends('frontend.layouts.app')

@section('title', 'Tour Yêu Thích')

@section('content')
<main class="main pages-category">
    <div class="user-profile pt-50">
        <div class="container">
            <div class="row">
                @auth
                    @php
                        $user = auth()->user();
                    @endphp
                    <div class="col-lg-3">
                        @include('frontend.profile.sidebar')
                    </div>
                    <div class="col-lg-9">
                @else
                    <div class="col-lg-12">
                @endauth
                    <div class="user-profile-wrapper">
                        <div class="user-profile-card">
                            <h4 class="user-profile-card-title">Tour Yêu Thích</h4>
                            
                            @if($products->count() > 0)
                                <div class="row mt-30">
                                    @foreach ($products as $product)
                                        <div class="col-md-6 col-lg-4">
                                            @include('frontend.products._tour_card', [
                                                'product' => $product,
                                                'currentCategoryName' => 'Tour',
                                            ])
                                        </div>
                                    @endforeach
                                </div>

                                <div class="pagination-area my-4">
                                    {{ $products->links('pagination::bootstrap-4') }}
                                </div>
                            @else
                                <div class="wishlist-empty-state text-center" style="padding: 60px 0;">
                                    <i class="far fa-heart" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                                    <h4>Bạn chưa có tour yêu thích nào</h4>
                                    <p class="mb-4">Hãy khám phá các tour và lưu lại những lựa chọn tuyệt vời nhất.</p>
                                    <a href="{{ route('frontend.home') }}" class="theme-btn">Khám phá ngay</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
