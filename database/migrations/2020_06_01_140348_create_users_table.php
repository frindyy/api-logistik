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
            $table->id();
            $table->string('name');
            $table->string('identity_id');
            $table->char('gender',1)->comment('0: female, 1: male');
            $table->text('address');
            $table->string('photo');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number',20);
            $table->string('api_token')->nullable();
            $table->char('role',1)->comment('0: admin, 1: driver, 2: user');
            $table->boolean('status')->default(false);
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
