<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {   #Schema joka luo tietokantaan pöydän
            $table->increments('id');       //id on automaatisesti suurentuva               
            $table->string('tehtava');      //Tehtava on varchar
            $table->string('status');       //Status on varchar
            $table->integer('user_id');     //user_id on numero
            $table->timestamps();           //Pöytään oma row timestamps
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
