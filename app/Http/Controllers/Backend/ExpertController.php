<?php

namespace App\Http\Controllers\Backend;

use App\Models\Expert;
use App\Support\MediaManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExpertController extends Controller
{
    public function index()
    {
        $experts = Expert::query()
            ->orderByDesc('id')
            ->paginate(20);

        return view('backend.experts.index', compact('experts'));
    }

    public function create()
    {
        return view('backend.experts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'facebook_url' => 'nullable|string|max:255',
            'twitter_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('image_file')) {
            $validated['image'] = MediaManager::store($request->file('image_file'), 'experts');
        }

        Expert::create($validated);

        return redirect()->route('backend.experts.index')
            ->with('success', 'Thêm chuyên gia thành công.');
    }

    public function edit(Expert $expert)
    {
        return view('backend.experts.edit', compact('expert'));
    }

    public function update(Request $request, Expert $expert)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_image' => 'nullable|boolean',
            'facebook_url' => 'nullable|string|max:255',
            'twitter_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        if ($request->boolean('remove_image') && $expert->image) {
            MediaManager::delete($expert->image);
            $validated['image'] = null;
        }

        if ($request->hasFile('image_file')) {
            if ($expert->image) {
                MediaManager::delete($expert->image);
            }
            $validated['image'] = MediaManager::store($request->file('image_file'), 'experts');
        }

        $expert->update($validated);

        return redirect()->route('backend.experts.index')
            ->with('success', 'Cập nhật chuyên gia thành công.');
    }

    public function destroy(Expert $expert)
    {
        if ($expert->image) {
            MediaManager::delete($expert->image);
        }
        $expert->delete();

        return redirect()->route('backend.experts.index')
            ->with('success', 'Xóa chuyên gia thành công.');
    }

    public function toggleStatus(Expert $expert)
    {
        $expert->update(['is_active' => ! $expert->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'is_active' => $expert->is_active,
        ]);
    }
}
