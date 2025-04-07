# API PHP - Laravel + Docker + PostgreSQL + Min.io

## Dados da Inscrição

**Número:** 8800  
**Desenvolvedor:** GABRIEL BATISTA DA SILVA NOGUEIRA

## Tecnologias Utilizadas

- Laravel
- Docker
- PostgreSQL
- Min.io
- Sanctum para autenticação

## Instalação e Execução

### Clonar o repositório

```sh
https://github.com/GabrielBN123/APIPHPPleno.git
```

### Acessar a página do projeto

```sh
cd APIPHP
```

### Executar os comandos de instalação

```sh
composer install
php artisan storage:link
php artisan l5-swagger:generate
```

### Iniciar o Docker

```sh
docker-compose up --build
```

Aguardar até o sistema exibir:

```sh
INFO  Server running on [http://0.0.0.0:8181]
```

### Acessar o Swagger para testar a API

[http://localhost:8181/api/documentation](http://localhost:8181/api/documentation)

Para testar a API:
- Acesse o endpoint de login e copie o token gerado.
- O token é válido por 5 minutos.

## Rotas da API

### Autenticação
- `POST /login` - Gera um token de autenticação.
- `POST /logout` - Revoga o token de autenticação.
- `POST /refresh` - Renova o token de autenticação.

### Cidades
- `GET /cidade` - Lista todas as cidades.
- `GET /show-cidade/{cid_id}` - Exibe uma cidade específica.
- `POST /store-cidade` - Cria uma nova cidade.
- `PUT /update-cidade/{cid_id}` - Atualiza uma cidade.

### Endereços
- `GET /endereco` - Lista todos os endereços.
- `GET /show-endereco/{end_id}` - Exibe um endereço específico.
- `POST /store-endereco` - Cria um novo endereço.
- `PUT /update-endereco/{end_id}` - Atualiza um endereço.
- `DELETE /delete-endereco/{end_id}` - Remove um endereço.

### Fotos de Pessoas
- `GET /foto-pessoa` - Lista todas as fotos.
- `GET /show-foto-pessoa/{pes_id}` - Exibe uma foto específica.
- `POST /store-foto-pessoa/{pes_id}` - Adiciona uma nova foto.
- `PUT /update-foto-pessoa/{pes_id}` - Atualiza uma foto.
- `DELETE /delete-foto-pessoa/{pes_id}` - Remove uma foto.

### Lotação
- `GET /lotacao` - Lista todas as lotações.
- `GET /show-lotacao/{lot_id}` - Exibe uma lotação específica.
- `POST /store-lotacao` - Cria uma nova lotação.
- `PUT /update-lotacao/{lot_id}` - Atualiza uma lotação.
- `DELETE /delete-lotacao/{lot_id}` - Remove uma lotação.

### Pessoas
- `GET /pessoa` - Lista todas as pessoas.
- `GET /show-pessoa/{pes_id}` - Exibe uma pessoa específica.
- `POST /store-pessoa` - Adiciona uma nova pessoa.
- `PUT /update-pessoa/{pes_id}` - Atualiza uma pessoa.
- `DELETE /delete-pessoa/{pes_id}` - Remove uma pessoa.

### Pessoa-Endereço
- `GET /pessoa-endereco` - Lista todas as associações de pessoa e endereço.
- `GET /show-pessoa-endereco/{pes_id}` - Exibe uma associação específica.
- `POST /store-pessoa-endereco` - Cria uma nova associação.
- `PUT /update-pessoa-endereco/{pes_id}` - Atualiza uma associação.
- `DELETE /delete-pessoa-endereco/{pes_id}` - Remove uma associação.

### Servidores Efetivos
- `GET /servidor-efetivo` - Lista todos os servidores efetivos.
- `GET /show-servidor-efetivo/{pes_id}` - Exibe um servidor efetivo específico.
- `POST /store-cadastro-servidor-efetivo` - Cadastra um novo servidor efetivo.
- `POST /store-servidor-efetivo` - Cria um novo servidor efetivo.
- `PUT /update-servidor-efetivo/{pes_id}` - Atualiza um servidor efetivo.
- `DELETE /delete-servidor-efetivo/{pes_id}` - Remove um servidor efetivo.

### Servidores Temporários
- `GET /servidor-temporario` - Lista todos os servidores temporários.
- `GET /show-servidor-temporario/{pes_id}` - Exibe um servidor temporário específico.
- `POST /store-servidor-temporario` - Cria um novo servidor temporário.
- `PUT /update-servidor-temporario/{pes_id}` - Atualiza um servidor temporário.
- `DELETE /delete-servidor-temporario/{pes_id}` - Remove um servidor temporário.

### Unidades
- `GET /unidade` - Lista todas as unidades.
- `GET /show-unidade/{unidade_id}` - Exibe uma unidade específica.
- `POST /store-unidade` - Cria uma nova unidade.
- `PUT /update-unidade/{unidade_id}` - Atualiza uma unidade.
- `DELETE /delete-unidade/{unidade_id}` - Remove uma unidade.

### Unidade-Endereço
- `GET /unidade-endereco` - Lista todas as associações de unidade e endereço.
- `GET /show-unidade-endereco/{unid_id}` - Exibe uma associação específica.
- `POST /store-unidade-endereco` - Cria uma nova associação.
- `PUT /update-unidade-endereco/{unid_id}` - Atualiza uma associação.
- `DELETE /delete-unidade-endereco/{unid_id}` - Remove uma associação.

