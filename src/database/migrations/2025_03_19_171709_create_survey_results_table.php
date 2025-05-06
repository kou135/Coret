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
        Schema::create('survey_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('survey_questions')->onDelete('cascade');

            // 会社単位 / 組織単位 の集計を容易にするため残す設計(不要なら省略も可)
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('organization_hierarchy_id')->nullable()->constrained('organization_hierarchies')->onDelete('cascade');
            $table->foreignId('organization_names_id')->nullable()->constrained('organization_names')->onDelete('cascade');

            $table->float('average_score')->nullable();
            $table->integer('response_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_results');
    }
};
