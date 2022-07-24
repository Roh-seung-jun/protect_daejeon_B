<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGardensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gardens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('introduce');
            $table->string('institution');
            $table->string('phone');
            $table->string('open-close');
            $table->string('address');
            $table->text('image');
            $table->integer('limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gardens');
    }
}
