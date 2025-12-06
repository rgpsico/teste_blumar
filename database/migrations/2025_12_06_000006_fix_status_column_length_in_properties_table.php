<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Garante que a coluna aceite qualquer status válido sem erro de truncamento
        DB::statement("ALTER TABLE properties MODIFY status VARCHAR(20) DEFAULT 'available'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE properties MODIFY status ENUM('available','rented','maintenance','inactive') DEFAULT 'available'");
    }
};
