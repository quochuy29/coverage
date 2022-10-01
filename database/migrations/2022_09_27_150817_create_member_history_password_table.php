<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_history_password', function (Blueprint $table) {
            $table->increments('member_history_password_id');
            $table->string('member_code', 128)->index()->nullable(false);
            $table->string('member_history_password', 128)->nullable();
            $table->dateTime('member_history_password_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_history_password');
    }
};
