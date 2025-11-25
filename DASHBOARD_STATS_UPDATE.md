# 📊 Atualização: Dashboard com Estatísticas de Visitantes e Registros

## ✨ Novo Dashboard Administrativo

O dashboard admin agora exibe estatísticas detalhadas de visitantes e registros de usuários em cards visuais modernos.

## 🎯 Novas Estatísticas Adicionadas

### Primeira Linha de Cards

1. **Total de Visitantes**
   - Total de visitas registradas (últimos 30 dias)
   - Subtítulo: Número de visitantes únicos
   - Ícone: Olho (azul)

2. **Visitantes Hoje**
   - Total de visitas hoje
   - Subtítulo: Visitantes únicos hoje
   - Ícone: Calendário (verde)

3. **Proprietários**
   - Total de proprietários cadastrados
   - Subtítulo: Novos proprietários este mês
   - Ícone: Usuário (roxo)

4. **Inquilinos**
   - Total de inquilinos cadastrados
   - Subtítulo: Novos inquilinos este mês
   - Ícone: Grupo de usuários (laranja)

### Segunda Linha de Cards

5. **Total de Imóveis** (índigo)
6. **Imóveis Alugados** (teal)
7. **Comunidades** (rosa)
8. **Total de Usuários** (cinza)

## 📊 Dados Exibidos

```javascript
{
  // Visitantes
  totalVisitors: 450,        // Total de visitas (30 dias)
  uniqueVisitors: 200,       // Visitantes únicos (30 dias)
  visitorsToday: 45,         // Visitas hoje
  uniqueVisitorsToday: 25,   // Visitantes únicos hoje

  // Usuários por tipo
  owners: 35,                // Total de proprietários
  tenants: 58,               // Total de inquilinos
  ownersThisMonth: 5,        // Proprietários este mês
  tenantsThisMonth: 8,       // Inquilinos este mês

  // Outros
  properties: 120,           // Total de imóveis
  rented: 45,                // Imóveis alugados
  communities: 12,           // Total de comunidades
  users: 95                  // Total de usuários
}
```

## 🔄 Fontes de Dados

### API de Analytics (`/api/admin/analytics`)

Já existente no sistema. Retorna:

```json
{
  "summary": {
    "total_users": 95,
    "total_properties": 120,
    "total_visitors": 450,
    "unique_visitors": 200,
    "new_registrations_count": 13
  },
  "new_registrations": [...],
  "charts": {
    "registrations_by_day": [...],
    "visitors_by_day": [...],
    "registrations_by_type": [...]
  },
  "top_pages": [...]
}
```

**Parâmetros:**
- `?period=30` - Últimos 30 dias (padrão: 7)
- `?period=1` - Apenas hoje

### Outras APIs Utilizadas

- `GET /api/users` - Lista de todos os usuários
- `GET /api/properties` - Lista de imóveis
- `GET /api/communities` - Lista de comunidades

## 💻 Implementação Técnica

### Componente StatsCard Melhorado

Agora aceita um `subtitle` opcional:

```vue
<StatsCard
  title="Total de Visitantes"
  :value="stats.totalVisitors"
  icon="M15 12a3 3 0 11-6 0 3 3 0 016 0z..."
  color="blue"
  :subtitle="`${stats.uniqueVisitors || 0} únicos`"
/>
```

### Estrutura do Card

```
┌─────────────────────────────┐
│ Total de Visitantes    [👁️] │
│ 450                         │
│ 200 únicos                  │
└─────────────────────────────┘
```

### Função loadDashboardData

Carrega dados de múltiplas APIs em paralelo:

```javascript
const loadDashboardData = async () => {
  try {
    // Buscar dados em paralelo
    const [usersRes, propertiesRes, communitiesRes, analyticsRes] =
      await Promise.all([
        axios.get('/api/users'),
        axios.get('/api/properties'),
        axios.get('/api/communities'),
        axios.get('/api/admin/analytics?period=30')
      ]);

    // Processar dados gerais
    stats.value.users = allUsers.length;
    stats.value.properties = allProperties.length;
    stats.value.rented = allProperties.filter(p => p.status === 'rented').length;
    stats.value.communities = allCommunities.length;

    // Processar por tipo de usuário
    stats.value.owners = allUsers.filter(u => u.role === 'owner').length;
    stats.value.tenants = allUsers.filter(u => u.role === 'tenant').length;

    // Calcular registros deste mês
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    stats.value.ownersThisMonth = allUsers.filter(u =>
      u.role === 'owner' && new Date(u.created_at) >= firstDayOfMonth
    ).length;

    // Extrair dados de analytics
    stats.value.totalVisitors = analyticsRes.data.summary.total_visitors;
    stats.value.uniqueVisitors = analyticsRes.data.summary.unique_visitors;

    // Buscar dados de hoje separadamente
    const todayAnalyticsRes = await axios.get('/api/admin/analytics?period=1');
    stats.value.visitorsToday = todayAnalyticsRes.data.summary.total_visitors;
  } catch (error) {
    // Garantir valores padrão em caso de erro
  }
};
```

## 🎨 Cores dos Cards

| Estatística | Cor | Gradiente |
|-------------|-----|-----------|
| Total de Visitantes | Azul | `from-blue-500 to-blue-600` |
| Visitantes Hoje | Verde | `from-green-500 to-green-600` |
| Proprietários | Roxo | `from-purple-500 to-purple-600` |
| Inquilinos | Laranja | `from-orange-500 to-orange-600` |
| Total de Imóveis | Índigo | `from-indigo-500 to-indigo-600` |
| Imóveis Alugados | Teal | `from-teal-500 to-teal-600` |
| Comunidades | Rosa | `from-pink-500 to-pink-600` |
| Total de Usuários | Cinza | `from-gray-500 to-gray-600` |

## 🚀 Como Testar

### 1. Compilar Frontend
```bash
npm run build
```

ou

```bash
build-fix-logs.bat
```

### 2. Acessar Dashboard
1. Faça login como admin
2. Vá para o painel administrativo
3. A página inicial (Dashboard) mostrará todas as estatísticas

### 3. Verificar Console
Abra o console do navegador (F12) e veja:
```
Visitor logs carregados: 5
Dashboard analytics loaded
```

### 4. Teste de Dados

**Visitantes:**
- Navegue pelo site público
- Visite páginas de imóveis
- Aguarde alguns minutos
- Recarregue o dashboard admin
- Os números devem aumentar

**Registros:**
- Crie novos usuários (proprietários/inquilinos)
- Os contadores devem atualizar imediatamente
- "Este mês" mostra apenas registros do mês atual

## 📱 Responsividade

O dashboard é totalmente responsivo:

- **Desktop (lg+)**: 4 cards por linha
- **Tablet (md)**: 2 cards por linha
- **Mobile**: 1 card por linha (stack vertical)

```css
grid-cols-1 md:grid-cols-2 lg:grid-cols-4
```

## 🔍 Troubleshooting

### Cards Mostram "0"

**Causa 1**: Ainda não há dados no banco
**Solução**: Navegue pelo site para gerar visitantes, crie usuários

**Causa 2**: API analytics não está retornando dados
**Solução**: Verifique no console:
```javascript
// No console do navegador
axios.get('/api/admin/analytics?period=30')
  .then(r => console.log(r.data))
```

**Causa 3**: Erro 403 Forbidden
**Solução**: Verifique se o usuário é admin:
```sql
SELECT id, name, email, role FROM users WHERE email = 'seu@email.com';
UPDATE users SET role = 'admin' WHERE email = 'seu@email.com';
```

### "Visitantes Hoje" Sempre Zero

**Causa**: Não houve visitas hoje ainda
**Solução**:
1. Abra o site em janela anônima
2. Navegue por algumas páginas
3. Aguarde 1-2 minutos
4. Recarregue o dashboard

### Subtítulos Não Aparecem

**Causa**: Assets não foram recompilados
**Solução**:
```bash
npm run build
# Limpe cache do navegador: Ctrl+F5
```

### Erro ao Carregar Dashboard

**Verificar logs do Laravel:**
```bash
tail -f storage/logs/laravel.log
```

**Verificar console do navegador:**
- Veja se há erros 500, 403, 404
- Verifique se todas as APIs respondem corretamente

## 📈 Melhorias Futuras (Opcional)

### 1. Gráficos de Tendências
```javascript
// Adicionar biblioteca de gráficos (Chart.js, ApexCharts)
import { Line } from 'vue-chartjs';

// Gráfico de visitantes por dia (últimos 7 dias)
const visitorChart = {
  labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
  data: [120, 150, 180, 165, 200, 190, 220]
};
```

### 2. Comparação com Período Anterior
```javascript
// Ex: "↑ 15% vs mês passado"
const comparison = {
  visitorsThisMonth: 450,
  visitorsLastMonth: 390,
  change: +15 // porcentagem
};
```

### 3. Taxa de Conversão
```javascript
// Visitantes → Registros
const conversionRate = (stats.tenantsThisMonth + stats.ownersThisMonth)
  / stats.visitorsToday * 100;
// Ex: "2.5% de conversão"
```

### 4. Top Páginas Visitadas
```javascript
// Usar dados de analytics.top_pages
const topPages = [
  { url: '/properties/123', views: 45 },
  { url: '/properties/456', views: 38 },
  { url: '/', views: 32 }
];
```

### 5. Mapa de Visitantes
```javascript
// Usar country/city do VisitorLog
// Exibir mapa interativo com número de visitantes por região
```

## ✅ Resultado Final

O dashboard admin agora exibe:

- ✅ **8 cards de estatísticas** bem organizados
- ✅ **Visitantes totais e únicos** (30 dias)
- ✅ **Visitantes de hoje** em tempo real
- ✅ **Proprietários e inquilinos** totais
- ✅ **Novos registros deste mês**
- ✅ **Estatísticas de imóveis e comunidades**
- ✅ **Design moderno e responsivo**
- ✅ **Subtítulos informativos**
- ✅ **Cores diferenciadas por categoria**
- ✅ **Carregamento paralelo otimizado**
- ✅ **Error handling robusto**

## 📄 Arquivos Modificados

1. **`resources/js/views/Admin/Dashboard.vue`**
   - Adicionados 4 novos cards de estatísticas
   - Componente StatsCard aceita `subtitle`
   - Função `loadDashboardData` busca analytics
   - Cálculo de registros por mês
   - Error handling melhorado

2. **`DASHBOARD_STATS_UPDATE.md`** (este arquivo)
   - Documentação completa das mudanças

---

**Data da atualização**: 25/01/2025
**Status**: ✅ Implementado
**Build necessário**: ✅ Sim (`npm run build`)
