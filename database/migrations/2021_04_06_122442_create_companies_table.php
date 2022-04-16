<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact', 15)->nullable();
            $table->string('contact_person')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('description')->nullable();
            $table->boolean('status')->default(1)->comment('0 mean inactive');
            $table->foreignId('users_id')->constrained()->onDelete('cascade')->onUpdate('No Action');
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
        Schema::dropIfExists('companies');
    }
}
