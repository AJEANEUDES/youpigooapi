<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompagniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compagnies', function (Blueprint $table) {

            $table->bigIncrements('id_compagnie');
            $table->string('nom_compagnie', 200);
            $table->string('slug_compagnie', 200);
            $table->text('description_compagnie');
            $table->string('image_compagnie')->nullable();
            $table->boolean('status_compagnie')->default(true);
            // $table->string('prix_estimatif_reservation_bus_compagnie');
            $table->string('telephone1_compagnie', 100);
            $table->string('telephone2_compagnie', 100)->nullable();
            $table->string('email_compagnie')->unique();
            $table->string('adresse_compagnie');
            $table->string('numero_rccm_compagnie')->nullable();
            $table->string('numero_cnss_compagnie')->nullable();
            $table->string('numero_if_compagnie')->nullable();
            $table->string('longitude_compagnie')->nullable();
            $table->string('latitude_compagnie')->nullable();

            $table->bigInteger('ville_id')->unsigned()->nullable();
            $table->bigInteger('pays_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();


            $table->timestamps();

            $table->foreign('ville_id')
            ->on('villes')
            ->references('id_ville')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        $table->foreign('pays_id')
            ->on('pays')
            ->references('id_pays')
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
        Schema::dropIfExists('compagnies');
    }
}
