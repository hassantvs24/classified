<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('types',['Premium', 'Free'])->default('Premium');
            $table->integer('quantity')->default(0)->comment('0 means Unlimited');
            $table->integer('expire_day')->default(0)->comment('0 means Unlimited');
            $table->double('price')->default(0)->comment('0 means Free');
            $table->string('banner')->nullable();
            $table->string('description')->nullable();
            $table->boolean('status')->default(1)->comment('0 mean inactive');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['name', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_packages');
    }
}
