<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->nullable()->constrained('posts')->onDelete('set null'); // Tour ID
            $table->string('tour_name')->nullable(); // Snapshot of tour name
            $table->integer('adult_quantity')->default(1);
            $table->integer('child_quantity')->default(0);
            $table->integer('infant_quantity')->default(0);
            $table->decimal('price', 15, 2)->default(0); // Base price or total for this item
            $table->decimal('total', 15, 2)->default(0); // Total price for this item
            $table->date('departure_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
