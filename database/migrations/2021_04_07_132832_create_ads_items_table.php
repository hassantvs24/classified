<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_items', function (Blueprint $table) {
            $table->id();
            $table->double('quantity')->default(0);
            $table->text('descriptions')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('products_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
            $table->foreignId('ads_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
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
        Schema::dropIfExists('ads_items');
    }
}
