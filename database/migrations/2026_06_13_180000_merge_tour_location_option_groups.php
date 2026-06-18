<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tour_options')) {
            return;
        }

        $existingLocations = DB::table('tour_options')
            ->where('group_key', 'location')
            ->pluck('id', 'name');

        DB::table('tour_options')
            ->where('group_key', 'departure_location')
            ->orderBy('id')
            ->get()
            ->each(function ($option) use (&$existingLocations) {
                if (isset($existingLocations[$option->name])) {
                    DB::table('tour_options')->where('id', $option->id)->delete();

                    return;
                }

                DB::table('tour_options')
                    ->where('id', $option->id)
                    ->update(['group_key' => 'location']);

                $existingLocations[$option->name] = $option->id;
            });

        DB::table('tour_options')
            ->where('group_key', 'destination')
            ->orderBy('id')
            ->get()
            ->each(function ($option) use (&$existingLocations) {
                if (isset($existingLocations[$option->name])) {
                    DB::table('tour_options')->where('id', $option->id)->delete();

                    return;
                }

                DB::table('tour_options')
                    ->where('id', $option->id)
                    ->update(['group_key' => 'location']);

                $existingLocations[$option->name] = $option->id;
            });
    }

    public function down(): void
    {
        if (! Schema::hasTable('tour_options')) {
            return;
        }

        DB::table('tour_options')
            ->where('group_key', 'location')
            ->update(['group_key' => 'departure_location']);
    }
};
