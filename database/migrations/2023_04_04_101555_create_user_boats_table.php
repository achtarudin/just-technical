<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_boats', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('owner');
            $table->string('address');
            $table->integer('size');
            $table->string('captain_name');
            $table->integer('total_abk');
            $table->string('document_number')->nullable();
            $table->string('document')->nullable()->comment('Pdf document');
            $table->string('image')->nullable()->comment('Image of boat');
            $table->unsignedBigInteger('user_id')->comment('Reference to users table');
            $table->enum('status', ['pending', 'approved', 'rejected'])->comment('status with value [pending, approved ,rejected]')->default('pending');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('admin_id')->comment('Reference to users table')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('admin_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_boats');
    }
};
