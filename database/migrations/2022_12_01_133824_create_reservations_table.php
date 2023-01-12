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
            // $table->float('total')->nullable();
            $table->dateTime('datearrivee')->nullable(); 
            $table->dateTime('datedepart')->nullable(); 
            $table->integer('totaladultes')->nullable(); 
            $table->string('totalenfants')->nullable();
            $table->string('nombredechambres')->nullable();
            // $table->string('email_user', 100)->unique();
            // $table->string('telephone_user', 50)->unique();
            // $table->string('prefix_user', 10)->nullable();
            $table->text('message')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status_reservation')->default(true);
            $table->boolean('status_annulation')->default(false);


            $table->bigInteger('chambre_id')->unsigned();
            $table->bigInteger('hotel_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

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
        Schema::dropIfExists('reservations');
    }
}
