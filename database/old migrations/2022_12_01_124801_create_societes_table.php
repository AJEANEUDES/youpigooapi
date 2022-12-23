<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocietesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('societes', function (Blueprint $table) {

            $table->bigIncrements('id_societe');
            $table->string('nom_societe', 100);
            $table->string('adresse_societe', 100);
            $table->string('telephone1_societe', 100);
            $table->string('telephone2_societe', 100)->nullable();
            $table->string('slug_societe');
            $table->string('type_societe')->nullable();
            // $table->string('code_societe')->unique();
            $table->boolean('status_societe')->default(true);

            
            $table->bigInteger('hotel_id')->unsigned();
            $table->bigInteger('typehebergement_id')->unsigned();
            $table->bigInteger('ville_id')->unsigned();
            $table->bigInteger('pays_id')->unsigned();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->timestamps();


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
        Schema::dropIfExists('societes');
    }
}
