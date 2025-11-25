#!/bin/bash

# Script de Deploy - Sistema de Imóveis
# Execute este script no servidor após fazer upload dos arquivos

echo "=========================================="
echo "  Deploy - Sistema de Imóveis"
echo "=========================================="
echo ""

# Verificar se está no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script na raiz do projeto Laravel"
    exit 1
fi

echo "📦 Instalando dependências do Composer..."
composer install --no-dev --optimize-autoloader

echo ""
echo "🔨 Compilando assets..."
if command -v npm &> /dev/null; then
    npm install
    npm run build
    echo "✅ Assets compilados com sucesso"
else
    echo "⚠️  npm não encontrado. Compile os assets localmente e faça upload da pasta public/build/"
fi

echo ""
echo "🧹 Limpando cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "✅ Cache limpo"

echo ""
echo "⚡ Otimizando para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo "✅ Otimização concluída"

echo ""
echo "🔐 Ajustando permissões..."
chmod -R 755 storage bootstrap/cache
echo "✅ Permissões ajustadas"

echo ""
echo "=========================================="
echo "  ✅ Deploy Concluído!"
echo "=========================================="
echo ""
echo "Próximos passos:"
echo "1. Reinicie o PHP-FPM: sudo systemctl restart php8.2-fpm"
echo "2. Reinicie o Nginx/Apache (se necessário)"
echo "3. Teste o sistema no navegador"
echo ""
