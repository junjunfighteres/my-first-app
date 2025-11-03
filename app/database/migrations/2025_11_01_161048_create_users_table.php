<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('email', 255)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('password', 255)->nullable();
            $table->tinyInteger('role')->default(0);
            $table->tinyInteger('del_flg')->default(0);
            $table->string('profile_image_path', 255)->nullable();
            $table->text('self_introduction')->nullable();
            $table->string('password_reset_token', 255)->nullable();
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
