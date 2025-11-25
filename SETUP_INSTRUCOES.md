# 🚀 Instruções de Setup - Novas Funcionalidades

## ✅ O que foi implementado

### 1. **Sistema de Log de Visitantes**
- Registra automaticamente cada acesso ao sistema
- Captura: IP, navegador, página visitada, referrer
- Middleware automático em todas as rotas web

### 2. **Sistema de Log de Registros**
- Registra automaticamente cada novo usuário
- Captura: tipo de usuário, IP, data/hora
- Integrado no AuthController

### 3. **Tipo de Transação em Imóveis**
- Agora imóveis podem ser para: Aluguel, Venda ou Ambos
- Campo `transaction_type` adicionado à tabela properties

### 4. **Sistema de Mensagens/Perguntas**
- Visitantes podem enviar perguntas sobre imóveis
- Proprietários recebem notificações
- Sistema de leitura/não lida

### 5. **Painel de Analytics (Admin)**
- Estatísticas de visitantes
- Novos registros
- Gráficos de crescimento
- Páginas mais acessadas

## 📋 Passo a Passo para Ativar

### 1. Rodar as Migrations

Execute no terminal (dentro da pasta do projeto):

```bash
php artisan migrate
```

Isso criará as seguintes tabelas:
- `visitor_logs` - Logs de visitantes
- `registration_logs` - Logs de registros
- `property_messages` - Mensagens/perguntas sobre imóveis
- Adiciona coluna `transaction_type` em `properties`

### 2. Verificar se está funcionando

Após rodar as migrations, teste:

```bash
# Verificar tabelas criadas
php artisan db:show

# Verificar se middleware está ativo
php artisan route:list | grep web
```

### 3. Testar o Sistema

1. **Teste de Visitantes**:
   - Acesse qualquer página do site
   - Verifique no banco: `SELECT * FROM visitor_logs ORDER BY id DESC LIMIT 10;`

2. **Teste de Registro**:
   - Crie um novo usuário
   - Verifique no banco: `SELECT * FROM registration_logs ORDER BY id DESC LIMIT 10;`

3. **Teste de Mensagens**:
   - Envie uma mensagem de um imóvel
   - Verifique no banco: `SELECT * FROM property_messages ORDER BY id DESC LIMIT 10;`

## 🎨 Frontend - Próximos Passos

Agora vou criar as telas Vue para visualizar essas informações:

### Para o Admin Dashboard:
- [ ] Painel de Analytics com gráficos
- [ ] Lista de visitantes recentes
- [ ] Lista de novos registros
- [ ] Estatísticas em tempo real

### Para o Owner Dashboard:
- [ ] Tela de mensagens recebidas
- [ ] Notificação de novas perguntas
- [ ] Marcar como lida/responder

### Para os Formulários:
- [ ] Adicionar campo "Tipo de Transação" no cadastro de imóveis
- [ ] Filtro por tipo de transação na listagem
- [ ] Modal de contato/pergunta nos detalhes do imóvel

## 🔧 APIs Disponíveis

### Analytics (Admin apenas)
```
GET /api/admin/analytics?period=7
GET /api/admin/visitor-logs
GET /api/admin/registration-logs
```

### Mensagens (Owner)
```
GET /api/owner/messages
PUT /api/messages/{id}/read
PUT /api/messages/read-all
DELETE /api/messages/{id}
```

### Mensagens (Público)
```
POST /api/properties/{propertyId}/messages
Body: {
  "visitor_name": "Nome",
  "visitor_email": "email@exemplo.com",
  "visitor_phone": "11999999999",
  "message": "Tenho interesse..."
}
```

## ⚠️ Importante

- As migrations adicionam índices para performance
- O middleware de visitantes NÃO bloqueia o acesso se falhar
- Logs são salvos de forma assíncrona para não afetar performance
- Analytics calcula baseado em período configurável (padrão: 7 dias)

## 📊 Estrutura das Tabelas

### visitor_logs
- id, ip_address, user_agent, page_url, referrer
- country, city, user_id, visited_at
- Índices em: ip_address, visited_at, user_id

### registration_logs
- id, user_id, ip_address, user_agent
- user_type, registered_at
- Índices em: user_id, registered_at, user_type

### property_messages
- id, property_id, visitor_name, visitor_email
- visitor_phone, message, ip_address
- read, read_at, created_at
- Índices em: property_id, read, created_at

### properties (nova coluna)
- transaction_type: ENUM('rent', 'sale', 'both')
- Padrão: 'rent'

---

**Próximo passo**: Vou criar as interfaces Vue para visualizar todos esses dados! 🚀
