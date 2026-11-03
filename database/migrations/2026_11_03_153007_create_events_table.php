<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Organizer
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('banner_path')->nullable();
            $table->string('venue');
            $table->dateTime('event_date');
            $table->integer('total_quota');
            $table->integer('remaining_quota');
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
