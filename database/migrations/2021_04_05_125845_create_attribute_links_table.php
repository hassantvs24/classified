<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_links', function (Blueprint $table) { //Link with product categories
            $table->id();
            $table->foreignId('product_categories_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
            $table->foreignId('attributes_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
            $table->timestamps();

            $table->unique(['product_categories_id', 'attributes_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_links');
    }
}
