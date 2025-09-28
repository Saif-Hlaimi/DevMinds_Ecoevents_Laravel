<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('events', function (Blueprint $table) {
        $table->unsignedBigInteger('category_id')->nullable()->after('user_id');
        $table->enum('type', ['online', 'onsite'])->default('onsite')->after('category_id');
        $table->integer('max_participants')->nullable()->after('type'); // null si illimité
        $table->string('meet_link')->nullable()->after('max_participants'); // si en ligne
    });
}

public function down()
{
    Schema::table('events', function (Blueprint $table) {
        $table->dropColumn(['category_id', 'type', 'max_participants', 'meet_link']);
    });
}

};
