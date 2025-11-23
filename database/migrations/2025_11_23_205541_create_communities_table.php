<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Inserir comunidades iniciais
        DB::table('communities')->insert([
            ['name' => 'Cantagalo', 'slug' => 'cantagalo', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pavão-Pavãozinho', 'slug' => 'pavao-pavaozinho', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cabritos', 'slug' => 'cabritos', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};
