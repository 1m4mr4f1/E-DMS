<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPreferencesTable extends Migration
{
    public function up()
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->unique();
            $table->boolean('email_notif_share')->default(true);
            $table->boolean('email_notif_update')->default(true);
            $table->boolean('email_notif_expiring')->default(true);
            $table->string('ui_language')->default('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_preferences');
    }
}
