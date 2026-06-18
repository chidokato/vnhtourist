<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends BaseFrontendController
{
    public function productCategory(string $slug)
    {
        $category = Category::query()
            ->where('type', Category::TYPE_PRODUCT)
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $payload = $this->buildProductCategoryPayload($category, request());

        return view('frontend.products.index', $this->sharedViewData([
            'category' => $category,
            'products' => $payload['products'],
            'filterCategoryOptions' => $payload['filterCategoryOptions'],
            'destinationOptions' => $payload['destinationOptions'],
            'selectedCategoryId' => $payload['selectedCategoryId'],
            'selectedDestinations' => $payload['selectedDestinations'],
            'selectedDepartureDate' => $payload['selectedDepartureDate'],
            'ajaxUrl' => route('frontend.products.filter', $category->slug),
            'pageTitle' => $category->seo_title ?: $category->name,
            'pageDescription' => $category->seo_description,
        ]));
    }

    public function productCategoryAjax(Request $request, string $slug): JsonResponse
    {
        $category = Category::query()
            ->where('type', Category::TYPE_PRODUCT)
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $payload = $this->buildProductCategoryPayload($category, $request);

        return response()->json([
            'html' => view('frontend.products._listing', [
                'products' => $payload['products'],
                'currentCategoryName' => $category->name,
            ])->render(),
            'destinationOptions' => $payload['destinationOptions'],
            'selectedCategoryId' => $payload['selectedCategoryId'],
            'selectedDestinations' => $payload['selectedDestinations'],
            'selectedDepartureDate' => $payload['selectedDepartureDate'],
            'total' => $payload['products']->total(),
        ]);
    }

    public function categoryBySlug(string $slug)
    {
        $category = Category::query()
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        if ($category->type === Category::TYPE_PRODUCT) {
            return $this->productCategory($slug);
        }

        return $this->newsCategory($slug);
    }

    public function legacyProductShow(string $slug)
    {
        $product = Post::query()
            ->with(['category', 'province', 'ward.province', 'galleryImages', 'apartments.images'])
            ->where('type', Post::TYPE_PRODUCT)
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        if (! $this->shouldRedirectToCanonical($product->frontend_url)) {
            return $this->renderProductShow($product);
        }

        return redirect()->to($product->frontend_url, $this->canonicalRedirectStatus());
    }

    public function legacyNewsShow(string $slug)
    {
        $post = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        if (! $this->shouldRedirectToCanonical($post->frontend_url)) {
            return $this->renderNewsShow($post);
        }

        return redirect()->to($post->frontend_url, $this->canonicalRedirectStatus());
    }

    public function contentShow(string $categorySlug, string $slug)
    {
        $category = Category::query()
            ->where('is_active', true)
            ->where('slug', $categorySlug)
            ->firstOrFail();

        if ($category->type === Category::TYPE_PRODUCT) {
            $product = Post::query()
                ->with(['category', 'province', 'ward.province', 'galleryImages', 'seller', 'apartments.images'])
                ->where('type', Post::TYPE_PRODUCT)
                ->where('is_active', true)
                ->where('category_id', $category->id)
                ->where('slug', $slug)
                ->firstOrFail();

            return $this->renderProductShow($product);
        }

        $post = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->renderNewsShow($post);
    }

    protected function renderProductShow(Post $product)
    {
        $contactSeller = User::query()
            ->orderBy('id')
            ->first();

        if ($product->relationLoaded('seller') && $product->seller) {
            $contactSeller = $product->seller;
        }

        $relatedProducts = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_PRODUCT)
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, function ($query) use ($product) {
                $query->where('category_id', $product->category_id);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return view('frontend.products.show', $this->sharedViewData([
            'product' => $product,
            'contactSeller' => $contactSeller,
            'relatedProducts' => $relatedProducts,
            'pageTitle' => $product->seo_title ?: $product->title,
            'pageDescription' => $product->seo_description ?: $product->summary,
        ]));
    }

    public function newsIndex()
    {
        $categories = Category::query()
            ->where('type', Category::TYPE_NEWS)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categoryTree = $this->buildCategoryTree($categories);

        $posts = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();
        $allNewsPosts = Post::query()
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->get(['id', 'category_id']);
        $categoryTree = $this->attachPostCountsToCategoryTree($categoryTree, $allNewsPosts, 'news_posts_count');
        $recentPosts = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('frontend.news.index', $this->sharedViewData([
            'currentCategory' => null,
            'categories' => $categoryTree,
            'posts' => $posts,
            'recentPosts' => $recentPosts,
            'pageTitle' => 'Tin tức',
            'pageDescription' => 'Tin tức và bài viết mới nhất.',
        ]));
    }

    public function newsCategory(string $slug)
    {
        $category = Category::query()
            ->where('type', Category::TYPE_NEWS)
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $allCategories = Category::query()
            ->where('type', Category::TYPE_NEWS)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categoryIds = $this->collectDescendantIds($allCategories, $category->id);
        $categoryTree = $this->buildCategoryTree($allCategories);

        $posts = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->whereIn('category_id', $categoryIds)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();
        $allNewsPosts = Post::query()
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->get(['id', 'category_id']);
        $categoryTree = $this->attachPostCountsToCategoryTree($categoryTree, $allNewsPosts, 'news_posts_count');
        $currentPostIds = $posts->getCollection()->pluck('id')->filter()->values();
        $recentPosts = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->when($currentPostIds->isNotEmpty(), function ($query) use ($currentPostIds) {
                $query->whereNotIn('id', $currentPostIds);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('frontend.news.index', $this->sharedViewData([
            'currentCategory' => $category,
            'categories' => $categoryTree,
            'posts' => $posts,
            'recentPosts' => $recentPosts,
            'pageTitle' => $category->seo_title ?: $category->name,
            'pageDescription' => $category->seo_description,
        ]));
    }

    protected function renderNewsShow(Post $post)
    {
        $relatedPosts = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->where('id', '!=', $post->id)
            ->when($post->category_id, function ($query) use ($post) {
                $query->where('category_id', $post->category_id);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $categories = Category::query()
            ->where('type', Category::TYPE_NEWS)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $categoryTree = $this->buildCategoryTree($categories);

        $recentPosts = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();
        $allNewsPosts = Post::query()
            ->where('type', Post::TYPE_NEWS)
            ->where('is_active', true)
            ->get(['id', 'category_id']);
        $categoryTree = $this->attachPostCountsToCategoryTree($categoryTree, $allNewsPosts, 'news_posts_count');

        return view('frontend.news.show', $this->sharedViewData([
            'post' => $post,
            'categories' => $categoryTree,
            'recentPosts' => $recentPosts,
            'relatedPosts' => $relatedPosts,
            'pageTitle' => $post->seo_title ?: $post->title,
            'pageDescription' => $post->seo_description ?: $post->summary,
        ]));
    }

    public function about()
    {
        return view('frontend.pages.about', $this->sharedViewData(
            $this->staticPageSeo('about', [
                'title' => 'Giới thiệu',
                'description' => 'Thông tin giới thiệu về công ty.',
                'keywords' => 'giới thiệu, công ty, NhaDatVN',
            ])
        ));
    }

    public function contact()
    {
        return view('frontend.pages.contact', $this->sharedViewData(
            $this->staticPageSeo('contact', [
                'title' => 'Liên hệ',
                'description' => 'Thông tin liên hệ với chúng tôi.',
                'keywords' => 'liên hệ, hotline, địa chỉ, NhaDatVN',
            ])
        ));
    }

    protected function shouldRedirectToCanonical(string $canonicalUrl): bool
    {
        $currentUrl = url()->current();

        return rtrim($canonicalUrl, '/') !== rtrim($currentUrl, '/');
    }

    protected function canonicalRedirectStatus(): int
    {
        return app()->environment('local') ? 302 : 301;
    }

    protected function buildProductCategoryPayload(Category $category, Request $request): array
    {
        $allCategories = Category::query()
            ->where('type', Category::TYPE_PRODUCT)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $selectedCategoryId = (int) $request->query('category_id', $category->id);

        if (! $allCategories->contains('id', $selectedCategoryId)) {
            $selectedCategoryId = $category->id;
        }

        $selectedDestinations = collect($request->query('destinations', []))
            ->map(fn ($value) => trim((string) $value))
            ->filter(fn ($value) => $value !== '')
            ->unique()
            ->values();
        $selectedDepartureDate = trim((string) $request->query('departure_date', ''));
        $selectedKeyword = trim((string) $request->query('keyword', ''));
        $selectedCategoryIds = $this->collectDescendantIds($allCategories, $selectedCategoryId);

        $destinationOptions = Post::query()
            ->where('type', Post::TYPE_PRODUCT)
            ->where('is_active', true)
            ->whereIn('category_id', $selectedCategoryIds)
            ->whereNotNull('destination')
            ->where('destination', '!=', '')
            ->orderBy('destination')
            ->distinct()
            ->pluck('destination')
            ->values();

        $products = Post::query()
            ->with('category')
            ->where('type', Post::TYPE_PRODUCT)
            ->where('is_active', true)
            ->whereIn('category_id', $selectedCategoryIds)
            ->when($selectedDestinations->isNotEmpty(), function ($query) use ($selectedDestinations) {
                $query->whereIn('destination', $selectedDestinations->all());
            })
            ->when($selectedDepartureDate !== '', function ($query) use ($selectedDepartureDate) {
                $query->whereDate('departure_date', $selectedDepartureDate);
            })
            ->when($selectedKeyword !== '', function ($query) use ($selectedKeyword) {
                $query->where('title', 'like', '%' . $selectedKeyword . '%');
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->appends([
                'category_id' => $selectedCategoryId,
                'destinations' => $selectedDestinations->all(),
                'departure_date' => $selectedDepartureDate,
                'keyword' => $selectedKeyword,
            ]);

        return [
            'products' => $products,
            'filterCategoryOptions' => $this->buildAllCategoryOptions($allCategories),
            'destinationOptions' => $destinationOptions,
            'selectedCategoryId' => $selectedCategoryId,
            'selectedDestinations' => $selectedDestinations->all(),
            'selectedDepartureDate' => $selectedDepartureDate,
        ];
    }
}
