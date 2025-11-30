<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Troca o ENUM antigo por VARCHAR para aceitar novos status sem erro de truncamento
        DB::statement("ALTER TABLE properties MODIFY status VARCHAR(20) DEFAULT 'available'");
    }

    public function down(): void
    {
        // Volta para ENUM original
        DB::statement("ALTER TABLE properties MODIFY status ENUM('available','rented','maintenance','inactive') DEFAULT 'available'");
    }
};
