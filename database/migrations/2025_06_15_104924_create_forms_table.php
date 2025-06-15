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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('form_date');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['draft', 'awaiting_review', 'approved', 'done']);
            $table->enum('shift', ['Shift 1', 'Shift 2']);
            $table->timestamps();
        });

        Schema::create('form_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('requested_quantity');
            $table->string('note')->nullable();
            $table->boolean('is_available')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_product');
        Schema::dropIfExists('forms');
    }
};
