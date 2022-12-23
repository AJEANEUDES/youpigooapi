<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriechambresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoriechambres', function (Blueprint $table) {

            $table->bigIncrements('id_categoriechambre');
            $table->string('libelle_categoriechambre', 100);
            $table->string('slug_categoriechambre');
            $table->string('image_categoriechambre')->nullable();
            $table->string('prix_estimatif_categoriechambre');
            $table->string('description_categoriechambre');
           
           
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->boolean('status_categoriechambre')->default(true);

            $table->timestamps();

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
        Schema::dropIfExists('categoriechambres');
    }
}
