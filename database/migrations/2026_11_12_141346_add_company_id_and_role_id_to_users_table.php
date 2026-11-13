<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // SuperAdmin can have NULL company_id

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            // Every user must have a role

            $table->foreignId('role_id')
                ->constrained('roles')
                ->restrictOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropConstrainedForeignId('company_id');

            $table->dropConstrainedForeignId('role_id');

        });
    }
};
