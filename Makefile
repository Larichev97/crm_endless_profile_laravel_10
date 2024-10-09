# Запуск Docker Desktop через пользовательский bash-скрипт (для Ubuntu 24.4):
run:
	docker_run

# Построение контейнеров для проекта + их запуск:
build-containers:
	docker compose build --no-cache
	@make docker-up

# Подготовка проекта к работе:
build-project:
	cp .env.example .env
	docker compose exec -u root -t -i php bash -c "composer install"
	docker compose exec -u root -t -i php bash -c "php artisan key:generate"
	docker compose exec -u root -t -i php bash -c "sudo chmod -R 777 bootstrap/cache"
	docker compose exec -u root -t -i php bash -c "sudo chmod -R 777 storage/framework"
	docker compose exec -u root -t -i php bash -c "sudo chmod -R 777 storage/logs"
	docker compose exec -u root -t -i php bash -c "php artisan storage:link"
	docker compose exec php bash -c "npm i"
	docker compose exec php bash -c "npm run build"

# Заполнение БД таблицами из миграций + формирование тестовых данных и QR-кодов:
build-database:
	docker compose exec -u root -t -i php bash -c "php artisan migrate:fresh --seed"
	docker compose exec -u root -t -i php bash -c "php artisan qrs:generate"

docker-up:
	docker compose up -d

# Создание dev-сборки CSS и JS файлов:
npm-dev:
	docker compose exec php bash -c "npm run dev"

# Создание prod-сборки CSS и JS файлов (минимизация файлов):
npm-prod:
	docker compose exec php bash -c "npm run prod"

# Переход в PHP-контейнер (в нём выполняются все команды в проекте):
php:
	docker compose exec -u root -t -i php bash

# Переход в MYSQL-контейнер (в нём можно импортировать или экспортировать dump БД "endless_profile"):
mysql:
	docker compose exec mysql bash -c 'mysql -u root -proot endless_profile'

