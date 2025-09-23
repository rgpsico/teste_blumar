# Beach API

API REST desenvolvida em Laravel para gerenciamento de Beach Houses da Blumar.

## Requisitos

-   PHP >= 8.1
-   Composer
-   MySQL/PostgreSQL
-   Node.js e NPM (opcional, para assets)

## Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/rgpsico/teste_blumar
cd beach_api
```

### 2. Instale as dependências do Composer

```bash
composer install
```

### 3. Configure o arquivo de ambiente

Copie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

Configure as variáveis de ambiente no arquivo `.env`:

```env
APP_NAME=BeachAPI
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=beach_houses
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 5. Configure o banco de dados

Crie o banco de dados `beach_houses` no seu SGBD.

Execute as migrations para criar as tabelas:

```bash
php artisan migrate
```

### 6. Inicie o servidor de desenvolvimento

```bash
php artisan serve
```

A API estará disponível em `http://localhost:8000`

## Endpoints da API

### Listar todas as Beach Houses

```
GET /api/beach-houses
```

**Resposta de exemplo:**

```json
{
    "data": [
        {
            "id": 1,
            "nome": "Casa da Praia",
            "cidade": "Florianópolis",
            "descritivo": "Linda casa de praia com vista para o mar",
            "created_at": "2024-01-01T12:00:00.000000Z",
            "updated_at": "2024-01-01T12:00:00.000000Z"
        }
    ]
}
```

### Criar nova Beach House

```
POST /api/beach-houses
```

**Body (JSON):**

```json
{
    "nome": "Casa da Praia",
    "cidade": "Florianópolis",
    "descritivo": "Linda casa de praia com vista para o mar"
}
```

**Resposta de sucesso:**

```json
{
    "message": "Beach house created successfully",
    "data": {
        "id": 1,
        "nome": "Casa da Praia",
        "cidade": "Florianópolis",
        "descritivo": "Linda casa de praia com vista para o mar",
        "created_at": "2024-01-01T12:00:00.000000Z",
        "updated_at": "2024-01-01T12:00:00.000000Z"
    }
}
```

**Resposta de erro de validação:**

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "nome": ["O campo nome é obrigatório."],
        "cidade": ["O campo cidade é obrigatório."],
        "descritivo": ["O campo descritivo é obrigatório."]
    }
}
```

## Estrutura do Projeto

```
beach_api/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── BeachHouseController.php
│   └── Models/
│       └── BeachHouse.php
├── database/
│   └── migrations/
│       └── xxxx_xx_xx_create_beach_houses_table.php
├── routes/
│   └── api.php
├── .env.example
├── composer.json
└── README.md
```

## Testando a API

### Usando cURL

**Listar beach houses:**

```bash
curl -X GET http://localhost:8000/api/beach-houses \
  -H "Accept: application/json"
```

**Criar nova beach house:**

```bash
curl -X POST http://localhost:8000/api/beach-houses \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nome": "Casa Teste",
    "cidade": "São Paulo",
    "descritivo": "Casa para testes"
  }'
```

### Usando Postman

1. Importe a collection disponível em `postman_collection.json` (se disponível)
2. Configure a variável de ambiente `base_url` para `http://localhost:8000`

## Comandos Úteis

**Limpar cache:**

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

**Executar testes (se implementados):**

```bash
php artisan test
```

**Ver rotas disponíveis:**

```bash
php artisan route:list
```

## Tecnologias Utilizadas

-   Laravel 10.x
-   PHP 8.1+
-   MySQL/PostgreSQL
-   Eloquent ORM

## Autor

[Seu Nome]

## Link de Demonstração

[Inserir link da API em produção/demo aqui]

---

**Observações:**

-   Certifique-se de que o PHP e Composer estão instalados e configurados
-   Para ambiente de produção, configure adequadamente as variáveis de ambiente
-   Mantenha o arquivo `.env` seguro e nunca o versione
