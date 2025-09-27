<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->dateTime('date');
            $table->string('location');
            $table->string('image')->nullable(); // Optional image for the event
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to users table
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}