<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('itinerary')->nullable()->after('address');
            $table->text('attractions')->nullable()->after('itinerary');
            $table->string('transport')->nullable()->after('attractions');
            $table->string('duration')->nullable()->after('transport');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'itinerary',
                'attractions',
                'transport',
                'duration',
            ]);
        });
    }
};
