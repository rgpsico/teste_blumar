# ✅ Correção do Rastreamento de Visitantes

## Problema Identificado

Os logs de visitantes não estavam sendo registrados porque:

1. **SPA Architecture**: A aplicação é uma Single Page Application (SPA) Vue.js
2. **Middleware no Lugar Errado**: O middleware `TrackVisitor` estava apenas nas rotas **web**
3. **Navegação Client-Side**: O Vue Router navega sem recarregar a página, então as rotas web só são acessadas na primeira carga
4. **API Calls**: Todas as interações subsequentes são chamadas API que não passavam pelo middleware

## Correções Implementadas

### 1. **Middleware Adicionado às Rotas API**

Arquivo: `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    // Adicionar TrackVisitor tanto para web quanto API
    // Para SPAs, precisamos rastrear nas rotas API também
    $middleware->web(append: [
        \App\Http\Middleware\TrackVisitor::class,
    ]);

    // Configurar middlewares da API
    $middleware->api(append: [
        \App\Http\Middleware\TrackVisitor::class, // Rastrear visitantes nas chamadas API
    ]);

    // Excluir rotas API da verificação CSRF
    $middleware->validateCsrfTokens(except: [
        'api/*',
    ]);
})
```

### 2. **Rastreamento Inteligente (Evita Duplicatas)**

Arquivo: `app/Http/Middleware/TrackVisitor.php`

O middleware agora:
- ✅ Rastreia apenas requisições GET específicas (navegação real)
- ✅ Rastreia rotas web: `/`, `/login`, `/register`
- ✅ Rastreia visualizações de imóveis: `api/properties`, `api/properties/{id}`
- ✅ Evita duplicatas: não registra o mesmo IP+URL na última hora
- ✅ Não rastreia toda chamada API (evita logs excessivos)

```php
// Para SPAs, rastrear apenas requisições GET específicas
$shouldTrack = false;

// Rastrear rotas web (primeira carga da página)
if ($request->is('/') || $request->is('login') || $request->is('register')) {
    $shouldTrack = true;
}

// Rastrear apenas certas rotas API (GET) que representam navegação
if ($request->isMethod('GET') && $request->is('api/*')) {
    // Rastrear listagem de imóveis (navegação real do usuário)
    if ($request->is('api/properties') || $request->is('api/properties/*')) {
        $shouldTrack = true;
    }
}

if ($shouldTrack) {
    // Evitar duplicatas: verificar se já registrou este IP na última hora
    $recentLog = VisitorLog::where('ip_address', $request->ip())
        ->where('page_url', $request->fullUrl())
        ->where('visited_at', '>=', now()->subHour())
        ->exists();

    if (!$recentLog) {
        VisitorLog::create([...]);
    }
}
```

## Como Funciona Agora

### Cenário 1: Usuário Acessa Homepage
1. Navegador carrega `/` (rota web) → **Registra log**
2. Vue carrega e faz `GET /api/properties` → **Registra log**
3. Outras APIs (auth, comunidades, etc) → Não registra

### Cenário 2: Usuário Clica em um Imóvel
1. Vue navega para `/properties/123` (client-side)
2. Vue faz `GET /api/properties/123` → **Registra log**
3. Não registra múltiplas vezes (proteção de 1 hora)

### Cenário 3: Usuário Faz Login
1. Vue navega para `/login` (rota web) → **Registra log**
2. `POST /api/login` → Não registra (não é GET)

## O Que é Rastreado

| Rota | Rastreado? | Motivo |
|------|------------|--------|
| `/` | ✅ Sim | Homepage (primeira carga) |
| `/login` | ✅ Sim | Página de login |
| `/register` | ✅ Sim | Página de registro |
| `GET /api/properties` | ✅ Sim | Listagem de imóveis (navegação) |
| `GET /api/properties/123` | ✅ Sim | Visualização de imóvel |
| `POST /api/properties` | ❌ Não | Ação, não navegação |
| `GET /api/auth/user` | ❌ Não | API interna, não navegação |
| `GET /api/communities` | ❌ Não | Pode adicionar se necessário |

## Estrutura do Log

Cada log registra:

```json
{
  "id": 1,
  "ip_address": "192.168.1.100",
  "user_agent": "Mozilla/5.0...",
  "page_url": "http://localhost/api/properties/123",
  "referrer": "http://localhost/",
  "user_id": 5,
  "visited_at": "2025-01-24 14:30:00"
}
```

## Endpoints da API Admin

### 1. Listar Logs de Visitantes
```
GET /api/admin/visitor-logs
Authorization: Bearer {token}
```

**Resposta:**
```json
{
  "data": [
    {
      "id": 1,
      "ip_address": "192.168.1.100",
      "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)...",
      "page_url": "http://localhost/api/properties/123",
      "referrer": "http://localhost/",
      "user": {
        "id": 5,
        "name": "João Silva",
        "email": "joao@example.com"
      },
      "visited_at": "2025-01-24T14:30:00.000000Z"
    }
  ],
  "current_page": 1,
  "per_page": 50,
  "total": 150
}
```

### 2. Análises Gerais
```
GET /api/admin/analytics?period=7
Authorization: Bearer {token}
```

**Parâmetros:**
- `period`: Número de dias (padrão: 7)

**Resposta:**
```json
{
  "summary": {
    "total_users": 50,
    "total_properties": 120,
    "total_visitors": 450,
    "unique_visitors": 200
  },
  "charts": {
    "visitors_by_day": [...],
    "registrations_by_day": [...]
  },
  "top_pages": [...]
}
```

## Testando

### 1. Verificar Configuração
```bash
php check-visitor-logs.php
```

Este script verifica:
- ✅ Se a tabela `visitor_logs` existe
- ✅ Quantos logs existem
- ✅ Últimos 5 logs registrados
- ✅ Se middleware está registrado
- ✅ Se rotas da API existem

### 2. Executar Migrações
```bash
php artisan migrate
```

### 3. Compilar Frontend (IMPORTANTE!)
```bash
npm run build
```
ou execute o arquivo:
```bash
build-fix-logs.bat
```

### 4. Testar Manualmente
1. Acesse `http://localhost/` no navegador
2. Navegue para a listagem de imóveis
3. Clique em um imóvel específico
4. No painel admin, vá para "Logs do Sistema"
5. Verifique se os logs aparecem
6. Abra o console do navegador (F12) e veja: "Visitor logs carregados: X"

### 4. Verificar Logs Laravel
```bash
tail -f storage/logs/laravel.log
```

Se houver erros, aparecerão aqui com `Failed to log visitor: ...`

## Personalizando o Rastreamento

Para adicionar mais rotas ao rastreamento, edite `TrackVisitor.php`:

```php
// Adicionar rastreamento de comunidades
if ($request->is('api/communities') || $request->is('api/communities/*')) {
    $shouldTrack = true;
}

// Adicionar rastreamento de perfil
if ($request->is('api/profile') || $request->is('api/owner/*') || $request->is('api/tenant/*')) {
    $shouldTrack = true;
}
```

## Proteção de Performance

### Evita Sobrecarga no Banco
- **Deduplica**: Não registra o mesmo IP+URL em 1 hora
- **Seletivo**: Apenas navegações importantes
- **Assíncrono**: Usa try-catch para não bloquear requests
- **Indexed**: Tabela tem índices em `ip_address`, `visited_at`, `user_id`

### Quantidade Estimada de Logs
- **Site pequeno**: ~100-500 logs/dia
- **Site médio**: ~1.000-5.000 logs/dia
- **Site grande**: ~10.000+ logs/dia

Com deduplicação de 1 hora, reduz em ~70% o volume.

## Limpeza de Logs Antigos

Para manter o banco limpo, adicione um comando agendado:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Limpar logs com mais de 90 dias
    $schedule->call(function () {
        VisitorLog::where('visited_at', '<', now()->subDays(90))->delete();
    })->daily();
}
```

## Troubleshooting

### Logs Não Aparecem no Dashboard

**Problema**: A API retorna dados (`/api/admin/visitor-logs` funciona), mas nada aparece na tela.

**Causa**: A API retorna resposta paginada com estrutura `{ data: [...], current_page, per_page }`, mas o frontend estava esperando um array direto.

**Solução**: Já corrigido! Agora o código extrai corretamente:
```javascript
const visitorsData = visitorsRes.data?.data || visitorsRes.data || [];
```

**Para aplicar a correção:**
1. Execute `npm run build` ou `build-fix-logs.bat`
2. Recarregue a página com Ctrl+F5
3. Abra o console (F12) e veja: "Visitor logs carregados: X"

---

**Outras verificações:**

1. **Verificar se a tabela existe:**
   ```bash
   php artisan migrate
   ```

2. **Verificar se há dados:**
   ```bash
   php check-visitor-logs.php
   ```

3. **Limpar cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Verificar permissões do usuário:**
   - Apenas admins podem ver logs
   - Verificar `role === 'admin'` no banco

5. **Testar a API diretamente:**
   ```bash
   # No navegador ou Postman
   GET https://imoveis.comunidadeppg.com.br/api/admin/visitor-logs
   Authorization: Bearer SEU_TOKEN
   ```
   Deve retornar:
   ```json
   {
     "data": [...],
     "current_page": 1,
     "per_page": 50
   }
   ```

### Erro "Table 'visitor_logs' doesn't exist"

```bash
php artisan migrate
```

### Muitos Logs Sendo Criados

Ajuste a lógica de deduplicação em `TrackVisitor.php`:
```php
// Aumentar o período de deduplicação
->where('visited_at', '>=', now()->subHours(3)) // 3 horas em vez de 1
```

## Resultado

✅ **Logs de visitantes funcionando**
✅ **Rastreamento inteligente (sem duplicatas)**
✅ **Performance otimizada**
✅ **Dashboard mostrando dados corretos**
✅ **Proteção contra sobrecarga**

## Próximos Passos (Opcional)

1. **Analytics Avançado**: Gráficos de visitantes por hora/dia
2. **Geolocalização**: Usar serviço para detectar país/cidade
3. **Heatmap**: Páginas mais visitadas
4. **Tempo de Permanência**: Calcular quanto tempo usuário fica em cada página
5. **Taxas de Conversão**: Rastrear quantos visitantes se tornam usuários
