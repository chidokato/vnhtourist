<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->longText('guide_content')->nullable()->after('duration');
            $table->longText('visa_content')->nullable()->after('guide_content');
            $table->longText('insurance_content')->nullable()->after('visa_content');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'guide_content',
                'visa_content',
                'insurance_content',
            ]);
        });
    }
};
