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
            $table->timestamp('created_at');
            $table->timestamp('inactive_at')->nullable();
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
