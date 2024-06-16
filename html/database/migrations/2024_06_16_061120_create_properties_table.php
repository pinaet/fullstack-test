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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->boolean('for_sale');
            $table->boolean('for_rent');
            $table->boolean('sold');
            $table->decimal('price', 10, 2);
            $table->string('currency');
            $table->string('currency_symbol');
            $table->string('property_type');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->integer('area');
            $table->string('area_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
