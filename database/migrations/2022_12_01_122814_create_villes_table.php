<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVillesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('villes', function (Blueprint $table) {
            $table->bigIncrements('id_ville');
            $table->string('nom_ville', 200);
            $table->string('image_ville')->nullable();
            $table->string('slug_ville');

            $table->bigInteger('pays_id')->unsigned()->nullable();
           
            $table->boolean('status_ville')->default(true);
            $table->text('description_ville')->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();

            $table->timestamps();

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
        Schema::dropIfExists('villes');
    }
}
