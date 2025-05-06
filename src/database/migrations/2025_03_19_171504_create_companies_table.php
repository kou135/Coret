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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_code', 4)->unique();
            $table->string('name');
            $table->string('company_size')->nullable();
            $table->string('business_years')->nullable();
            $table->string('evaluation_frequency')->nullable();
            $table->string('salary_transparency')->nullable();
            $table->string('evaluation_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
