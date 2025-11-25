<?php

/**
 * Script para tornar um usuário ADMIN
 *
 * Uso:
 * 1. Edite a variável $email abaixo
 * 2. Execute: php make-admin.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ============================================
// CONFIGURE AQUI: Email do usuário a ser admin
// ============================================
$email = 'seu@email.com'; // ← MUDE AQUI PARA SEU EMAIL

echo "\n";
echo "===========================================\n";
echo "  Tornar Usuário ADMIN\n";
echo "===========================================\n\n";

// Buscar usuário
$user = \App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "❌ ERRO: Usuário não encontrado com o email: $email\n\n";
    echo "Usuários disponíveis:\n";
    echo "---------------------------------------------\n";

    $allUsers = \App\Models\User::all();
    foreach ($allUsers as $u) {
        echo "  - {$u->name} ({$u->email}) - Role: {$u->role}\n";
    }
    echo "---------------------------------------------\n\n";
    echo "Edite o arquivo make-admin.php e coloque um email válido.\n\n";
    exit(1);
}

echo "✅ Usuário encontrado:\n";
echo "---------------------------------------------\n";
echo "  ID:    {$user->id}\n";
echo "  Nome:  {$user->name}\n";
echo "  Email: {$user->email}\n";
echo "  Role atual: {$user->role}\n";
echo "---------------------------------------------\n\n";

if ($user->role === 'admin') {
    echo "ℹ️  Este usuário já é ADMIN!\n";
    echo "Não é necessário fazer nada.\n\n";
    exit(0);
}

// Confirmar mudança
echo "⚠️  Você deseja tornar este usuário ADMIN? (s/n): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$resposta = trim(strtolower($line));
fclose($handle);

if ($resposta !== 's' && $resposta !== 'sim') {
    echo "\n❌ Operação cancelada.\n\n";
    exit(0);
}

// Fazer a mudança
$roleAntiga = $user->role;
$user->role = 'admin';
$user->save();

echo "\n";
echo "===========================================\n";
echo "  ✅ SUCESSO!\n";
echo "===========================================\n\n";
echo "Role mudada de '{$roleAntiga}' para 'admin'\n\n";
echo "⚠️  IMPORTANTE:\n";
echo "1. Faça LOGOUT no navegador\n";
echo "2. Faça LOGIN novamente\n";
echo "3. Acesse: https://imoveis.comunidadeppg.com.br/admin\n\n";
echo "Agora você pode acessar o dashboard administrativo!\n\n";
