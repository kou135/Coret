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
        Schema::create('survey_measures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('organization_hierarchy_id')->constrained('organization_hierarchies')->onDelete('cascade');
            $table->foreignId('organization_names_id')->constrained('organization_names')->onDelete('cascade');
            $table->foreignId('question_id')->nullable()->constrained('survey_questions')->onDelete('set null');
            $table->string('measure_title');
            $table->text('measure_description');
            $table->string('target_scope')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('measure_effect')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_measures');
    }
};
