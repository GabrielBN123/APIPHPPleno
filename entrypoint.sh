#!/bin/sh

# Sai imediatamente em caso de erro
set -e

echo "🏗  Inicializando o ambiente..."

# Garante que o .env existe, mas sem sobrescrever se já foi criado
if [ ! -f ".env" ]; then
    echo "⚠️  Arquivo .env não encontrado. Criando um novo..."
    cp .env.example .env
fi

# Aguarda o banco de dados estar pronto antes de rodar as migrações
echo "⏳ Aguardando conexão com o banco de dados..."
while ! pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" >/dev/null 2>&1; do
    sleep 2
done
echo "✅ Banco de dados pronto!"

# Gera a chave da aplicação, caso não exista
if [ ! -f "storage/oauth-private.key" ]; then
    echo "🔑 Gerando chave da aplicação..."
    php artisan key:generate
fi

# Verifica se o banco já tem tabelas
if [ $(php artisan migrate:status | grep -c 'Yes') -eq 0 ]; then
    echo "⚠️ Nenhuma migração detectada, executando migrações..."
    php artisan migrate --force
else
    echo "✅ Migrações já aplicadas."
fi

# Define permissões corretas (mais seguras que 777)
echo "📂 Ajustando permissões..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "🚀 Inicialização completa! Executando comando do container..."
# exec "$@"
