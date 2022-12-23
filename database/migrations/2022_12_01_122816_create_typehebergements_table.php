<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypehebergementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typehebergements', function (Blueprint $table) {
            $table->bigIncrements('id_typehebergement');
            $table->string('nom_typehebergement', 200);
            // $table->string('code_typehebergement')->unique();
            $table->string('description_typehebergement');
            $table->string('image_typehebergement')->nullable();
            $table->string('slug_typehebergement');
          
            $table->bigInteger('pays_id')->unsigned()->nullable();
            $table->bigInteger('ville_id')->unsigned()->nullable();
          
            
            $table->boolean('status_typehebergement')->default(true);
            $table->bigInteger('created_by')->unsigned()->nullable();

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
        Schema::dropIfExists('typehebergements');
    }
}
