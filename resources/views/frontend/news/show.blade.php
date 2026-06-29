@extends('frontend.layouts.app')

@php
    $pageHeading = $post->title ?? 'Tin tức';
    $resolveImage = function ($value) {
        if (!\App\Support\MediaManager::diskPath($value)) {
            return null;
        }
        return \App\Support\MediaManager::publicUrl($value);
    };
    $placeholderImage = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='400' viewBox='0 0 600 400'%3E%3Crect width='600' height='400' fill='%23f0f0f0'/%3E%3Ctext x='300' y='200' fill='%23999999' font-family='sans-serif' font-size='24' text-anchor='middle' alignment-baseline='middle'%3EĐang cập nhật ảnh%3C/text%3E%3C/svg%3E";
@endphp

@section('title', $pageTitle ?? $pageHeading)
@section('meta_description', $pageDescription ?? '')
@section('meta_keywords', $pageKeywords ?? '')

@section('content')
    <main class="main news-category-page">
        <div class="site-breadcrumb" style="background: url({{ $resolveImage($post->image) ?: $placeholderImage }})">
            <div class="container pt-10">
                <h2 class="breadcrumb-title">Tin tức</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
                    <li><a href="{{ route('frontend.news.index') }}">Tin tức</a></li>
                    <li class="active">{{ $displayValue($pageHeading) }}</li>
                </ul>
            </div>
        </div>

        <div class="blog-single-area pt-50 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="blog-single-wrapper">
                            <div class="blog-single-content">
                                <div class="blog-info">
                                    
                                    <div class="blog-details">
                                        <h3 class="blog-details-title mb-20">{{ $displayValue($post->title) }}</h3>
                                        
                                        @if (filled($post->summary))
                                            <div class="mb-3">
                                                <strong>{!! $displayValue($post->summary) !!}</strong>
                                            </div>
                                        @endif
                                        
                                        <div class="content-text mt-4">
                                            {!! $displayValue($post->content) !!}
                                        </div>
                                    </div>

                                    <div class="blog-meta">
                                        <div class="blog-meta-left">
                                            <ul>
                                                <li><i class="far fa-user"></i> Bởi {{ $displayValue($post->user?->name ?? 'Admin') }}</li>
                                                <li><i class="far fa-comments"></i> {{ $displayValue($post->category?->name ?? 'Tin du lịch') }}</li>
                                                <li><i class="far fa-calendar-alt"></i> {{ optional($post->published_at)->format('d/m/Y') ?: 'Mới cập nhật' }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="blog-comments">
                                        <h3>Comments (20)</h3>
                                        <div class="blog-comments-wrapper">
                                            <div class="blog-comments-single">
                                                <div class="blog-comments-img"><img src="assets/img/blog/com-1.jpg" alt="thumb"></div>
                                                <div class="blog-comments-content">
                                                    <h5>Jesse Sinkler</h5>
                                                    <span><i class="far fa-clock"></i> 29 August, 2025</span>
                                                    <p>There are many variations of passages the majority have suffered in some injected humour or randomised words which don't look even slightly believable.</p>
                                                    <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                </div>
                                            </div>
                                            <div class="blog-comments-single blog-comments-reply">
                                                <div class="blog-comments-img"><img src="assets/img/blog/com-2.jpg" alt="thumb"></div>
                                                <div class="blog-comments-content">
                                                    <h5>Daniel Wellman</h5>
                                                    <span><i class="far fa-clock"></i> 29 August, 2025</span>
                                                    <p>There are many variations of passages the majority have suffered in some injected humour or randomised words which don't look even slightly believable.</p>
                                                    <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                </div>
                                            </div>
                                            <div class="blog-comments-single">
                                                <div class="blog-comments-img"><img src="assets/img/blog/com-3.jpg" alt="thumb"></div>
                                                <div class="blog-comments-content">
                                                    <h5>Kenneth Evans</h5>
                                                    <span><i class="far fa-clock"></i> 29 August, 2025</span>
                                                    <p>There are many variations of passages the majority have suffered in some injected humour or randomised words which don't look even slightly believable.</p>
                                                    <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="blog-comments-form">
                                            <h3>Leave A Comment</h3>
                                            <form action="#">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Your Name*">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="email" class="form-control" placeholder="Your Email*">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <textarea class="form-control" rows="5" placeholder="Your Comment*"></textarea>
                                                        </div>
                                                        <button type="submit" class="theme-btn">Post Comment <i class="far fa-paper-plane"></i></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <aside class="sidebar">
                            <div class="widget search">
                                <h5 class="widget-title">Tìm kiếm</h5>
                                <form action="{{ route('frontend.news.index') }}" method="GET" class="blog-search-form">
                                    <input type="text" name="keyword" class="form-control" placeholder="Nhập từ khóa..." value="{{ request('keyword') }}">
                                    <button type="submit"><i class="far fa-search"></i></button>
                                </form>
                            </div>

                            @if (isset($recentPosts) && $recentPosts->isNotEmpty())
                                <div class="widget recent-post">
                                    <h5 class="widget-title">Tin tức mới nhất</h5>
                                    @foreach ($recentPosts as $recent)
                                        <div class="recent-post-single">
                                            <div class="recent-post-img">
                                                <a href="{{ $recent->frontend_url }}">
                                                    @if ($resolveImage($recent->image))
                                                        <img src="{{ $resolveImage($recent->image) }}" alt="{{ $displayValue($recent->title) }}">
                                                    @else
                                                        <img src="{{ asset('tourit/assets/img/blog/01.jpg') }}" alt="Tin tức">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="recent-post-info">
                                                <span><i class="far fa-calendar-alt"></i> {{ optional($recent->published_at)->format('d/m/Y') ?: 'Mới cập nhật' }}</span>
                                                <h5><a href="{{ $recent->frontend_url }}">{{ $displayValue($recent->title) }}</a></h5>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if (isset($categories) && $categories->isNotEmpty())
                                <div class="widget category">
                                    <h5 class="widget-title">Chuyên mục</h5>
                                    <ul class="category-list">
                                        @foreach ($categories as $cat)
                                            <li>
                                                <a href="{{ route('frontend.categories.show', $cat->slug) }}">
                                                    {{ $displayValue($cat->name) }} <span>({{ $cat->news_posts_count ?? 0 }})</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (isset($relatedPosts) && $relatedPosts->isNotEmpty())
                                <div class="widget recent-post">
                                    <h5 class="widget-title">Tin tức liên quan</h5>
                                    @foreach ($relatedPosts as $related)
                                        <div class="recent-post-single">
                                            <div class="recent-post-img">
                                                <a href="{{ $related->frontend_url }}">
                                                    @if ($resolveImage($related->image))
                                                        <img src="{{ $resolveImage($related->image) }}" alt="{{ $displayValue($related->title) }}">
                                                    @else
                                                        <img src="{{ asset('tourit/assets/img/blog/bs-1.jpg') }}" alt="Tin tức">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="recent-post-bio">
                                                <h6><a href="{{ $related->frontend_url }}">{{ Str::limit($displayValue($related->title), 60) }}</a></h6>
                                                <span><i class="far fa-clock"></i>{{ optional($related->published_at)->format('d/m/Y') ?: 'Mới cập nhật' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="widget social-share">
                                <h5 class="widget-title">Follow Us</h5>
                                <div class="social-share-link">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-x-twitter"></i></a>
                                    <a href="#"><i class="fab fa-dribbble"></i></a>
                                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                                    <a href="#"><i class="fab fa-youtube"></i></a>
                                </div>
                            </div>

                            @if (!empty($post->tags) && is_array($post->tags))
                                <div class="widget sidebar-tag">
                                    <h5 class="widget-title">Từ khóa</h5>
                                    <div class="tag-list">
                                        @foreach ($post->tags as $tag)
                                            <a href="{{ route('frontend.news.index', ['tag' => $tag]) }}">{{ $tag }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
