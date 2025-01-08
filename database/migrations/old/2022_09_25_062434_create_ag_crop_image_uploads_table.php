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
        Schema::create('ag_crop_image_uploads', function (Blueprint $table) {
         
            $table->bigIncrements('image_id');

            $table->bigInteger('crop_type_cd')->nullable(true);
            $table->bigInteger('crop_name_cd')->nullable(true);
            $table->bigInteger('crop_variety_cd')->nullable(true);
            $table->bigInteger('crop_stage_cd')->nullable(true);
            $table->string('image_path', $length = 255);
            $table->string('uploaded_by')->nullable(true);
            $table->timestamp('uploaded_on');
            $table->string('state_cd', $length=10)->nullable(true);
            $table->string('district_cd',$length=10)->nullable(true);
            $table->bigInteger('crop_disease_cd');
            $table->string('weather_data_secret_code')->nullable(true);
            $table->bigInteger('weather_data_cd')->nullable(true);

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
        Schema::dropIfExists('ag_crop_image_uploads');
    }
};
