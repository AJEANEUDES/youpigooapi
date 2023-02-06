<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {

            $table->bigIncrements('id_bus');
            $table->string('nom_bus', 200);
            $table->string('slug_bus', 200);
            $table->string('immatriculation_bus', 200);
            $table->string('image_bus')->nullable();
            $table->text('description_bus');
            $table->boolean('status_bus')->default(true);
            $table->integer('nombre_places_bus');
            $table->string('marque_bus')->nullable();
            $table->string('modele_bus')->nullable();

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
        Schema::dropIfExists('buses');
    }
}
