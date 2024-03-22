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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->bigInterger()->constrained('students')->onDelete('cascade');
            $table->foreignId('teacher_to_subject_id')->bigInterger()->constrained('teacher_to_subjects')->onDelete('cascade');
            $table->string('title');
            $table->string('path');
            $table->float('mark', 8,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
