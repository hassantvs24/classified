<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('types',['Premium', 'Free'])->default('Premium');
            $table->integer('quantity')->default(0)->comment('ads amount');
            $table->double('amount')->default(0);
            $table->double('discount')->default(0);
            $table->enum('status',['Active', 'Inactive', 'Cancel', 'Expire'])->default('Active');
            $table->dateTime('expire')->nullable()->comment('Package expire date. based on package day count. calculate when purchase');
            $table->boolean('is_percent')->default(0)->comment('0 mean not % in discount');
            $table->foreignId('coupons_id')->nullable()->constrained()->onDelete('Set Null')->onUpdate('No Action');
            $table->foreignId('ads_packages_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
            $table->foreignId('companies_id')->nullable()->constrained()->onDelete('Set Null')->onUpdate('No Action');
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
        Schema::dropIfExists('purchase_packages');
    }
}
