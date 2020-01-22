FROM php:fpm-alpine

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

RUN apk add --no-cache git libpng libpng-dev bash icu-dev poppler-utils && docker-php-ext-install gd && apk del libpng-dev 

RUN docker-php-ext-configure intl 
RUN docker-php-ext-install intl

RUN docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD composer install; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm 

EXPOSE 9000
