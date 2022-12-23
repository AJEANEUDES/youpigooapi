<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * numero_rccm = Registre du commerce et du crédit mobilier (RCCM) est un répertoire à caractère 
     * officiel qui a pour objet de recevoir l'immatriculation des personnes physiques ou morales 
     * exerçant une activité commerciale, ainsi que la déclaration des entreprenants.
     *
     *numero_cnss = numéro de la caisse nationale de sécurité sociale 
     * 
     * numero_if = numero d'identifiant fiscal
     * 
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {

            $table->bigIncrements('id_hotel');
            $table->string('nom_hotel', 200);
            $table->string('slug_hotel', 200);
            // $table->string('code_hotel')->unique();
            $table->string('description_hotel');

            $table->bigInteger('ville_id')->unsigned()->nullable();
            $table->bigInteger('pays_id')->unsigned()->nullable();
            $table->bigInteger('typehebergement_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();


            
            $table->string('image_hotel')->nullable();
            $table->boolean('status_hotel')->default(true);
            $table->unsignedInteger('etoile');
            $table->string('date_reservation_hotel')->nullable();
            $table->string('prix_estimatif_chambre_hotel');
            $table->string('telephone1_hotel', 100);
            $table->string('telephone2_hotel', 100)->nullable();
            $table->string('email_hotel')->unique();
            $table->string('adresse_hotel');
            $table->boolean('populaire_hotel')->default(false);
            $table->boolean('special_hotel')->default(false);
            $table->boolean('meilleur_hotel')->default(false);
            $table->string('numero_rccm_hotel')->nullable();
            $table->string('numero_cnss_hotel')->nullable();
            $table->string('numero_if_hotel')->nullable();
            $table->string('longitude_hotel')->nullable();
            $table->string('latitude_hotel')->nullable();
            // $table->bigInteger('created_by')->unsigned()->nullable();
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

            $table->foreign('typehebergement_id')
                ->on('typehebergements')
                ->references('id_typehebergement')
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
        Schema::dropIfExists('hotels');
    }
}
