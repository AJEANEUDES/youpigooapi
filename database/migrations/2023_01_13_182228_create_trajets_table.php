<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrajetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trajets', function (Blueprint $table) {

            $table->bigIncrements('id_trajet');
            $table->string('adresse_depart_trajet');
            $table->string('adresse_arrivee_trajet');
            $table->string('ville_depart');
            $table->string('ville_arrivee');
            $table->date('date_depart');
            $table->date('date_arrivee');
            $table->time('heure_depart');
            $table->time('heure_arrivee');
           
            $table->boolean('status_trajet')->default(true);

            $table->bigInteger('bus_id')->unsigned()->nullable();
            $table->bigInteger('region_id')->unsigned()->nullable();
            $table->bigInteger('prefecture_id')->unsigned()->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('bus_id')
            ->on('buses')
            ->references('id_bus')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('created_by')
            ->on('users')
            ->references('id')
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
        Schema::dropIfExists('trajets');
    }
}
