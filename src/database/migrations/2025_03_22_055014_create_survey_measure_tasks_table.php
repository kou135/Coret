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
        Schema::create('survey_measure_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measure_id')->constrained('survey_measures')->onDelete('cascade');
            $table->text('task_text');
            $table->date('deadline_date')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_measure_tasks');
    }
};
