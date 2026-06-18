<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaManager
{
    public static function store(UploadedFile $file, string $directory, ?string $prefix = null): string
    {
        $normalizedDirectory = self::normalizeDirectory($directory);
        $extension = strtolower($file->getClientOriginalExtension());
        $filenamePrefix = $prefix ? trim($prefix, '-') . '-' : '';
        $filename = $filenamePrefix . now()->format('YmdHis') . '-' . Str::random(10) . '.' . $extension;
        $storedPath = trim($normalizedDirectory, '/') . '/' . $filename;
        $targetDirectory = public_path('uploads/' . trim($normalizedDirectory, '/'));

        if (! File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        $file->move($targetDirectory, $filename);

        return $storedPath;
    }

    public static function delete(?string $path): void
    {
        $normalizedPath = self::normalizePath($path);

        if (! $normalizedPath) {
            return;
        }

        $publicPath = public_path('uploads/' . ltrim($normalizedPath, '/'));

        if (File::exists($publicPath)) {
            File::delete($publicPath);
        }

        $legacyStoragePath = storage_path('app/public/' . ltrim(Str::after($normalizedPath, 'storage/'), '/'));

        if (File::exists($legacyStoragePath)) {
            File::delete($legacyStoragePath);
        }
    }

    public static function normalizePath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $trimmedPath = trim($path);

        if ($trimmedPath === '') {
            return null;
        }

        if (Str::startsWith($trimmedPath, ['http://', 'https://'])) {
            $parsedPath = parse_url($trimmedPath, PHP_URL_PATH);
            $trimmedPath = is_string($parsedPath) ? $parsedPath : '';
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $trimmedPath), '/');

        if (Str::contains($normalizedPath, '/storage/')) {
            $normalizedPath = ltrim(Str::afterLast($normalizedPath, '/storage/'), '/');
        }

        if (Str::startsWith($normalizedPath, 'storage/')) {
            $normalizedPath = ltrim(Str::after($normalizedPath, 'storage/'), '/');
        }

        if (Str::contains($normalizedPath, '/uploads/')) {
            $normalizedPath = ltrim(Str::afterLast($normalizedPath, '/uploads/'), '/');
        }

        if (Str::startsWith($normalizedPath, 'uploads/')) {
            $normalizedPath = ltrim(Str::after($normalizedPath, 'uploads/'), '/');
        }

        return $normalizedPath;
    }

    public static function publicUrl(?string $path): ?string
    {
        $normalizedPath = self::normalizePath($path);

        if (! $normalizedPath) {
            return null;
        }

        return self::toAbsoluteUrl('uploads/' . self::encodePath($normalizedPath));
    }

    protected static function toAbsoluteUrl(string $path): string
    {
        if (app()->bound('request')) {
            $request = request();
            $root = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/');

            return $root . '/' . ltrim($path, '/');
        }

        return asset($path);
    }

    public static function diskPath(?string $path): ?string
    {
        $normalizedPath = self::normalizePath($path);

        if (! $normalizedPath) {
            return null;
        }

        $candidates = [public_path('uploads/' . ltrim($normalizedPath, '/'))];

        foreach ($candidates as $candidate) {
            if (File::exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    protected static function encodePath(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', ltrim($path, '/'))));
    }

    protected static function normalizeDirectory(string $directory): string
    {
        $normalizedDirectory = trim(str_replace('\\', '/', $directory), '/');

        if (Str::startsWith($normalizedDirectory, 'uploads/')) {
            return ltrim(Str::after($normalizedDirectory, 'uploads/'), '/');
        }

        return $normalizedDirectory;
    }
}
