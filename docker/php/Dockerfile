# PHPのバージョンを固定
FROM php:8.2.27-fpm

# php.iniの設定ファイルをコピー
COPY php.ini /usr/local/etc/php/

# 必要なパッケージをインストールし、GDやPDOの拡張を有効にする
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composerのインストールと更新
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer self-update

# 作業ディレクトリを指定
WORKDIR /var/www
