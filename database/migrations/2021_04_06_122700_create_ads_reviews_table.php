<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('comment')->nullable();
            $table->float('rating')->default(0);
            $table->foreignId('ads_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
            $table->foreignId('users_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_reviews');
    }
}
