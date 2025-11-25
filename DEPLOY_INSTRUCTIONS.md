# Instruções para Deploy - Correção do Erro 403 CSRF

## Arquivos Modificados

Os seguintes arquivos foram modificados para corrigir o erro 403 CSRF Token Mismatch:

1. `bootstrap/app.php` - Configuração dos middlewares
2. `resources/js/bootstrap.js` - Configuração do Axios
3. `resources/views/app.blade.php` - Meta tag CSRF
4. `app/Models/Property.php` - Adicionado accessor description_short
5. `resources/js/views/Owner/Dashboard.vue` - Uso de description_short
6. `resources/js/views/Public/PropertyList.vue` - Uso de description_short
7. `resources/js/views/Admin/Dashboard.vue` - Truncate no título

## Passos para Deploy no Servidor

### 1. Fazer Upload dos Arquivos
Faça upload de todos os arquivos modificados para o servidor via FTP/SFTP ou Git.

### 2. Recompilar Assets
No servidor, execute:
```bash
cd /path/to/imoveis
npm run build
```

Se não tiver acesso ao npm no servidor, compile localmente e faça upload da pasta `public/build`:
```bash
# No seu computador local
npm run build

# Faça upload da pasta public/build/ para o servidor
```

### 3. Limpar Cache do Laravel
No servidor, execute:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 4. Otimizar para Produção (Opcional mas recomendado)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Reiniciar Serviços
Se estiver usando PHP-FPM, reinicie:
```bash
sudo systemctl restart php8.2-fpm
# ou
sudo service php8.2-fpm restart
```

Se estiver usando Apache:
```bash
sudo systemctl restart apache2
# ou
sudo service apache2 restart
```

Se estiver usando Nginx:
```bash
sudo systemctl restart nginx
# ou
sudo service nginx restart
```

## Verificação

Após o deploy, teste:
1. Login no painel do proprietário
2. Tente editar um imóvel
3. Tente excluir um imóvel
4. Tente adicionar um novo imóvel

O erro 403 CSRF não deve mais aparecer.

## Troubleshooting

Se o erro persistir:

1. **Verifique se os arquivos foram atualizados no servidor:**
   ```bash
   # Verifique a data de modificação
   ls -la bootstrap/app.php
   ls -la public/build/
   ```

2. **Verifique os logs do Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Verifique se o cache foi limpo:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Verifique as permissões:**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

## Mudanças Importantes

### API não usa mais CSRF
As rotas `/api/*` agora estão isentas de verificação CSRF porque usam autenticação via Bearer token (Sanctum). Isso é o padrão correto para APIs RESTful.

### Bearer Token Authentication
O sistema continua usando Bearer tokens para autenticação, o que é mais seguro para APIs do que depender de sessões e CSRF.

### Descrição Curta
Todas as listagens agora mostram apenas 20 caracteres da descrição do imóvel, com "..." no final se for maior.
