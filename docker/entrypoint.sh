#!/bin/bash
set -e

echo "⏳ Жду, пока база данных будет доступна..."
until mysqladmin ping -h"${DB_HOST:-db}" --silent; do
  sleep 2
done
echo "✅ База данных доступна."

cd /var/www/html

# Если Laravel не установлен, только запускаем Apache
if [ ! -f artisan ]; then
  echo "ℹ️ Laravel-проект не найден (нет artisan)."
  echo "   Создай проект командой:"
  echo "   docker compose exec app bash -lc \"composer create-project laravel/laravel . \\\"^12.0\\\"\""
  echo "🚀 Запуск Apache..."
  exec apache2-foreground
fi

# .env (если отсутствует)
if [ ! -f .env ] && [ -f .env.example ]; then
  echo "📝 Копирую .env из .env.example"
  cp .env.example .env
fi

if [ ! -f vendor/autoload.php ]; then
  echo "📦 Устанавливаю зависимости Composer..."
  composer install --no-interaction
else
  echo "✅ Composer зависимости уже установлены, пропускаю"
fi

# APP_KEY (только если пустой/отсутствует)
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
  echo "🔑 Генерирую APP_KEY..."
  php artisan key:generate --force
fi

echo "🧱 Готовлю кеш/права..."
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Создаем storage link (если отсутствует)
if [ ! -L public/storage ]; then
  echo "🔗 Создаю storage:link..."
  php artisan storage:link || true
fi

echo "🛠️ Выполняю миграции..."
php artisan migrate --force || true
php artisan db:seed --force || true

echo "🚀 Запуск Apache..."
exec apache2-foreground