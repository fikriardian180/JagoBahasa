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
    Schema::create('lessons', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->longText('content');
        $table->string('duration')->nullable();
        $table->string('attachment_url')->nullable();
        $table->integer('order')->default(1);
        $table->timestamps();
    });

    // Tabel pivot progres bab selesai per murid
    Schema::create('lesson_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->timestamp('completed_at')->useCurrent();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
