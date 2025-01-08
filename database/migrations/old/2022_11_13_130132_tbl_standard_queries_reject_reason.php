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
        Schema::create('tbl_standard_queries_reject_reason', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query_id');
            $table->string('reason');
            $table->timestamps();

            $table->foreign('query_id')->references('query_id')->on('tbl_standard_queries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        schema::dropIfExists('tbl_standard_queries_reject_reason');
    }
};
