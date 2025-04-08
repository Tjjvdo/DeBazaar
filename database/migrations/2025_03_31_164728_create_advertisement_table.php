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
            $table->mediumText('information');
            $table->foreignId('advertiser_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_rentable')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('inactive_at')->nullable();
        });

        Schema::create('bid', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained('advertisement')->onDelete('cascade');
            $table->integer('bidder_id')->nullable();
            $table->integer('bid_amount');

            $table->foreign('bidder_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('renting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained('advertisement')->onDelete('cascade');
            $table->foreignId('renter_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('return_date')->nullable();
            $table->string('image_path')->nullable();
        });

        Schema::create('advertisement_related', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained('advertisement')->onDelete('cascade');
            $table->foreignId('related_advertisement_id')->constrained('advertisement')->onDelete('cascade');
        });

        Schema::create('favorite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained('advertisement')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });

        Schema::create('product_review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('advertisement_id')->constrained('advertisement')->onDelete('cascade');
            $table->mediumText('review');
        });

        Schema::create('advertiser_review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('advertiser_id')->constrained('users')->onDelete('cascade');
            $table->mediumText('review');
        });

        Schema::create('wear_setting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained('advertisement')->onDelete('cascade');
            $table->integer('investment_amount');
            $table->integer('days_durable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisement');
        Schema::dropIfExists('bid');
        Schema::dropIfExists('renting');
        Schema::dropIfExists('advertisement_related');
        Schema::dropIfExists('favorite');
        Schema::dropIfExists('product_review');
        Schema::dropIfExists('advertiser_review');
        Schema::dropIfExists('wear_setting');
    }
};
