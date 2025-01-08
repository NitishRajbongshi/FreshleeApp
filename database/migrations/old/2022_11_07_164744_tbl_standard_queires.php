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
        Schema::create('tbl_standard_queires', function (Blueprint $table) {
            $table->bigIncrements('query_id');
            $table->string('query_desc', 255);
            $table->boolean('is_moderated')->default(1);
            $table->unsignedBigInteger('query_catg');
            $table->string('query_submitted_by');
            $table->timestamp('query_submitted_on');
            $table->string('moderated_by');
            $table->timestamp('moderated_on')->nullable(true);
            $table->double('lat');
            $table->double('lon');
            $table->string('district');
            $table->string('ack_no');
            $table->integer('has_attachment')->default(1);
            $table->timestamps();

            $table->foreign('query_catg')->references('catg_id')->on('tbl_standard_queries_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        schema::dropIfExists('tbl_standard_queires');
    }
};
