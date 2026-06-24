<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;

class HomeController extends BaseFrontendController
{
    private function getCategoryIds(?\App\Models\Category $category): array
    {
        if (!$category) return [];
        $ids = [$category->id];
        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getCategoryIds($child));
        }
        return $ids;
    }

    public function index()
    {
        $seo = $this->staticPageSeo('home', [
            'title' => 'Trang chủ',
            'description' => 'Trang chủ du lịch NhaDatVN.',
            'keywords' => 'trang chủ, du lịch, tour, khách sạn, dịch vụ, NhaDatVN',
        ]);

        $locationProjects = Post::query()
            ->select('province_id')
            ->selectRaw('COUNT(*) as projects_count')
            ->where('type', Post::TYPE_PRODUCT)
            ->where('is_active', true)
            ->whereNotNull('province_id')
            ->groupBy('province_id')
            ->orderByDesc('projects_count')
            ->limit(4)
            ->get();

        $representativeProjects = Post::query()
            ->with('province')
            ->where('type', Post::TYPE_PRODUCT)
            ->where('is_active', true)
            ->whereIn('province_id', $locationProjects->pluck('province_id')->filter())
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get()
            ->groupBy('province_id');

        $locationProjects = $locationProjects
            ->map(function ($locationProject) use ($representativeProjects) {
                $project = $representativeProjects->get($locationProject->province_id)?->first();

                if (! $project || ! $project->province) {
                    return null;
                }

                return (object) [
                    'name' => $project->province->name,
                    'projects_count' => (int) $locationProject->projects_count,
                    'image' => $project->location_image ?: $project->image,
                    'frontend_url' => $project->frontend_url,
                ];
            })
            ->filter()
            ->values();

        $foreignCategory = \App\Models\Category::where('slug', 'tour-nuoc-ngoai')->first();
        $foreignCategoryIds = $this->getCategoryIds($foreignCategory);

        $foreignTours = Post::query()
            ->with(['category', 'galleryImages', 'province'])
            ->where('type', Post::TYPE_PRODUCT)
            ->where('is_active', true)
            ->whereIn('category_id', $foreignCategoryIds)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $domesticCategory = \App\Models\Category::where('slug', 'du-lich-trong-nuoc')->first();
        $domesticCategoryIds = $this->getCategoryIds($domesticCategory);

        $mbCategory = \App\Models\Category::where('slug', 'mien-bac')->first();
        $mtCategory = \App\Models\Category::where('slug', 'mien-trung')->first();
        $mnCategory = \App\Models\Category::where('slug', 'mien-nam')->first();

        $mbIds = $this->getCategoryIds($mbCategory);
        $mtIds = $this->getCategoryIds($mtCategory);
        $mnIds = $this->getCategoryIds($mnCategory);

        $resolveRegion = function (Post $tour) use ($mbIds, $mtIds, $mnIds) {
            $catId = $tour->category_id;
            if (in_array($catId, $mbIds)) {
                return 'mien-bac';
            }
            if (in_array($catId, $mtIds)) {
                return 'mien-trung';
            }
            if (in_array($catId, $mnIds)) {
                return 'mien-nam';
            }
            return 'mien-bac';
        };

        $domesticTours = Post::query()
            ->with(['category', 'galleryImages', 'province'])
            ->where('type', Post::TYPE_PRODUCT)
            ->where('is_active', true)
            ->when(
                ! empty($domesticCategoryIds),
                fn ($query) => $query->whereIn('category_id', $domesticCategoryIds),
                fn ($query) => $query->whereNotIn('category_id', $foreignCategoryIds)
            )
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(8)
            ->get()
            ->map(function (Post $tour) use ($resolveRegion) {
                $tour->home_region = $resolveRegion($tour);

                return $tour;
            });

        return view('frontend.home', $this->sharedViewData([
            'featuredProducts' => Post::query()
                ->with(['category', 'galleryImages'])
                ->where('type', Post::TYPE_PRODUCT)
                ->where('is_active', true)
                ->where('is_featured', true)
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(3)
                ->get(),
            'latestProducts' => Post::query()
                ->with(['category', 'galleryImages'])
                ->where('type', Post::TYPE_PRODUCT)
                ->where('is_active', true)
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(10)
                ->get(),
            'latestNews' => Post::query()
                ->with('category')
                ->where('type', Post::TYPE_NEWS)
                ->where('is_active', true)
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(3)
                ->get(),
            'locationProjects' => $locationProjects,
            'foreignTours' => $foreignTours,
            'domesticTours' => $domesticTours,
            'apartments' => \App\Models\Apartment::query()
                ->with(['images', 'project'])
                ->where('is_active', true)
                ->orderByDesc('id')
                ->limit(10)
                ->get(),
            'experts' => \App\Models\Expert::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(),
        ] + $seo));
    }
}
