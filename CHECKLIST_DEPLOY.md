# ✅ Checklist de Deploy - Correção CSRF 403

## Antes do Deploy

- [ ] Testar localmente com `npm run dev`
- [ ] Verificar se não há erros no console do navegador
- [ ] Testar operações CRUD no painel do proprietário localmente
- [ ] Fazer backup do banco de dados de produção
- [ ] Fazer backup dos arquivos atuais do servidor

## Upload de Arquivos

### Opção 1: Via Git (Recomendado)
```bash
git add .
git commit -m "Fix: Corrigido erro 403 CSRF e implementado description_short"
git push origin main

# No servidor
git pull origin main
```

### Opção 2: Via FTP/SFTP
Fazer upload dos seguintes arquivos:
- [ ] `bootstrap/app.php`
- [ ] `resources/js/bootstrap.js`
- [ ] `resources/views/app.blade.php`
- [ ] `app/Models/Property.php`
- [ ] `resources/js/views/Owner/Dashboard.vue`
- [ ] `resources/js/views/Public/PropertyList.vue`
- [ ] `resources/js/views/Admin/Dashboard.vue`

## No Servidor de Produção

### 1. Compilar Assets
- [ ] Executar: `npm run build`
- [ ] OU fazer upload da pasta `public/build/` compilada localmente

### 2. Limpar Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```
- [ ] Cache de configuração limpo
- [ ] Cache de aplicação limpo
- [ ] Cache de rotas limpo
- [ ] Cache de views limpo

### 3. Otimizar (Opcional)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
- [ ] Configuração cacheada
- [ ] Rotas cacheadas
- [ ] Views cacheadas

### 4. Reiniciar Serviços
- [ ] PHP-FPM reiniciado: `sudo systemctl restart php8.2-fpm`
- [ ] Nginx/Apache reiniciado (se necessário)

## Testes Pós-Deploy

### Funcionalidade Básica
- [ ] Site carrega normalmente
- [ ] Login funciona
- [ ] Listagem de imóveis funciona
- [ ] Detalhes do imóvel funcionam

### Painel do Proprietário
- [ ] Login como proprietário funciona
- [ ] Dashboard carrega corretamente
- [ ] **Criar novo imóvel** - Não deve dar erro 403
- [ ] **Editar imóvel existente** - Não deve dar erro 403
- [ ] **Excluir imóvel** - Não deve dar erro 403
- [ ] Upload de fotos funciona
- [ ] FAQs/Bot funcionam

### Descrição Curta
- [ ] Na listagem pública, descrições longas aparecem com "..."
- [ ] Na listagem do proprietário, descrições longas aparecem com "..."
- [ ] Na página de detalhes, descrição completa aparece

## Se Algo Der Errado

### Erro 403 persiste
1. [ ] Verificar se os arquivos foram realmente atualizados
2. [ ] Limpar cache do navegador (Ctrl+Shift+Delete)
3. [ ] Verificar logs: `tail -f storage/logs/laravel.log`
4. [ ] Verificar se o Bearer token está sendo enviado corretamente

### Assets não atualizados
1. [ ] Verificar se `npm run build` foi executado
2. [ ] Verificar se a pasta `public/build/` foi atualizada
3. [ ] Limpar cache do navegador com Ctrl+F5

### Erro 500
1. [ ] Verificar permissões: `chmod -R 755 storage bootstrap/cache`
2. [ ] Verificar logs do Laravel
3. [ ] Verificar logs do Nginx/Apache

## Rollback (Se necessário)

Se algo der muito errado:
1. [ ] Restaurar backup dos arquivos
2. [ ] Restaurar backup do banco de dados
3. [ ] Limpar cache novamente
4. [ ] Reiniciar serviços

## Notas Importantes

- ⚠️ O sistema agora usa **Bearer Token** para autenticação da API, não CSRF
- ⚠️ Todas as rotas `/api/*` estão **isentas de verificação CSRF**
- ⚠️ Isto é **normal e seguro** para APIs RESTful
- ✅ A autenticação via Bearer token é mais segura que CSRF para APIs
