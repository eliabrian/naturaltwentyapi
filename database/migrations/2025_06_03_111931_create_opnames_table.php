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
        Schema::create('opnames', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->dateTime('opname_date');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['draft', 'awaiting_review', 'under_review', 'approved'])->default('draft');
            $table->enum('shift', ['Shift 1', 'Shift 2']);
            $table->timestamps();
        });

        Schema::create('opname_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opname_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('system_quantity');
            $table->unsignedInteger('counted_quantity');
            $table->integer('difference');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opnames');
    }
};
