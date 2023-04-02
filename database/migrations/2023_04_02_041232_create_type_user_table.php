<?php

use Illuminate\Support\Facades\Schema;
use App\Services\InitMigrationServices;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

    public function up()
    {
        Schema::dropIfExists('type_users');

        Schema::create('type_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Reference to users table');
            $table->unsignedBigInteger('types_id')->comment('Reference to types table');
            $table->string('description')->comment('Create By Migration');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('types_id')->references('id')->on('types');
            $table->unique('user_id', 'user only have one 1 types');
            $table->timestamps();
            $table->softDeletes();
        });

        InitMigrationServices::createNewAdmin();

    }


    public function down()
    {
        Schema::dropIfExists('type_users');
    }
};
