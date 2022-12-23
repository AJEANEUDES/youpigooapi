<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGestionnairesSocietesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestionnaires_societes', function (Blueprint $table) {

            $table->bigIncrements('id_gestionnaire_societe');
            $table->bigInteger('gestionnaire_hotel_id')->unsigned()->nullable();
            $table->bigInteger('gestionnaire_compagnie_id')->unsigned()->nullable();
            $table->bigInteger('societe_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('gestionnaire_hotel_id')
                ->on('users')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->foreign('gestionnaire_compagnie_id')
                ->on('users')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('societe_id')
                ->on('societes')
                ->references('id_societe')
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
        Schema::dropIfExists('gestionnaires_societes');
    }
}
