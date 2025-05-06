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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('organization_hierarchy_id')->nullable()->constrained('organization_hierarchies')->onDelete('cascade');
            $table->foreignId('organization_names_id')->nullable()->constrained('organization_names')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['organization_hierarchy_id']);
            $table->dropForeign(['organization_names_id']);
            $table->dropColumn(['company_id', 'organization_hierarchy_id', 'organization_names_id']);
        });
    }
};
