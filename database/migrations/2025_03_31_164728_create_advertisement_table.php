<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('advertisement', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->double('price');
            $table->string('information');
            $table->integer('advertiser_id');
            $table->boolean('is_rentable')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('inactive_at')->nullable();

            $table->foreign('advertiser_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('bid', function (Blueprint $table) {
            $table->id();
            $table->integer('advertisement_id');
            $table->integer('bidder_id')->nullable();
            $table->integer('bid_amount');

            $table->foreign('advertisement_id')->references('id')->on('advertisement')->onDelete('cascade');
            $table->foreign('bidder_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('renting', function (Blueprint $table) {
            $table->id();
            $table->integer('advertisement_id');
            $table->integer('bidder_id');
            $table->date('start_date');
            $table->date('end_date');
            
            $table->foreign('advertisement_id')->references('id')->on('advertisement')->onDelete('cascade');
            $table->foreign('bidder_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('advertisement_related', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advertisement_id');
            $table->unsignedBigInteger('related_advertisement_id');

            $table->foreign('advertisement_id')->references('id')->on('advertisement')->onDelete('cascade');
            $table->foreign('related_advertisement_id')->references('id')->on('advertisement')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisement');
    }
};
