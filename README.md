### Description

This is a template application for creating REST API's based on Symfony framework

* Symfony 6.1
* SwaggerUI

### Installation

#### Create project

```
composer create-project zim32/symfony-skeleton-rest-only folder_name '6.1.*'
composer update
```

#### Generate JWT keys
See official bundle docs: https://github.com/lexik/LexikJWTAuthenticationBundle

#### Add this to your .env.local file (change for your needs)

```
APP_ENV=dev
DATABASE_URL=YOUT_DB_DSN
API_SCHEMA_URL=http://your-domain/api/v1/doc/schema
API_ENDPOINT_URL=http://your-domain/api/v1
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
```

### Usage

There are some test entities and resources in this skeleton project, so you can nagivate to
http://yout-domain/api/v1/doc/ and start playing