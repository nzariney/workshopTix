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
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Web Development');
            $table->string('instructor_name');
            $table->string('instructor_title')->nullable();
            $table->text('description');
            $table->text('target_audience')->nullable();
            $table->text('learning_points')->nullable();
            $table->text('prerequisites')->nullable();
            $table->date('date');
            $table->string('start_time', 10);
            $table->string('end_time', 10);
            $table->string('location_type')->default('offline');
            $table->string('location');
            $table->decimal('price', 12, 2)->default(0);
            $table->unsignedInteger('quota')->default(30);
            $table->string('image')->nullable();
            $table->enum('status', ['available', 'full', 'closed'])->default('available');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['status', 'date']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
