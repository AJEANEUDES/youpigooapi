<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChambresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chambres', function (Blueprint $table) {


            $table->bigIncrements('id_chambre');
            $table->string('nom_chambre', 200);
            // $table->string('code_chambre')->unique();
            $table->string('description_chambre'); //designation
            $table->string('slug_chambre');
            $table->string('image_chambre')->nullable();
            $table->string('classe_chambre');
            $table->string('nombre_lits_chambre');
            $table->string('nombre_places_chambre');
            $table->float('prix_standard_chambre');
            $table->boolean('status_chambre')->default(true);
            $table->boolean('status_reserver_chambre')->default(true);

            
            $table->bigInteger('categoriechambre_id')->unsigned();
            $table->bigInteger('hotel_id')->unsigned();
            $table->bigInteger('ville_id')->unsigned();
            $table->bigInteger('pays_id')->unsigned();
            $table->bigInteger('typehebergement_id')->unsigned();
           
           
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->timestamps();


            $table->foreign('categoriechambre_id')
                ->on('categoriechambres')
                ->references('id_categoriechambre')
                ->onUpdate('cascade')
                ->onDelete('cascade');



            $table->foreign('hotel_id')
                ->on('hotels')
                ->references('id_hotel')
                ->onUpdate('cascade')
                ->onDelete('cascade');

                
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


            $table->foreign('typehebergement_id')
                ->on('typehebergements')
                ->references('id_typehebergement')
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
        Schema::dropIfExists('chambres');
    }
}
