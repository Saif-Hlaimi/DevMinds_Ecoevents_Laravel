<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_causes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->decimal('raised_amount', 10, 2)->default(0.00);
            $table->decimal('goal_amount', 10, 2);
            $table->string('sdg');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_causes');
    }
};