<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *La table user sera considéré comme celui de l'Administrateur(Table)
     * 
     * @return void
     */
    public function up()
    {
        //Table Users = table Admin
        
        Schema::create('users', function (Blueprint $table) {
          
            $table->bigIncrements('id');
            // $table->string('code_user')->unique();
            $table->string('nom_user', 100);
            $table->string('prenoms_user', 50);
            $table->string('email_user', 100)->unique();
            $table->string('telephone_user', 15)->unique();
            $table->string('prefix_user', 10)->nullable();
            $table->string('adresse_user', 500);
            $table->string('image_user')->default("images/profile.jpg");
            $table->string('roles_user', 30);
            $table->boolean('status_user')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('pays_user', 100)->nullable();
            $table->string('ville_user', 100)->nullable();

            $table->rememberToken();
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
