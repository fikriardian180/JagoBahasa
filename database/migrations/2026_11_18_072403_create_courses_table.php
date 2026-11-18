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
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
        $table->string('title');
        $table->string('language'); // Bahasa Inggris, Bahasa Arab, dll
        $table->string('class_code')->unique(); // Contoh: JGB-EN01
        $table->text('description')->nullable();
        $table->timestamps();
    });

    // Tabel pivot murid yang bergabung ke kursus
    Schema::create('course_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
