<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MigrateUploadsToStorage extends Command
{
    protected $signature = 'uploads:migrate-to-storage {--keep-source : Keep original files in public/uploads after copying}';

    protected $description = 'Move uploaded files back into public/uploads and update database paths.';

    public function handle(): int
    {
        $mappings = [
            ['table' => 'users', 'column' => 'avatar'],
            ['table' => 'settings', 'column' => 'logo'],
            ['table' => 'settings', 'column' => 'footer_logo'],
            ['table' => 'settings', 'column' => 'favicon'],
            ['table' => 'posts', 'column' => 'image'],
            ['table' => 'posts', 'column' => 'location_image'],
            ['table' => 'post_images', 'column' => 'image'],
            ['table' => 'apartment_images', 'column' => 'image'],
        ];

        $migratedCount = 0;
        $skippedCount = 0;
        $missingCount = 0;

        foreach ($mappings as $mapping) {
            $records = DB::table($mapping['table'])
                ->where($mapping['column'], 'like', 'uploads/%')
                ->get(['id', $mapping['column']]);

            foreach ($records as $record) {
                $legacyPath = \App\Support\MediaManager::normalizePath((string) $record->{$mapping['column']});

                if ($legacyPath === '') {
                    $skippedCount++;
                    continue;
                }

                $sourcePath = public_path($legacyPath);

                if (! File::exists($sourcePath)) {
                    $legacyStoragePath = storage_path('app/public/' . ltrim(str_replace('\\', '/', $legacyPath), '/'));

                    if (File::exists($legacyStoragePath)) {
                        $sourcePath = $legacyStoragePath;
                    }
                }

                if (! File::exists($sourcePath)) {
                    $this->warn("Missing file: {$mapping['table']}.{$mapping['column']} #{$record->id} -> {$legacyPath}");
                    $missingCount++;
                    continue;
                }

                $targetPath = public_path('uploads/' . ltrim($legacyPath, '/'));
                $targetDirectory = dirname($targetPath);

                if (! File::isDirectory($targetDirectory)) {
                    File::makeDirectory($targetDirectory, 0755, true);
                }

                if (! File::exists($targetPath)) {
                    File::copy($sourcePath, $targetPath);
                }

                DB::table($mapping['table'])
                    ->where('id', $record->id)
                    ->update([$mapping['column'] => $legacyPath]);

                if (! $this->option('keep-source')) {
                    File::delete($sourcePath);
                }

                $this->line("Migrated {$mapping['table']}.{$mapping['column']} #{$record->id}");
                $migratedCount++;
            }
        }

        if (! $this->option('keep-source')) {
            $this->cleanupEmptyLegacyDirectories();
        }

        $this->newLine();
        $this->info("Done. Migrated: {$migratedCount}, skipped: {$skippedCount}, missing: {$missingCount}");
        $this->line('Uploads now point back to public/uploads.');

        return self::SUCCESS;
    }

    protected function cleanupEmptyLegacyDirectories(): void
    {
        $legacyRoot = public_path('uploads');

        if (! File::isDirectory($legacyRoot)) {
            return;
        }

        collect(File::directories($legacyRoot))
            ->sortDesc()
            ->each(function (string $directory): void {
                if (count(File::allFiles($directory)) === 0 && count(File::directories($directory)) === 0) {
                    File::deleteDirectory($directory);
                }
            });

        if (count(File::allFiles($legacyRoot)) === 0 && count(File::directories($legacyRoot)) === 0) {
            File::deleteDirectory($legacyRoot);
        }
    }
}
