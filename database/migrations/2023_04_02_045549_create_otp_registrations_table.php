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
        Schema::create('otp_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Reference to users table');
            $table->string('otp')->comment('Otp Code');
            $table->enum('status', ['pending', 'approved', 'rejected'])->comment('status with value [pending, approved ,rejected]')->default('pending');
            $table->unsignedBigInteger('admin_id')->comment('Reference to users table')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('admin_id')->references('id')->on('users');
            $table->unique('user_id', 'user only have one 1 otp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp_registrations');
    }
};
