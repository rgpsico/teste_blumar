<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->enum('user_type', ['owner', 'tenant', 'admin']);
            $table->timestamp('registered_at');
            $table->timestamps();

            $table->index('user_id');
            $table->index('registered_at');
            $table->index('user_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_logs');
    }
};
