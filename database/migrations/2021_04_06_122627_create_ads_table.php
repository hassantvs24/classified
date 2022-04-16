<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('state',['Weapon','Accessories','Other'])->default('Weapon');
            $table->string('seller')->nullable();
            $table->string('email')->nullable();
            $table->string('phone',15)->nullable();
            $table->string('contact_time')->nullable();
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->string('product_types')->nullable();
            $table->string('photo')->nullable()->comment('Primary Photo');
            $table->double('price')->default(0);
            $table->text('descriptions', 500)->nullable();
            $table->boolean('is_used')->default(0)->comment('0 mean new');
            $table->boolean('is_shipping')->default(0)->comment('0 mean shipping not available');
            $table->enum('status', ['Published', 'Draft', 'Pending', 'Canceled', 'Expired'])->default('Published');
            $table->date('expire')->comment('Auto Set on after 60 days from created date by default');
            $table->foreignId('areas_id')->nullable()->constrained()->onDelete('Set Null')->onUpdate('No Action');
            $table->foreignId('product_brands_id')->nullable()->constrained()->onDelete('Set Null')->onUpdate('No Action');
            $table->foreignId('product_categories_id')->nullable()->constrained()->onDelete('Set Null')->onUpdate('No Action');
            $table->foreignId('product_types_id')->nullable()->constrained()->onDelete('Set Null')->onUpdate('No Action');
            $table->foreignId('companies_id')->nullable()->constrained()->onDelete('Set Null')->onUpdate('No Action');
            $table->foreignId('purchase_packages_id')->nullable()->constrained()->onDelete('Set Null')->onUpdate('No Action');
            $table->foreignId('products_id')->nullable()->constrained()->onDelete('Set Null')->onUpdate('No Action');
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
        Schema::dropIfExists('ads');
    }
}
