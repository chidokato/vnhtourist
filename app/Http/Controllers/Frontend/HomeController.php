<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;

class HomeController extends BaseFrontendController
{
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
        $foreignCategoryIds = $foreignCategory ? $foreignCategory->children()->pluck('id')->push($foreignCategory->id)->toArray() : [];

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
        $domesticCategoryIds = $domesticCategory ? $domesticCategory->children()->pluck('id')->push($domesticCategory->id)->toArray() : [];

        $northKeywords = ['ha noi', 'ninh binh', 'ha giang', 'sapa', 'lao cai', 'quang ninh', 'ha long', 'hai phong', 'nam dinh', 'thai binh', 'bac ninh', 'bac giang', 'phu tho', 'yen bai', 'son la', 'dien bien', 'lai chau', 'hoa binh', 'lang son', 'cao bang', 'bac kan', 'tuyen quang', 'thai nguyen', 'vinh phuc', 'ha nam', 'hung yen', 'hai duong'];
        $centralKeywords = ['da nang', 'hue', 'quang nam', 'hoi an', 'quang ngai', 'binh dinh', 'phu yen', 'khanh hoa', 'nha trang', 'ninh thuan', 'binh thuan', 'quang binh', 'quang tri', 'ha tinh', 'nghe an', 'thanh hoa', 'dak lak', 'gia lai', 'kon tum', 'lam dong', 'da lat', 'dak nong'];
        $southKeywords = ['ho chi minh', 'sai gon', 'vung tau', 'ba ria', 'dong nai', 'binh duong', 'tay ninh', 'binh phuoc', 'long an', 'tien giang', 'ben tre', 'tra vinh', 'vinh long', 'dong thap', 'an giang', 'kien giang', 'phu quoc', 'can tho', 'hau giang', 'soc trang', 'bac lieu', 'ca mau'];

        $resolveRegion = function (Post $tour) use ($northKeywords, $centralKeywords, $southKeywords) {
            $haystack = \Illuminate\Support\Str::lower(trim(implode(' ', array_filter([
                $tour->province?->name,
                $tour->destination,
                $tour->departure_location,
                $tour->address,
            ]))));

            foreach ($northKeywords as $keyword) {
                if (\Illuminate\Support\Str::contains($haystack, $keyword)) {
                    return 'mien-bac';
                }
            }

            foreach ($centralKeywords as $keyword) {
                if (\Illuminate\Support\Str::contains($haystack, $keyword)) {
                    return 'mien-trung';
                }
            }

            foreach ($southKeywords as $keyword) {
                if (\Illuminate\Support\Str::contains($haystack, $keyword)) {
                    return 'mien-nam';
                }
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
        ] + $seo));
    }
}
