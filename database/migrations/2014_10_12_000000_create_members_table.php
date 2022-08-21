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
        Schema::create('members', function (Blueprint $table) {
            $table->increments('member_id');
            $table->string('member_code', 50)->nullable();
            $table->string('member_name', 256)->nullable();
            $table->string('member_login_name', 45)->nullable();
            $table->string('member_password', 256);
            $table->string('member_email', 256)->nullable();
            $table->text('member_avatar')->nullable();
            $table->string('member_phone_mobile', 45)->nullable();
            $table->tinyInteger('member_is_deleted')->default(0)->nullable();
            $table->integer('member_creator_id')->nullable();
            $table->integer('member_updater_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('member_reset_pwd_is_required')->default(0);
            $table->dateTime('member_reset_pwd_datetime')->nullable();
            $table->string('member_forget_password_key',256)->nullable();
            $table->dateTime('member_forget_password_time')->nullable();
            $table->index('member_code');
            $table->index('member_login_name');
            $table->index('member_is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
