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
        Schema::create('completed_jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('job_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('weed_spray');
            $table->integer('weed_spray_used');
            $table->integer('green_waste');
            $table->integer('bulbs_replaced');
            $table->boolean('lawn_care');
            $table->boolean('trimming');
            $table->boolean('raking_up_leaf_litter');
            $table->boolean('weeding');
            $table->boolean('blowing');
            $table->boolean('clearing_garden_beds');
            $table->boolean('collecting_litter');
            $table->boolean('entrance_glass_cleaning');
            $table->boolean('bringing_in_bins');
            $table->boolean('remove_cob_webs');
            $table->boolean('vacuum');
            $table->boolean('clear_out_bin_corral');
            $table->boolean('clearing_drainage_gate');
            $table->boolean('vacuum_and_mop_laundry');
            $table->boolean('remove_waste_laundry');
            $table->text('notes');
            $table->text('extra_work');
            $table->integer('price');
            $table->integer('weed_spray_charge');
            $table->integer('extra_charge');
            // $table->integer('gst_charge');
            $table->integer('total_price');
            $table->boolean('invoiced');

              $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_jobs');
    }
};
