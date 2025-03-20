FROM php:8.2-fpm

# Atualiza pacotes e instala dependências
RUN apt-get update -y \
    && apt-get install -y \
    postgresql-client \
    openssl \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring \
    && rm -rf /var/lib/apt/lists/*  # Limpar cache após a instalação

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Define diretório de trabalho
WORKDIR /app

# Copia os arquivos do projeto
COPY . .

# Instala dependências do Laravel
RUN composer install --no-dev --prefer-dist --no-progress --no-suggest

# Expõe a porta do Laravel
EXPOSE 8181

# Copia o entrypoint para dentro do container
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Define o entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Comando para iniciar o Laravel
CMD php artisan serve --host=0.0.0.0 --port=8181
