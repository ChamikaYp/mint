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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('description');
            $table->enum('status', ['Open', 'Pending', 'On hold', 'Solved', 'Closed']);
            $table->foreignId('job_id')->constrained()->onDelete('cascade'); // Links to the jobs table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Ticket owner

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
