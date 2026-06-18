<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $menuTree = $this->buildTree($menus);

        return view('backend.menus.index', compact('menuTree'));
    }

    public function create()
    {
        $parentOptions = $this->flattenMenus();
        $categoryOptions = $this->flattenCategoriesByType();

        return view('backend.menus.create', compact('parentOptions', 'categoryOptions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateMenu($request);

        Menu::create([
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $this->resolveSlug($validated),
            'target' => $validated['target'] ?? '_self',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('backend.menus.index')
            ->with('success', 'Thêm menu thành công.');
    }

    public function edit(Menu $menu)
    {
        $parentOptions = $this->flattenMenus($menu->id);
        $categoryOptions = $this->flattenCategoriesByType();

        return view('backend.menus.edit', compact('menu', 'parentOptions', 'categoryOptions'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $this->validateMenu($request, $menu->id);

        if ($validated['parent_id'] && $this->isDescendant($menu, (int) $validated['parent_id'])) {
            return back()
                ->withErrors(['parent_id' => 'Không thể chọn menu con làm menu cha.'])
                ->withInput();
        }

        $menu->update([
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $this->resolveSlug($validated, $menu->id),
            'target' => $validated['target'] ?? '_self',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('backend.menus.index')
            ->with('success', 'Cập nhật menu thành công.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->children()->exists()) {
            return redirect()
                ->route('backend.menus.index')
                ->with('error', 'Không thể xóa menu đang có menu con.');
        }

        $menu->delete();

        return redirect()
            ->route('backend.menus.index')
            ->with('success', 'Xóa menu thành công.');
    }

    public function toggleStatus(Menu $menu)
    {
        $menu->update([
            'is_active' => ! $menu->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái menu thành công.',
            'is_active' => $menu->is_active,
            'label' => $menu->is_active ? 'Hiển thị' : 'Ẩn',
        ]);
    }

    public function updateSortOrder(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $menu->update([
            'sort_order' => $validated['sort_order'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thứ tự menu thành công.',
            'sort_order' => $menu->sort_order,
        ]);
    }

    protected function validateMenu(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'parent_id' => ['nullable', 'integer', 'exists:menus,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('menus', 'slug')->ignore($ignoreId),
            ],
            'target' => ['nullable', Rule::in(['_self', '_blank'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);
    }

    protected function buildTree(Collection $menus, ?int $parentId = null): Collection
    {
        return $menus
            ->where('parent_id', $parentId)
            ->values()
            ->map(function (Menu $menu) use ($menus) {
                $menu->children_tree = $this->buildTree($menus, $menu->id);

                return $menu;
            });
    }

    protected function flattenMenus(?int $excludeId = null): array
    {
        $menus = Menu::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $tree = $this->buildTree($menus);
        $options = [];

        $walk = function (Collection $nodes, string $prefix = '') use (&$walk, &$options, $excludeId): void {
            foreach ($nodes as $node) {
                if ($excludeId !== null && $node->id === $excludeId) {
                    continue;
                }

                $options[$node->id] = $prefix . $node->name;
                $walk($node->children_tree, $prefix . '-- ');
            }
        };

        $walk($tree);

        return $options;
    }

    protected function flattenCategoriesByType(): array
    {
        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return collect(Category::types())->mapWithKeys(function ($label, $type) use ($categories) {
            $tree = $this->buildCategoryTree($categories->where('type', $type));
            $options = [];

            $walk = function (Collection $nodes, string $prefix = '') use (&$walk, &$options): void {
                foreach ($nodes as $node) {
                    $options[] = [
                        'id' => $node->id,
                        'name' => $prefix . $node->name,
                        'plain_name' => $node->name,
                    ];

                    $walk($node->children_tree, $prefix . '-- ');
                }
            };

            $walk($tree);

            return [$label => $options];
        })->toArray();
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

    protected function isDescendant(Menu $menu, int $parentId): bool
    {
        $currentParent = Menu::find($parentId);

        while ($currentParent) {
            if ($currentParent->id === $menu->id) {
                return true;
            }

            $currentParent = $currentParent->parent;
        }

        return false;
    }

    protected function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug !== '' ? $baseSlug : 'menu';
        $counter = 1;

        while (
            Menu::query()
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function resolveSlug(array $validated, ?int $ignoreId = null): string
    {
        $providedSlug = trim((string) ($validated['slug'] ?? ''));

        if ($providedSlug !== '') {
            if ($providedSlug === '/') {
                return '/';
            }

            $normalizedSlug = Str::slug($providedSlug);

            return $normalizedSlug !== '' ? $normalizedSlug : $this->generateUniqueSlug($validated['name'], $ignoreId);
        }

        return $this->generateUniqueSlug($validated['name'], $ignoreId);
    }
}
