<?php

use App\Models\Type\TypeModel;
use Illuminate\Support\Facades\Schema;
use App\Services\InitMigrationServices;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        InitMigrationServices::createTypes();
    }

    public function down()
    {
        Schema::dropIfExists('types');
    }
};
