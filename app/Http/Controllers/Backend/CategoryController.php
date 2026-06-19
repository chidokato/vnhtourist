<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $groupedTrees = collect(Category::types())->mapWithKeys(function ($label, $type) use ($categories) {
            return [$type => $this->buildTree($categories->where('type', $type))];
        });

        $types = Category::types();

        return view('backend.categories.index', compact('groupedTrees', 'types'));
    }

    public function create()
    {
        $parentOptions = $this->flattenCategoriesByType();
        $types = Category::types();

        return view('backend.categories.create', compact('parentOptions', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);

        if ($validated['parent_id']) {
            $parentCategory = Category::find($validated['parent_id']);

            if ($parentCategory && $parentCategory->type !== $validated['type']) {
                return back()
                    ->withErrors(['parent_id' => 'Danh mục cha phải cùng loại với danh mục hiện tại.'])
                    ->withInput();
            }
        }

        Category::create([
            'type' => $validated['type'],
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('backend.categories.index')
            ->with('success', 'Thêm danh mục thành công.');
    }

    public function edit(Category $category)
    {
        $parentOptions = $this->flattenCategoriesByType($category->id);
        $types = Category::types();

        return view('backend.categories.edit', compact('category', 'parentOptions', 'types'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validateCategory($request, $category->id);

        if ($validated['parent_id'] && $this->isDescendant($category, (int) $validated['parent_id'])) {
            return back()
                ->withErrors(['parent_id' => 'Không thể chọn danh mục con làm danh mục cha.'])
                ->withInput();
        }

        if ($validated['parent_id']) {
            $parentCategory = Category::find($validated['parent_id']);

            if ($parentCategory && $parentCategory->type !== $validated['type']) {
                return back()
                    ->withErrors(['parent_id' => 'Danh mục cha phải cùng loại với danh mục hiện tại.'])
                    ->withInput();
            }
        }

        $category->update([
            'type' => $validated['type'],
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('backend.categories.index')
            ->with('success', 'Cập nhật danh mục thành công.');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->exists()) {
            return redirect()
                ->route('backend.categories.index')
                ->with('error', 'Không thể xóa danh mục đang có danh mục con.');
        }

        $category->delete();

        return redirect()
            ->route('backend.categories.index')
            ->with('success', 'Xóa danh mục thành công.');
    }

    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái danh mục thành công.',
            'is_active' => $category->is_active,
            'label' => $category->is_active ? 'Hiển thị' : 'Ẩn',
        ]);
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(array_keys(Category::types()))],
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        $category = Category::create([
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm danh mục thành công',
            'data' => [
                'value' => $category->id,
                'label' => $category->name,
            ]
        ]);
    }

    public function options(Request $request)
    {
        $type = $request->query('type');
        
        $categories = Category::query()
            ->where('is_active', true);
            
        if ($type) {
            $categories->where('type', $type);
        }
            
        $categories = $categories->orderBy('sort_order')
            ->orderBy('name')
            ->get();
            
        $tree = $this->buildTree($categories);
        $options = [];

        $walk = function (Collection $nodes, string $prefix = '') use (&$walk, &$options): void {
            foreach ($nodes as $node) {
                $options[] = [
                    'value' => $node->id,
                    'label' => $prefix . $node->name,
                ];
                $walk($node->children_tree, $prefix . '-- ');
            }
        };

        $walk($tree);
            
        return response()->json([
            'success' => true,
            'data' => $options
        ]);
    }

    protected function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(array_keys(Category::types()))],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($ignoreId),
            ],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);
    }

    protected function buildTree(Collection $categories, ?int $parentId = null): Collection
    {
        return $categories
            ->where('parent_id', $parentId)
            ->values()
            ->map(function (Category $category) use ($categories) {
                $category->children_tree = $this->buildTree($categories, $category->id);

                return $category;
            });
    }

    protected function flattenCategoriesByType(?int $excludeId = null): array
    {
        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $excludedIds = collect();

        if ($excludeId !== null) {
            $excludedIds = collect($this->collectDescendantIds($categories, $excludeId));
        }

        return collect(Category::types())->mapWithKeys(function ($label, $type) use ($categories, $excludedIds) {
            $tree = $this->buildTree($categories->where('type', $type));
            $options = [];

            $walk = function (Collection $nodes, string $prefix = '') use (&$walk, &$options, $excludedIds): void {
                foreach ($nodes as $node) {
                    if ($excludedIds->contains($node->id)) {
                        continue;
                    }

                    $options[$node->id] = $prefix . $node->name;
                    $walk($node->children_tree, $prefix . '-- ');
                }
            };

            $walk($tree);

            return [$type => $options];
        })->toArray();
    }

    protected function collectDescendantIds(Collection $categories, int $parentId): array
    {
        $ids = [$parentId];
        $children = $categories->where('parent_id', $parentId);

        foreach ($children as $child) {
            $ids = array_merge($ids, $this->collectDescendantIds($categories, $child->id));
        }

        return array_values(array_unique($ids));
    }

    protected function isDescendant(Category $category, int $parentId): bool
    {
        $currentParent = Category::find($parentId);

        while ($currentParent) {
            if ($currentParent->id === $category->id) {
                return true;
            }

            $currentParent = $currentParent->parent;
        }

        return false;
    }
}
