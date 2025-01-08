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
        Schema::create('ag_crop_disease_master', function (Blueprint $table) {
            $table->bigIncrements('disease_cd');
            $table->string('disease_name', 255)->unique();
            $table->string('disease_desc', 255);
            $table->string('folder_name');
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
        Schema::dropIfExists('ag_crop_disease_master');
    }
};
