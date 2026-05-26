#!/bin/bash

echo "=========================================="
echo "DecorArte RH - Despliegue Completo"
echo "=========================================="
echo ""

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

echo "📁 Proyecto: $PROJECT_DIR"

# Backend
echo ""
echo "🏗️ Desplegando Backend..."
cd $PROJECT_DIR/backend

# Instalar si no existe vendor
if [ ! -d "vendor" ]; then
    echo "📦 Instalando dependencias de Composer..."
    composer install --no-dev --optimize-autoloader
fi

# Configurar .env
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
    echo "⚠️ Por favor edita .env con tus credenciales de PostgreSQL"
fi

# Migrar y seed
php artisan migrate --force
php artisan db:seed --force

# Cache
php artisan config:cache
php artisan route:cache

# Frontend
echo ""
echo "🏗️ Desplegando Frontend..."
cd $PROJECT_DIR/frontend

if [ ! -d "node_modules" ]; then
    echo "📦 Instalando dependencias de npm..."
    npm install
fi

npm run build

echo ""
echo "=========================================="
echo "✅ Despliegue completado!"
echo "=========================================="
echo ""
echo "Backend: http://localhost:8000"
echo "Frontend: http://localhost:5173 (dev) o servir dist/"
echo "API: http://localhost:8000/api/v1"
echo ""
echo "Credenciales demo:"
echo "  Admin: admin@decorarte.demo / password"
echo "  Gerente: gerente@decorarte.demo / password"
echo "  Empleado: juan.perez@decorarte.demo / password"
echo ""
echo "Para iniciar backend: cd backend && php artisan serve"
echo "Para iniciar frontend: cd frontend && npm run dev"
echo ""
