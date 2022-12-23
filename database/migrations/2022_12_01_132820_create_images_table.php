<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {

            $table->bigIncrements('id_image');
            $table->string('path_image');
            $table->bigInteger('pays_id')->unsigned()->nullable();
            $table->bigInteger('ville_id')->unsigned()->nullable();
            $table->bigInteger('typehebergement_id')->unsigned()->nullable();
            $table->bigInteger('hotel_id')->unsigned()->nullable();
            $table->bigInteger('categoriechambre_id')->unsigned()->nullable();
            $table->bigInteger('chambre_id')->unsigned()->nullable();
            // $table->bigInteger('societe_id')->unsigned()->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('pays_id')
                ->on('pays')
                ->references('id_pays')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->foreign('ville_id')
                ->on('villes')
                ->references('id_ville')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->foreign('typehebergement_id')
                ->on('typehebergements')
                ->references('id_typehebergement')
                ->onUpdate('cascade')
                ->onDelete('cascade');



            $table->foreign('hotel_id')
                ->on('hotels')
                ->references('id_hotel')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->foreign('categoriechambre_id')
                ->on('categoriechambres')
                ->references('id_categoriechambre')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->foreign('chambre_id')
                ->on('chambres')
                ->references('id_chambre')
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
        Schema::dropIfExists('images');
    }
}
