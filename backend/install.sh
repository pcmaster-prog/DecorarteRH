#!/bin/bash

echo "=========================================="
echo "DecorArte RH Operativo - Instalación"
echo "=========================================="
echo ""

# Verificar PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP no está instalado. Por favor instala PHP 8.2+"
    exit 1
fi

PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
echo "✅ PHP versión: $PHP_VERSION"

# Verificar Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer no está instalado. Por favor instala Composer"
    exit 1
fi
echo "✅ Composer instalado"

# Verificar PostgreSQL
if ! command -v psql &> /dev/null; then
    echo "⚠️ PostgreSQL no está instalado. Necesitas PostgreSQL 14+"
    echo "   Ubuntu/Debian: sudo apt install postgresql postgresql-contrib"
    echo "   macOS: brew install postgresql"
fi

echo ""
echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader

echo ""
echo "📄 Copiando archivo de entorno..."
cp .env.example .env

echo ""
echo "🔑 Generando clave de aplicación..."
php artisan key:generate

echo ""
echo "🗄️ Configurando base de datos..."
echo "Por favor edita el archivo .env con tus credenciales de PostgreSQL:"
echo "   DB_DATABASE=decorarte_rh"
echo "   DB_USERNAME=postgres"
echo "   DB_PASSWORD=tu_password"
echo ""
read -p "¿Ya configuraste el archivo .env? (s/n): " confirm

if [ "$confirm" != "s" ]; then
    echo "⚠️ Por favor configura el archivo .env y vuelve a ejecutar:"
    echo "   php artisan migrate --seed"
    exit 0
fi

echo ""
echo "🏗️ Ejecutando migraciones..."
php artisan migrate

echo ""
echo "🌱 Ejecutando seeders..."
php artisan db:seed

echo ""
echo "🔗 Generando enlace simbólico para storage..."
php artisan storage:link

echo ""
echo "🚀 Iniciando servidor de desarrollo..."
echo "   URL: http://localhost:8000"
echo "   API: http://localhost:8000/api/v1"
echo ""
echo "Credenciales demo:"
echo "   Admin: admin@decorarte.demo / password"
echo "   Gerente: gerente@decorarte.demo / password"
echo ""
php artisan serve --host=0.0.0.0 --port=8000
