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
        Schema::create('organization_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('organization_hierarchy_id')->constrained('organization_hierarchies')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('organization_names')->onDelete('set null');
            $table->string('name');
            $table->string('organization_size')->nullable();
            $table->string('remote_work_status')->nullable();
            $table->string('flex_time_status')->nullable();
            $table->string('one_on_one_frequency')->nullable();
            $table->string('age_distribution')->nullable();
            $table->string('average_overtime_hours')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_names');
    }
};
