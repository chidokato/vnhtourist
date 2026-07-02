<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TourOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class TourOptionController extends Controller
{
    public function index(Request $request)
    {
        $groups = TourOption::groups();

        if (! Schema::hasTable('tour_options')) {
            return view('backend.tour-options.index', [
                'options' => collect(),
                'groups' => $groups,
            ])->with('error', 'Bảng tùy chọn tour chưa sẵn sàng.');
        }

        $query = TourOption::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('group_key', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('group_key')) {
            $query->where('group_key', $request->group_key);
        }

        $options = $query->orderBy('group_key')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('backend.tour-options.index', compact('options', 'groups'));
    }

    public function create()
    {
        $groups = TourOption::groups();

        return view('backend.tour-options.create', compact('groups'));
    }

    public function store(Request $request)
    {
        abort_unless(Schema::hasTable('tour_options'), 404);

        $validated = $this->validateOption($request);

        TourOption::create([
            'group_key' => $validated['group_key'],
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('backend.tour-options.index')
            ->with('success', 'Thêm tùy chọn tour thành công.');
    }

    public function edit(TourOption $tourOption)
    {
        abort_unless(Schema::hasTable('tour_options'), 404);

        $groups = TourOption::groups();

        return view('backend.tour-options.edit', compact('tourOption', 'groups'));
    }

    public function update(Request $request, TourOption $tourOption)
    {
        abort_unless(Schema::hasTable('tour_options'), 404);

        $validated = $this->validateOption($request, $tourOption->id);

        $tourOption->update([
            'group_key' => $validated['group_key'],
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('backend.tour-options.index')
            ->with('success', 'Cập nhật tùy chọn tour thành công.');
    }

    public function destroy(TourOption $tourOption)
    {
        abort_unless(Schema::hasTable('tour_options'), 404);

        $tourOption->delete();

        return redirect()
            ->route('backend.tour-options.index')
            ->with('success', 'Xóa tùy chọn tour thành công.');
    }

    protected function validateOption(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'group_key' => ['required', Rule::in(array_keys(TourOption::groups()))],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tour_options', 'name')
                    ->where(fn ($query) => $query->where('group_key', $request->input('group_key')))
                    ->ignore($ignoreId),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);
    }

    public function quickStore(Request $request)
    {
        abort_unless(Schema::hasTable('tour_options'), 404);

        $request->validate([
            'group_key' => ['required', Rule::in(array_keys(TourOption::groups()))],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tour_options', 'name')
                    ->where(fn ($query) => $query->where('group_key', $request->input('group_key')))
            ],
        ]);

        $option = TourOption::create([
            'group_key' => $request->group_key,
            'name' => $request->name,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm tùy chọn thành công',
            'data' => [
                'value' => $option->name,
                'label' => $option->name,
            ]
        ]);
    }

    public function options(Request $request)
    {
        abort_unless(Schema::hasTable('tour_options'), 404);

        $groupKey = $request->query('group_key');
        
        $query = TourOption::query()->where('is_active', true);
        
        if ($groupKey) {
            $query->where('group_key', $groupKey);
        }
        
        $options = $query->orderBy('sort_order')
            ->orderBy('name')
            ->get(['name as value', 'name as label']);
            
        return response()->json([
            'success' => true,
            'data' => $options
        ]);
    }
}
