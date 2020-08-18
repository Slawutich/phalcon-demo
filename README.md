# Phalcon demo project

#### Run
In the docker-compose.yml file, specify the correct database environment variables

and run

```bash
npm i
npm run build
composer install --no-dev
docker-compose up --force-recreate --build -V
```

after starting create a table of users, it is described in the migration