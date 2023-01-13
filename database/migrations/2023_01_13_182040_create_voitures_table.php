<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voitures', function (Blueprint $table) {
            $table->bigIncrements('id_voiture');
            $table->string('nom_voiture', 200);
            $table->string('slug_voiture', 200);
            $table->string('immatriculation_voiture', 200);
            $table->string('image_voiture')->nullable();
            $table->boolean('status_voiture')->default(true);
            $table->integer('nombre_places_voiture');
            $table->string('marque_voiture')->nullable();
            $table->string('modele_voiture')->nullable();

            $table->bigInteger('compagnie_id')->unsigned()->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('compagnie_id')
            ->on('compagnies')
            ->references('id_compagnie')
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
        Schema::dropIfExists('voitures');
    }
}
