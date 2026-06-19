<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('seller_id')
                ->constrained('users')
                ->nullOnDelete();
        });

        $firstUserId = DB::table('users')->orderBy('id')->value('id');

        if ($firstUserId) {
            DB::table('posts')
                ->whereNull('user_id')
                ->update(['user_id' => $firstUserId]);
        }
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
