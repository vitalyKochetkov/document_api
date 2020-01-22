# docker containers

```
git clone git@github.com:vitalyKochetkov/document_api.git

cd document_api

cd docker

docker-compose up
```

## Compose

### PHP (PHP-FPM)

Composer is included

```
docker-compose run php-fpm composer 
```

To run fixtures

```
docker-compose run php-fpm bin/console doctrine:fixtures:load
```

