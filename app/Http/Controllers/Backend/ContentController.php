<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Province;
use App\Models\TourOption;
use App\Models\User;
use App\Models\Ward;
use App\Support\MediaManager;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContentController extends Controller
{
    protected ?bool $postsHasUserIdColumn = null;

    public function productIndex()
    {
        return $this->indexByType(Post::TYPE_PRODUCT);
    }

    public function productCreate()
    {
        return $this->createByType(Post::TYPE_PRODUCT);
    }

    public function productStore(Request $request)
    {
        return $this->storeByType($request, Post::TYPE_PRODUCT);
    }

    public function productEdit(Post $post)
    {
        return $this->editByType($post, Post::TYPE_PRODUCT);
    }

    public function productUpdate(Request $request, Post $post)
    {
        return $this->updateByType($request, $post, Post::TYPE_PRODUCT);
    }

    public function productDestroy(Post $post)
    {
        return $this->destroyByType($post, Post::TYPE_PRODUCT);
    }

    public function newsIndex()
    {
        return $this->indexByType(Post::TYPE_NEWS);
    }

    public function newsCreate()
    {
        return $this->createByType(Post::TYPE_NEWS);
    }

    public function newsStore(Request $request)
    {
        return $this->storeByType($request, Post::TYPE_NEWS);
    }

    public function newsEdit(Post $post)
    {
        return $this->editByType($post, Post::TYPE_NEWS);
    }

    public function newsUpdate(Request $request, Post $post)
    {
        return $this->updateByType($request, $post, Post::TYPE_NEWS);
    }

    public function newsDestroy(Post $post)
    {
        return $this->destroyByType($post, Post::TYPE_NEWS);
    }

    public function productToggleStatus(Post $post)
    {
        return $this->toggleStatusByType($post, Post::TYPE_PRODUCT);
    }

    public function productToggleFeatured(Post $post)
    {
        abort_unless($post->type === Post::TYPE_PRODUCT, 404);

        $post->update([
            'is_featured' => ! $post->is_featured,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái dự án nổi bật thành công.',
            'is_active' => $post->is_featured,
            'label' => $post->is_featured ? 'Bật' : 'Tắt',
        ]);
    }

    public function newsToggleStatus(Post $post)
    {
        return $this->toggleStatusByType($post, Post::TYPE_NEWS);
    }

    public function uploadEditorImage(Request $request)
    {
        $validated = $request->validate([
            'upload' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $path = $this->storeImage($validated['upload']);

        return response()->json([
            'url' => MediaManager::publicUrl($path),
        ]);
    }

    protected function indexByType(string $type)
    {
        $relations = ['category'];

        if ($this->postsHasUserIdColumn()) {
            $relations[] = 'user';
        }

        $search = request('search');
        $categoryId = request('category_id');

        $posts = Post::query()
            ->with($relations)
            ->where('type', $type)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('tour_code', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.contents.index', [
            'posts' => $posts,
            'type' => $type,
            'typeLabel' => Post::types()[$type],
            'postsHasUserIdColumn' => $this->postsHasUserIdColumn(),
            'categories' => $this->categoryOptions($type),
        ]);
    }

    protected function createByType(string $type)
    {
        $selectedProvinceId = (int) old('province_id', 0) ?: null;

        return view('backend.contents.create', [
            'type' => $type,
            'typeLabel' => Post::types()[$type],
            'categories' => $this->categoryOptions($type),
            'sellerOptions' => $this->sellerOptions(),
            'tourOptionGroups' => $this->tourOptionGroups([
                TourOption::GROUP_TRANSPORT => old('transport', []),
                TourOption::GROUP_DEPARTURE_DATE => old('departure_date', []),
                TourOption::GROUP_LOCATION => array_filter([
                    old('departure_location'),
                ]),
                TourOption::GROUP_TAG => old('tags', []),
            ]),
            'provinceOptions' => $this->provinceOptions(),
            'wardOptions' => $this->wardOptionsForProvince($selectedProvinceId),
            'wardMap' => $this->wardMap(),
            'galleryImages' => collect(),
        ]);
    }

    protected function storeByType(Request $request, string $type)
    {
        $validated = $this->validatePost($request, $type);

        $post = Post::create($this->payload($request, $validated, $type));
        $this->syncGalleryImages($request, $post, $type);
        $this->syncDeparturePrices($request, $post, $type);

        return redirect()
            ->route($this->routePrefix($type) . '.index')
            ->with('success', 'Thêm ' . strtolower(Post::types()[$type]) . ' thành công.');
    }

    protected function editByType(Post $post, string $type)
    {
        abort_unless($post->type === $type, 404);

        $selectedProvinceId = (int) old('province_id', $post->province_id) ?: null;

        return view('backend.contents.edit', [
            'post' => $post,
            'type' => $type,
            'typeLabel' => Post::types()[$type],
            'categories' => $this->categoryOptions($type),
            'sellerOptions' => $this->sellerOptions(),
            'departurePrices' => $post->departurePrices->mapWithKeys(function ($dp) {
                return [$dp->departure_date => $dp->price / 1000000];
            })->toArray(),
            'tourOptionGroups' => $this->tourOptionGroups([
                TourOption::GROUP_TRANSPORT => old('transport', isset($post) && $post->transport ? (is_array($post->transport) ? $post->transport : explode(', ', $post->transport)) : []),
                TourOption::GROUP_DEPARTURE_DATE => old('departure_date', isset($post) && $post->departure_date ? (is_array($post->departure_date) ? $post->departure_date : explode(', ', $post->departure_date)) : []),
                TourOption::GROUP_LOCATION => array_filter([
                    old('departure_location', $post->departure_location),
                ]),
                TourOption::GROUP_TAG => old('tags', isset($post) && $post->tags ? (is_array($post->tags) ? $post->tags : explode(', ', $post->tags)) : []),
            ]),
            'provinceOptions' => $this->provinceOptions(),
            'wardOptions' => $this->wardOptionsForProvince($selectedProvinceId),
            'wardMap' => $this->wardMap(),
            'galleryImages' => $post->galleryImages,
        ]);
    }

    protected function updateByType(Request $request, Post $post, string $type)
    {
        abort_unless($post->type === $type, 404);

        $validated = $this->validatePost($request, $type, $post->id);

        $post->update($this->payload($request, $validated, $type, $post));
        $this->syncGalleryImages($request, $post, $type);
        $this->syncDeparturePrices($request, $post, $type);

        if ($request->boolean('save_stay')) {
            return redirect()
                ->route($this->routePrefix($type) . '.edit', $post)
                ->with('success', 'Cập nhật ' . strtolower(Post::types()[$type]) . ' thành công.');
        }

        return redirect()
            ->route($this->routePrefix($type) . '.index')
            ->with('success', 'Cập nhật ' . strtolower(Post::types()[$type]) . ' thành công.');
    }

    protected function destroyByType(Post $post, string $type)
    {
        abort_unless($post->type === $type, 404);

        $this->deleteImageIfExists($post->image);
        $this->deleteImageIfExists($post->location_image);
        foreach ($post->galleryImages as $image) {
            $this->deleteImageIfExists($image->image);
        }
        $post->delete();

        return redirect()
            ->route($this->routePrefix($type) . '.index')
            ->with('success', 'Xóa ' . strtolower(Post::types()[$type]) . ' thành công.');
    }

    protected function toggleStatusByType(Post $post, string $type)
    {
        abort_unless($post->type === $type, 404);

        $post->update([
            'is_active' => ! $post->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái ' . strtolower(Post::types()[$type]) . ' thành công.',
            'is_active' => $post->is_active,
            'label' => $post->is_active ? 'Hiển thị' : 'Ẩn',
        ]);
    }

    protected function validatePost(Request $request, string $type, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('type', $type)),
            ],
            'seller_id' => $type === Post::TYPE_PRODUCT
                ? ['nullable', 'integer', Rule::exists('users', 'id')]
                : ['nullable'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($ignoreId),
            ],
            'tour_code' => $type === Post::TYPE_PRODUCT
                ? [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('posts', 'tour_code')->ignore($ignoreId)
                ]
                : ['nullable'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'sales_policy' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string'] : ['nullable'],
            'content' => ['nullable', 'string'],
            'address' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string', 'max:255'] : ['nullable'],
            'itinerary' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string', 'max:255'] : ['nullable'],
            'departure_location' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string', 'max:255'] : ['nullable'],
            'destination' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string', 'max:255'] : ['nullable'],
            'departure_date' => $type === Post::TYPE_PRODUCT ? ['nullable', 'array'] : ['nullable'],
            'departure_date.*' => ['string', 'max:255'],
            'departure_prices' => $type === Post::TYPE_PRODUCT ? ['nullable', 'array'] : ['nullable'],
            'departure_prices.*' => ['nullable', 'numeric', 'min:0'],
            'child_price_percent' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0', 'max:100'] : ['nullable'],
            'infant_price_percent' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0', 'max:100'] : ['nullable'],
            'attractions' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string'] : ['nullable'],
            'transport' => $type === Post::TYPE_PRODUCT ? ['nullable', 'array'] : ['nullable'],
            'transport.*' => ['string', 'max:255'],
            'duration' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string', 'max:255'] : ['nullable'],
            'tags' => $type === Post::TYPE_NEWS ? ['nullable', 'array'] : ['nullable'],
            'tags.*' => ['string', 'max:255'],
            'guide_content' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string'] : ['nullable'],
            'visa_content' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string'] : ['nullable'],
            'insurance_content' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string'] : ['nullable'],
            'promotion_content' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string'] : ['nullable'],
            'province_id' => $type === Post::TYPE_PRODUCT
                ? ['nullable', 'integer', Rule::exists('provinces', 'id')]
                : ['nullable'],
            'ward_id' => $type === Post::TYPE_PRODUCT
                ? ['nullable', 'integer', Rule::exists('wards', 'id')]
                : ['nullable'],
            'map_embed' => $type === Post::TYPE_PRODUCT ? ['nullable', 'string'] : ['nullable'],
            'location_image_file' => $type === Post::TYPE_PRODUCT
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048']
                : ['nullable'],
            'remove_location_image' => ['nullable'],
            'area_from' => $type === Post::TYPE_PRODUCT ? ['nullable', 'numeric', 'min:0'] : ['nullable'],
            'area_to' => $type === Post::TYPE_PRODUCT ? ['nullable', 'numeric', 'min:0'] : ['nullable'],
            'floor_count_from' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0'] : ['nullable'],
            'floor_count_to' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0'] : ['nullable'],
            'unit_count_from' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0'] : ['nullable'],
            'unit_count_to' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0'] : ['nullable'],
            'bedroom_count_from' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0'] : ['nullable'],
            'bedroom_count_to' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0'] : ['nullable'],
            'bathroom_count_from' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0'] : ['nullable'],
            'bathroom_count_to' => $type === Post::TYPE_PRODUCT ? ['nullable', 'integer', 'min:0'] : ['nullable'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'gallery_files.*' => $type === Post::TYPE_PRODUCT
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048']
                : ['nullable'],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['integer'],
            'remove_image' => ['nullable'],
            'price' => $type === Post::TYPE_PRODUCT
                ? ['nullable', 'numeric', 'min:0']
                : ['nullable'],
            'price_unit' => $type === Post::TYPE_PRODUCT
                ? ['nullable', Rule::in(['ty', 'trieu'])]
                : ['nullable'],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['nullable'],
            'is_featured' => $type === Post::TYPE_PRODUCT ? ['nullable'] : ['nullable'],
        ], [
            'tour_code.unique' => 'Mã tour này đã tồn tại trong hệ thống. Vui lòng nhập một mã khác để tránh trùng lặp.',
        ]);

        if ($this->containsInlineBase64Image($validated['content'] ?? null)) {
            throw ValidationException::withMessages([
                'content' => 'Nội dung đang chứa ảnh base64. Vui lòng kéo thả hoặc dán ảnh để hệ thống upload thành file trước khi lưu.',
            ]);
        }

        if ($this->containsInlineBase64Image($validated['sales_policy'] ?? null)) {
            throw ValidationException::withMessages([
                'sales_policy' => 'Chính sách bán hàng đang chứa ảnh base64. Vui lòng upload ảnh thành file trước khi lưu.',
            ]);
        }

        if (
            $type === Post::TYPE_PRODUCT
            && ! empty($validated['province_id'])
            && ! empty($validated['ward_id'])
            && ! Ward::query()
                ->whereKey($validated['ward_id'])
                ->where('province_id', $validated['province_id'])
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'ward_id' => 'Phường xã không thuộc tỉnh thành đã chọn.',
            ]);
        }

        $this->validateRangeFields($validated, $type);

        return $validated;
    }

    protected function payload(Request $request, array $validated, string $type, ?Post $post = null): array
    {
        $imagePath = $post->image ?? null;
        $locationImagePath = $post->location_image ?? null;

        if ($request->boolean('remove_image') && $imagePath) {
            $this->deleteImageIfExists($imagePath);
            $imagePath = null;
        }

        if ($request->hasFile('image_file')) {
            if ($imagePath) {
                $this->deleteImageIfExists($imagePath);
            }

            $imagePath = $this->storeImage($request->file('image_file'));
        }

        if ($type === Post::TYPE_PRODUCT && $request->boolean('remove_location_image') && $locationImagePath) {
            $this->deleteImageIfExists($locationImagePath);
            $locationImagePath = null;
        }

        if ($type === Post::TYPE_PRODUCT && $request->hasFile('location_image_file')) {
            if ($locationImagePath) {
                $this->deleteImageIfExists($locationImagePath);
            }

            $locationImagePath = $this->storeImage($request->file('location_image_file'));
        }

        $price = null;

        if ($type === Post::TYPE_PRODUCT && array_key_exists('price', $validated) && $validated['price'] !== null) {
            $multiplier = ($validated['price_unit'] ?? 'ty') === 'trieu' ? 1000000 : 1000000000;
            $price = (float) $validated['price'] * $multiplier;
        }

        $destination = null;

        if ($type === Post::TYPE_PRODUCT) {
            $destination = $this->destinationFromCategoryId($validated['category_id'] ?? null)
                ?? ($post->destination ?? null);
        }

        $slug = $validated['slug'] ?? null;
        if (empty($slug)) {
            $slug = Str::slug($validated['title']);
        }
        if (empty($slug)) {
            do {
                $slug = strtolower(Str::random(10));
            } while (\App\Models\Post::where('slug', $slug)->exists());
        }

        $payload = [
            'type' => $type,
            'category_id' => $validated['category_id'] ?? null,
            'seller_id' => $type === Post::TYPE_PRODUCT ? ($validated['seller_id'] ?? null) : null,
            'title' => $validated['title'],
            'slug' => $slug,
            'tour_code' => $type === Post::TYPE_PRODUCT ? ($validated['tour_code'] ?? null) : null,
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'summary' => $validated['summary'] ?? null,
            'sales_policy' => $type === Post::TYPE_PRODUCT ? ($validated['sales_policy'] ?? null) : null,
            'content' => $validated['content'] ?? null,
            'address' => $type === Post::TYPE_PRODUCT ? ($validated['address'] ?? null) : null,
            'itinerary' => $type === Post::TYPE_PRODUCT ? ($validated['itinerary'] ?? null) : null,
            'departure_location' => $type === Post::TYPE_PRODUCT ? ($validated['departure_location'] ?? null) : null,
            'destination' => $destination,
            'departure_date' => $type === Post::TYPE_PRODUCT ? (isset($validated['departure_date']) && is_array($validated['departure_date']) ? implode(', ', $validated['departure_date']) : null) : null,
            'attractions' => $type === Post::TYPE_PRODUCT ? ($validated['attractions'] ?? null) : null,
            'transport' => $type === Post::TYPE_PRODUCT ? (isset($validated['transport']) && is_array($validated['transport']) ? implode(', ', $validated['transport']) : null) : null,
            'tags' => $type === Post::TYPE_NEWS ? ($validated['tags'] ?? null) : null,
            'child_price_percent' => $type === Post::TYPE_PRODUCT ? ($validated['child_price_percent'] ?? null) : null,
            'infant_price_percent' => $type === Post::TYPE_PRODUCT ? ($validated['infant_price_percent'] ?? null) : null,
            'duration' => $type === Post::TYPE_PRODUCT ? ($validated['duration'] ?? null) : null,
            'guide_content' => $type === Post::TYPE_PRODUCT ? ($validated['guide_content'] ?? null) : null,
            'visa_content' => $type === Post::TYPE_PRODUCT ? ($validated['visa_content'] ?? null) : null,
            'insurance_content' => $type === Post::TYPE_PRODUCT ? ($validated['insurance_content'] ?? null) : null,
            'promotion_content' => $type === Post::TYPE_PRODUCT ? ($validated['promotion_content'] ?? null) : null,
            'province_id' => $type === Post::TYPE_PRODUCT ? ($validated['province_id'] ?? null) : null,
            'ward_id' => $type === Post::TYPE_PRODUCT ? ($validated['ward_id'] ?? null) : null,
            'map_embed' => $type === Post::TYPE_PRODUCT ? ($validated['map_embed'] ?? null) : null,
            'location_image' => $type === Post::TYPE_PRODUCT ? $locationImagePath : null,
            'area' => null,
            'area_from' => $type === Post::TYPE_PRODUCT ? ($validated['area_from'] ?? null) : null,
            'area_to' => $type === Post::TYPE_PRODUCT ? ($validated['area_to'] ?? null) : null,
            'floor_count' => null,
            'floor_count_from' => $type === Post::TYPE_PRODUCT ? ($validated['floor_count_from'] ?? null) : null,
            'floor_count_to' => $type === Post::TYPE_PRODUCT ? ($validated['floor_count_to'] ?? null) : null,
            'unit_count' => null,
            'unit_count_from' => $type === Post::TYPE_PRODUCT ? ($validated['unit_count_from'] ?? null) : null,
            'unit_count_to' => $type === Post::TYPE_PRODUCT ? ($validated['unit_count_to'] ?? null) : null,
            'bedroom_count' => null,
            'bedroom_count_from' => $type === Post::TYPE_PRODUCT ? ($validated['bedroom_count_from'] ?? null) : null,
            'bedroom_count_to' => $type === Post::TYPE_PRODUCT ? ($validated['bedroom_count_to'] ?? null) : null,
            'bathroom_count' => null,
            'bathroom_count_from' => $type === Post::TYPE_PRODUCT ? ($validated['bathroom_count_from'] ?? null) : null,
            'bathroom_count_to' => $type === Post::TYPE_PRODUCT ? ($validated['bathroom_count_to'] ?? null) : null,
            'image' => $imagePath,
            'price' => $type === Post::TYPE_PRODUCT ? $price : null,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $type === Post::TYPE_PRODUCT ? $request->boolean('is_featured') : false,
            'published_at' => $validated['published_at'] ?? null,
        ];

        if ($this->postsHasUserIdColumn()) {
            $payload['user_id'] = $post->user_id ?? auth()->id();
        }

        return $payload;
    }

    protected function postsHasUserIdColumn(): bool
    {
        if ($this->postsHasUserIdColumn === null) {
            $this->postsHasUserIdColumn = Schema::hasColumn('posts', 'user_id');
        }

        return $this->postsHasUserIdColumn;
    }

    protected function categoryOptions(string $type)
    {
        $categories = Category::query()
            ->where('type', $type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return collect($this->flattenCategoryTree(
            $this->buildCategoryTree($categories)
        ));
    }

    protected function buildCategoryTree(Collection $categories, ?int $parentId = null): Collection
    {
        return $categories
            ->where('parent_id', $parentId)
            ->values()
            ->map(function (Category $category) use ($categories) {
                $category->children_tree = $this->buildCategoryTree($categories, $category->id);

                return $category;
            });
    }

    protected function flattenCategoryTree(Collection $categories, string $prefix = ''): array
    {
        $options = [];

        foreach ($categories as $category) {
            $options[$category->id] = $prefix . $category->name;

            if ($category->children_tree->isNotEmpty()) {
                $options += $this->flattenCategoryTree($category->children_tree, $prefix . '-- ');
            }
        }

        return $options;
    }

    protected function destinationFromCategoryId(?int $categoryId): ?string
    {
        if (! $categoryId) {
            return null;
        }

        return Category::query()
            ->whereKey($categoryId)
            ->value('name');
    }

    protected function provinceOptions()
    {
        return Province::query()
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    protected function wardOptionsForProvince(?int $provinceId)
    {
        if (! $provinceId) {
            return collect();
        }

        return Ward::query()
            ->where('province_id', $provinceId)
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    protected function wardMap(): array
    {
        return Ward::query()
            ->orderBy('province_id')
            ->orderBy('name')
            ->get(['id', 'province_id', 'name'])
            ->groupBy('province_id')
            ->map(function ($wards) {
                return $wards->map(function (Ward $ward) {
                    return [
                        'id' => $ward->id,
                        'name' => $ward->name,
                    ];
                })->values()->all();
            })
            ->toArray();
    }

    protected function sellerOptions()
    {
        return User::query()
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    protected function tourOptionGroups(array $selectedValues = []): array
    {
        $groupedOptions = [];

        foreach (TourOption::groups() as $groupKey => $label) {
            $groupedOptions[$groupKey] = [];
        }

        if (! Schema::hasTable('tour_options')) {
            foreach ($selectedValues as $groupKey => $selectedValue) {
                if (! array_key_exists($groupKey, $groupedOptions)) {
                    continue;
                }

                foreach ((array) $selectedValue as $value) {
                    if ($value) {
                        $groupedOptions[$groupKey][$value] = $value;
                    }
                }
            }

            return $groupedOptions;
        }

        $options = TourOption::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        foreach (TourOption::groups() as $groupKey => $label) {
            $groupedOptions[$groupKey] = $options
                ->where('group_key', $groupKey)
                ->where('is_active', true)
                ->pluck('name', 'name')
                ->toArray();

            foreach ((array) ($selectedValues[$groupKey] ?? []) as $selectedValue) {
                if ($selectedValue && ! array_key_exists($selectedValue, $groupedOptions[$groupKey])) {
                    $groupedOptions[$groupKey][$selectedValue] = $selectedValue;
                }
            }
        }

        return $groupedOptions;
    }

    protected function routePrefix(string $type): string
    {
        return $type === Post::TYPE_PRODUCT ? 'backend.products' : 'backend.news';
    }

    protected function syncGalleryImages(Request $request, Post $post, string $type): void
    {
        if ($type !== Post::TYPE_PRODUCT) {
            return;
        }

        $removeIds = collect($request->input('remove_gallery_images', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        if ($removeIds->isNotEmpty()) {
            $imagesToDelete = $post->galleryImages()->whereIn('id', $removeIds)->get();

            foreach ($imagesToDelete as $image) {
                $this->deleteImageIfExists($image->image);
                $image->delete();
            }
        }

        $sortOrder = (int) $post->galleryImages()->max('sort_order');

        $galleryInputs = [
            'gallery_files' => PostImage::TYPE_PERSPECTIVE,
        ];

        foreach ($galleryInputs as $inputName => $imageType) {
            if (! $request->hasFile($inputName)) {
                continue;
            }

            foreach ($request->file($inputName) as $file) {
                if (! $file) {
                    continue;
                }

                $sortOrder++;

                PostImage::create([
                    'post_id' => $post->id,
                    'image' => $this->storeImage($file),
                    'image_type' => $imageType,
                    'sort_order' => $sortOrder,
                ]);
            }
        }
    }

    private function syncDeparturePrices(Request $request, Post $post, string $type)
    {
        if ($type !== Post::TYPE_PRODUCT) {
            return;
        }

        $departurePrices = $request->input('departure_prices', []);
        $dates = $request->input('departure_date', []);

        $post->departurePrices()->delete();

        if (!is_array($dates) || empty($dates)) {
            return;
        }

        foreach ($dates as $date) {
            $price = $departurePrices[$date] ?? null;
            if ($price !== null && $price !== '') {
                $post->departurePrices()->create([
                    'departure_date' => $date,
                    'price' => (float) $price * 1000000,
                ]);
            }
        }
    }

    protected function storeImage($file): string
    {
        return MediaManager::store($file, 'uploads/posts');
    }

    protected function deleteImageIfExists(?string $imagePath): void
    {
        MediaManager::delete($imagePath);
    }

    protected function containsInlineBase64Image(?string $content): bool
    {
        if (! $content) {
            return false;
        }

        return Str::contains($content, 'src="data:image/')
            || Str::contains($content, "src='data:image/")
            || Str::contains($content, 'src=data:image/');
    }

    protected function validateRangeFields(array $validated, string $type): void
    {
        if ($type !== Post::TYPE_PRODUCT) {
            return;
        }

        $ranges = [
            'area' => ['from' => 'area_from', 'to' => 'area_to', 'label' => 'Diện tích'],
            'floor_count' => ['from' => 'floor_count_from', 'to' => 'floor_count_to', 'label' => 'Số tầng'],
            'unit_count' => ['from' => 'unit_count_from', 'to' => 'unit_count_to', 'label' => 'Số căn'],
            'bedroom_count' => ['from' => 'bedroom_count_from', 'to' => 'bedroom_count_to', 'label' => 'Số phòng ngủ'],
            'bathroom_count' => ['from' => 'bathroom_count_from', 'to' => 'bathroom_count_to', 'label' => 'So WC'],
        ];

        $messages = [];

        foreach ($ranges as $range) {
            $from = $validated[$range['from']] ?? null;
            $to = $validated[$range['to']] ?? null;

            if ($from !== null && $to !== null && (float) $to < (float) $from) {
                $messages[$range['to']] = $range['label'] . ' đến phải lớn hơn hoặc bằng giá trị từ.';
            }
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

}
