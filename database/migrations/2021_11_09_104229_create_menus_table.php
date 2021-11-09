<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('code', 50);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('nav_id');
            $table->foreign('nav_id')->references('id')->on('navigations')->onUpdate('cascade');
            $table->string('icon', 100);
            $table->integer('order');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
