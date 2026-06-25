<?php

namespace App\Http\Controllers\Backend;

use App\Models\Slider;
use App\Support\MediaManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('backend.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('backend.sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('image_file')) {
            $validated['image'] = MediaManager::store($request->file('image_file'), 'sliders');
        }

        Slider::create($validated);

        return redirect()->route('backend.sliders.index')
            ->with('success', 'Thêm Slider thành công.');
    }

    public function edit(Slider $slider)
    {
        return view('backend.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('image_file')) {
            if ($slider->image) {
                MediaManager::delete($slider->image);
            }
            $validated['image'] = MediaManager::store($request->file('image_file'), 'sliders');
        }

        $slider->update($validated);

        return redirect()->route('backend.sliders.index')
            ->with('success', 'Cập nhật Slider thành công.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            MediaManager::delete($slider->image);
        }
        $slider->delete();

        return redirect()->route('backend.sliders.index')
            ->with('success', 'Xóa Slider thành công.');
    }

    public function toggleStatus(Slider $slider)
    {
        $slider->update(['is_active' => ! $slider->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'is_active' => $slider->is_active,
        ]);
    }
}
