<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('identifier');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['product_id', 'identifier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_likes');
    }
};