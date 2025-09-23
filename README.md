Beach API

API REST desenvolvida em Laravel para gerenciamento de Beach Houses da Blumar.

Requisitos

PHP >= 8.1

Composer

MySQL/PostgreSQL

Node.js e NPM (opcional, para assets)

Instalação

1. Clone o repositório
   git clone https://github.com/rgpsico/teste_blumar
   cd beach_api

2. Instale as dependências do Composer
   composer install

3. Configure o arquivo de ambiente

Copie o arquivo .env.example para .env:

cp .env.example .env

Configure as variáveis de ambiente no arquivo .env:

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

4. Gere a chave da aplicação
   php artisan key:generate

5. Configure o banco de dados

Crie o banco de dados beach_houses no seu SGBD.

Execute as migrations para criar as tabelas:

php artisan migrate

6. Inicie o servidor de desenvolvimento
   php artisan serve

A API estará disponível em http://localhost:8000.

Frontend

O frontend está disponível no arquivo index.html na raiz do projeto Laravel.
Para visualizar, abra o arquivo index.html diretamente no navegador ou configure seu servidor web para servir a raiz do projeto.

Endpoints da API
Listar todas as Beach Houses
GET /api/beach-houses

Resposta de exemplo:

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

Criar nova Beach House
POST /api/beach-houses

Body (JSON):

{
"nome": "Casa da Praia",
"cidade": "Florianópolis",
"descritivo": "Linda casa de praia com vista para o mar"
}

Resposta de sucesso:

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

Resposta de erro de validação:

{
"message": "The given data was invalid.",
"errors": {
"nome": ["O campo nome é obrigatório."],
"cidade": ["O campo cidade é obrigatório."],
"descritivo": ["O campo descritivo é obrigatório."]
}
}

Estrutura do Projeto
beach_api/
├── app/
│ ├── Http/
│ │ └── Controllers/
│ │ ├── AuthController.php
│ │ ├── AuthControllerApi.php
│ │ ├── BeachHouseController.php
│ │ ├── ImovelController.php
│ │ └── CepController.php
│ └── Models/
│ ├── User.php
│ ├── UserAddress.php
│ ├── Imovel.php
│ ├── ImovelEndereco.php
│ └── ImovelFoto.php
├── database/
│ └── migrations/
│ └── xxxx_xx_xx_create_imoveis_table.php
├── routes/
│ └── api.php
├── index.html <-- Frontend (single page)
├── .env.example
├── composer.json
└── README.md

Testando a API
Usando cURL

Listar beach houses:

curl -X GET http://localhost:8000/api/imoveis \
 -H "Accept: application/json"

Criar nova beach house:

curl -X POST http://localhost:8000/api/imoveis \
 -H "Content-Type: application/json" \
 -H "Accept: application/json" \
 -d '{
"nome": "Casa Teste",
"cidade": "São Paulo",
"descritivo": "Casa para testes"
}'

Usando Postman

Importe a collection disponível em postman_collection.json (se houver).

Configure a variável de ambiente base_url para http://localhost:8000.

Comandos Úteis

Limpar cache:

php artisan cache:clear
php artisan config:clear
php artisan route:clear

Executar testes (se implementados):

php artisan test

Ver rotas disponíveis:

php artisan route:list

Tecnologias Utilizadas

Laravel 10.x

PHP 8.1+

MySQL/PostgreSQL

Eloquent ORM

Autor

[Seu Nome]

Link de Demonstração
