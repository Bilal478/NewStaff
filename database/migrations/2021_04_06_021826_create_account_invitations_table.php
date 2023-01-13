<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('role');
            $table->unsignedBigInteger('account_id');
            $table->timestamps();

            $table->unique(['account_id', 'email']);
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_invitations');
    }
}
