<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * table services : une chambre peut avoir plusieurs services..
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
          
            $table->bigIncrements('id_service');
            // $table->string('code_service')->unique();
            $table->string('nom_service', 200)->unique();
            $table->string('slug_service');
            $table->string('description_service');
            $table->bigInteger('chambre_id')->unsigned();
            $table->string('status_service')->default(true);
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')
                ->on('users')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');

                $table->foreign('chambre_id')
                ->on('chambres')
                ->references('id_chambre')
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
        Schema::dropIfExists('services');
    }
}
