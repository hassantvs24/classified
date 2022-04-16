<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {//This is the main ads as products
            $table->id();
            $table->string('name');
            $table->enum('state',['Weapon','Accessories','Other'])->default('Weapon');
            $table->text('descriptions', 500)->nullable();
            $table->boolean('is_disable')->default(0);
            $table->string('photo')->nullable();
            $table->double('price')->default(0)->comment('Default Price not mandatory');
            $table->foreignId('product_categories_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
            $table->foreignId('product_types_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
            $table->foreignId('product_brands_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['name', 'product_categories_id', 'product_brands_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
