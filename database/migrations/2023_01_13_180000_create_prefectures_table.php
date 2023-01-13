<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefectures', function (Blueprint $table) {
            $table->bigIncrements('id_prefecture');
            $table->string('nom_prefecture', 200);
            $table->string('code_prefecture');
            $table->boolean('status_prefecture')->default(true);

            $table->bigInteger('region_id')->unsigned()->nullable();


            $table->timestamps();

            $table->foreign('region_id')
            ->on('regions')
            ->references('id_region')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prefectures');
    }
}
