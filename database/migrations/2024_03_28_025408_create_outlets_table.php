<?php

use App\Enums\Table;
use App\Models\Brands\Brand;
use Illuminate\Database\Migrations\Migration;
use Winata\PackageBased\Database\Blueprints\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Table::OUTLETS->value, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Brand::class, 'brand_id')
                ->nullable()
                ->constrained(Table::BRANDS->value)
                ->onDelete('restrict');
            $table->string('name');
            $table->text('address');
            $table->decimal('longitude', 10, 6);
            $table->decimal('latitude', 10, 6);
            $table->timestamps(precision: 6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Table::OUTLETS->value);
    }
};
