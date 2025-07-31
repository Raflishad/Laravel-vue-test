<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->decimal('product_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total_price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->json('tags')->nullable();
            $table->dateTime('release_date')->nullable();
            $table->timestamps(); 
            $table->softDeletes();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
