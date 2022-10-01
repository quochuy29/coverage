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
        Schema::table('members', function (Blueprint $table) {
            $table->index('member_code', 'idx_member_code');
            $table->index('member_name', 'idx_member_name');
            $table->index('member_login_name', 'idx_member_login_name');
            $table->index('member_is_deleted', 'idx_member_is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('idx_member_code');
            $table->dropIndex('idx_member_name');
            $table->dropIndex('idx_member_login_name');
            $table->dropIndex('idx_member_is_deleted');
        });

    }
};
