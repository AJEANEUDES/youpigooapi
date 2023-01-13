<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusreservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('busreservations', function (Blueprint $table) {

            $table->bigIncrements('id_busreservation');
            $table->date('date_depart');
            $table->date('date_arrivee');
            $table->text('message')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status_busreservation')->default(true);
            $table->boolean('status_annulation')->default(false);
            $table->string('nombreplaces')->nullable();
           
            $table->bigInteger('bus_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();


            $table->timestamps();

            $table->foreign('bus_id')
            ->on('buses')
            ->references('id_bus')
            ->onUpdate('cascade')
            ->onDelete('cascade');


        $table->foreign('user_id')
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
        Schema::dropIfExists('busreservations');
    }
}
