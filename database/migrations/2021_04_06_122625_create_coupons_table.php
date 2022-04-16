<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->double('amount')->default(0);
            $table->boolean('is_percent')->default(0)->comment('0 mean not %');
            $table->dateTime('expire')->nullable()->comment('If null it is not expire');
            $table->enum('status', ['Active', 'Used', 'Inactive'])->default('Active');
            $table->foreignId('ads_packages_id')->nullable()->comment('If on null it is apply for specific Package')->constrained()->onDelete('Set Null')->onUpdate('No Action');
            $table->foreignId('users_id')->nullable()->comment('If on null it is apply for specific User')->constrained()->onDelete('Set Null')->onUpdate('No Action');
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
        Schema::dropIfExists('coupons');
    }
}
