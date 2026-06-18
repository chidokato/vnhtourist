<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\MediaManager;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::query()->firstOrCreate([]);

        return view('backend.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::query()->firstOrCreate([]);

        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'hotline' => ['nullable', 'string', 'max:50'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'footer_logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,ico,webp', 'max:1024'],
            'remove_logo' => ['nullable'],
            'remove_footer_logo' => ['nullable'],
            'remove_favicon' => ['nullable'],
            'footer_column_1_title' => ['nullable', 'string', 'max:255'],
            'footer_column_1_content' => ['nullable', 'string'],
            'footer_column_2_title' => ['nullable', 'string', 'max:255'],
            'footer_column_2_content' => ['nullable', 'string'],
            'footer_column_3_title' => ['nullable', 'string', 'max:255'],
            'footer_column_3_content' => ['nullable', 'string'],
            'footer_column_4_title' => ['nullable', 'string', 'max:255'],
            'footer_column_4_content' => ['nullable', 'string'],
        ]);

        $social = $this->buildSocial($validated);

        $setting->update([
            'company_name' => $validated['company_name'] ?? null,
            'address' => $validated['address'] ?? null,
            'email' => $validated['email'] ?? null,
            'hotline' => $validated['hotline'] ?? null,
            'social' => $social,
            'logo' => $this->syncImage($request, $setting->logo, 'logo_file', 'remove_logo'),
            'footer_logo' => $this->syncImage($request, $setting->footer_logo, 'footer_logo_file', 'remove_footer_logo'),
            'favicon' => $this->syncImage($request, $setting->favicon, 'favicon_file', 'remove_favicon'),
            'footer_column_1_title' => $validated['footer_column_1_title'] ?? null,
            'footer_column_1_content' => $validated['footer_column_1_content'] ?? null,
            'footer_column_2_title' => $validated['footer_column_2_title'] ?? null,
            'footer_column_2_content' => $validated['footer_column_2_content'] ?? null,
            'footer_column_3_title' => $validated['footer_column_3_title'] ?? null,
            'footer_column_3_content' => $validated['footer_column_3_content'] ?? null,
            'footer_column_4_title' => $validated['footer_column_4_title'] ?? null,
            'footer_column_4_content' => $validated['footer_column_4_content'] ?? null,
        ]);

        return redirect()
            ->route('backend.settings.edit')
            ->with('success', 'Cập nhật cài đặt thành công.');
    }

    protected function buildSocial(array $validated): array
    {
        $items = [];

        foreach (['facebook' => 'Facebook', 'youtube' => 'Youtube'] as $key => $label) {
            if (! empty($validated[$key])) {
                $items[] = [
                    'label' => $label,
                    'url' => $validated[$key],
                ];
            }
        }

        return $items;
    }

    protected function syncImage(Request $request, ?string $currentPath, string $field, string $removeField): ?string
    {
        $path = $currentPath;

        if ($request->boolean($removeField) && $path) {
            $this->deleteImageIfExists($path);
            $path = null;
        }

        if ($request->hasFile($field)) {
            if ($path) {
                $this->deleteImageIfExists($path);
            }

            $path = $this->storeImage($request->file($field));
        }

        return $path;
    }

    protected function storeImage($file): string
    {
        return MediaManager::store($file, 'uploads/settings');
    }

    protected function deleteImageIfExists(?string $imagePath): void
    {
        MediaManager::delete($imagePath);
    }
}
