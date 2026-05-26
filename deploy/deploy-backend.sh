#!/bin/bash

echo "=========================================="
echo "DecorArte RH - Despliegue Backend"
echo "=========================================="
echo ""

# Variables
APP_NAME="decorarte-rh"
BACKEND_DIR="$(cd "$(dirname "$0")/.." && pwd)/backend"

echo "📁 Directorio: $BACKEND_DIR"
cd $BACKEND_DIR

# Verificar PHP
echo "🔍 Verificando PHP..."
php -v || { echo "❌ PHP no instalado"; exit 1; }

# Verificar Composer
echo "🔍 Verificando Composer..."
composer --version || { echo "❌ Composer no instalado"; exit 1; }

# Instalar dependencias
echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader --no-interaction

# Configurar entorno
echo "📄 Configurando entorno..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Migraciones
echo "🏗️ Ejecutando migraciones..."
php artisan migrate --force

# Seeders (solo primera vez)
echo "🌱 Verificando seeders..."
php artisan db:seed --force || true

# Optimizar
echo "⚡ Optimizando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permisos
echo "🔐 Configurando permisos..."
chmod -R 775 storage bootstrap/cache

# Iniciar servidor (para desarrollo)
echo ""
echo "🚀 Backend listo!"
echo "Iniciando servidor en http://localhost:8000"
echo ""
php artisan serve --host=0.0.0.0 --port=8000
