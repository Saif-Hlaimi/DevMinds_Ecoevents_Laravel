<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('subject');
            $table->text('message');
            $table->string('category')->default('general');
            $table->enum('priority', ['low','medium','high'])->default('medium');
            $table->enum('status', ['open','pending','resolved','closed'])->default('open');

            $table->foreignId('assigned_to')->nullable()
                  ->constrained('users')->nullOnDelete();

            // FK vers complaint_types (nullable + SET NULL)
            $table->foreignId('complaint_type_id')->nullable()
                  ->constrained('complaint_types')
                  ->nullOnDelete();

            $table->timestamps();

            $table->index(['status','priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
