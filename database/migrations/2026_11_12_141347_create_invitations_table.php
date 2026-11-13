<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

            $table->foreignId('invited_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('email');

            $table->foreignId('role_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->string('token', 100)->unique();

            $table->enum('status', [
                'pending',
                'accepted',
                'expired',
                'cancelled'
            ])->default('pending');

            $table->timestamp('expires_at')->nullable();

            $table->timestamp('accepted_at')->nullable();

            $table->timestamps();

            $table->index(['company_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
