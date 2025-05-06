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
        Schema::create('survey_response_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_hierarchy_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('organization_names_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedInteger('sent_count');
            $table->unsignedInteger('answered_count');
            $table->float('response_rate');
            $table->date('collected_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_response_stats');
    }
};
