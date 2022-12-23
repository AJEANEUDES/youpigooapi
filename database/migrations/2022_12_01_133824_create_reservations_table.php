<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * table reservations = tablede données reservée à la reservations
     *de chambres dans un hôtel donnée
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {

            $table->bigIncrements('id_reservation');
            $table->integer('prix_reservation');
            $table->text('service_reservation')->nullable();
            $table->boolean('status_reservation')->default(false);
            $table->boolean('status_annulation')->default(false);


            $table->bigInteger('chambre_id')->unsigned();
            $table->bigInteger('hotel_id')->unsigned();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('chambre_id')
                ->on('chambres')
                ->references('id_chambre')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('hotel_id')
                ->on('hotels')
                ->references('id_hotel')
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
        Schema::dropIfExists('reservations');
    }
}
