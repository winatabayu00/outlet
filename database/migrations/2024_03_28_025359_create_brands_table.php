<?php

use App\Enums\Table;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Winata\PackageBased\Database\Blueprints\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Table::BRANDS->value, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->timestamps(precision: 6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Table::BRANDS->value);
    }
};
