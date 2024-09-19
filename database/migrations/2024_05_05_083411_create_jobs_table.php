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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            // $table->bigInteger('body_corporate_id');
            $table->string('body_corporate');
            $table->string('plan');
            $table->string('location');
            $table->string('location_link');
            $table->integer('frequency');
            $table->longText('scope');
            $table->decimal('base_price', total: 8, places: 2);
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
