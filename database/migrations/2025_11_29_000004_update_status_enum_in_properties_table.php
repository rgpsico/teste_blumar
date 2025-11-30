<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Garante que o novo status "inactive" seja aceito
        DB::statement("ALTER TABLE properties MODIFY status ENUM('available','rented','maintenance','inactive') DEFAULT 'available'");
    }

    public function down(): void
    {
        // Reverte para os valores anteriores
        DB::statement("ALTER TABLE properties MODIFY status ENUM('available','rented','maintenance') DEFAULT 'available'");
    }
};
