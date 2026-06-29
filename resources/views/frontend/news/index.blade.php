@extends('frontend.layouts.app')

@php
    $pageHeading = $currentCategory?->name ?? 'Tin tức';
    $categoryDescription = trim((string) ($currentCategory->description ?? ''));
    $resolveImage = function ($value) {
        return \App\Support\MediaManager::publicUrl($value);
    };
@endphp

@section('title', $pageTitle ?? $pageHeading)
@section('meta_description', $pageDescription ?? '')
@section('meta_keywords', $pageKeywords ?? '')

@section('content')
    <main class="main news-category-page">
        <div class="site-breadcrumb" style="background: url(assets/img/banner/04.jpg)">
            <div class="container pt-10">
                <h2 class="breadcrumb-title">{{ $displayValue($pageHeading) }}</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
                    <li class="active">{{ $displayValue($pageHeading) }}</li>
                </ul>
            </div>
        </div>

        <div class="blog-area py-50">
            <div class="container">
                <div class="row">
                    @forelse ($posts as $post)
                        <div class="col-md-6 col-lg-4">
                            <div class="blog-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".{{ 25 + (($loop->index % 3) * 25) }}s">
                                <span class="blog-date">
                                    {{ optional($post->published_at)->format('d/m/Y') ?: 'Mới cập nhật' }}
                                </span>
                                <div class="blog-item-img">
                                    <a href="{{ $post->frontend_url }}">
                                        @if ($resolveImage($post->image))
                                            <img src="{{ $resolveImage($post->image) }}" alt="{{ $displayValue($post->title) }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="blog-item-info">
                                    <div class="blog-item-meta">
                                        <ul>
                                            <li>
                                                <a href="{{ $post->frontend_url }}">
                                                    <i class="far fa-user-circle"></i> Bởi Tourist
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ $post->frontend_url }}">
                                                    <i class="far fa-comments"></i> {{ $displayValue($post->category?->name ?? 'Tin du lịch') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <h4 class="blog-title">
                                        <a href="{{ $post->frontend_url }}">{{ $displayValue($post->title) }}</a>
                                    </h4>
                                    @if (filled($post->summary))
                                        <div class="news-summary">{!! $displayValue($post->summary) !!}</div>
                                    @endif
                                    <a class="theme-btn mt-3" href="{{ $post->frontend_url }}">
                                        Xem thêm <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="widget category-empty-state">
                                <h4 class="widget-title">Chưa có bài viết</h4>
                                <p class="mb-0">Danh mục này hiện chưa có nội dung. Bạn có thể quay lại sau hoặc xem các chuyên mục khác.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if ($posts->lastPage() > 1)
                    <div class="pagination-area">
                        <div aria-label="Phân trang">
                            <ul class="pagination">
                                <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $posts->previousPageUrl() ?: '#' }}" aria-label="Trang trước">
                                        <span aria-hidden="true"><i class="fas fa-arrow-left"></i></span>
                                    </a>
                                </li>
                                @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page === $posts->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                                <li class="page-item {{ $posts->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $posts->nextPageUrl() ?: '#' }}" aria-label="Trang sau">
                                        <span aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="pagination-showing">
                            Trang {{ $posts->currentPage() }}/{{ $posts->lastPage() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
