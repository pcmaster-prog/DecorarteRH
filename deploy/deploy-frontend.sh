#!/bin/bash

echo "=========================================="
echo "DecorArte RH - Despliegue Frontend"
echo "=========================================="
echo ""

FRONTEND_DIR="$(cd "$(dirname "$0")/.." && pwd)/frontend"

echo "📁 Directorio: $FRONTEND_DIR"
cd $FRONTEND_DIR

# Verificar Node.js
echo "🔍 Verificando Node.js..."
node -v || { echo "❌ Node.js no instalado"; exit 1; }

# Instalar dependencias
echo "📦 Instalando dependencias..."
npm install

# Build para producción
echo "🏗️ Compilando para producción..."
npm run build

echo ""
echo "✅ Frontend compilado en dist/"
echo "Para desarrollo: npm run dev"
echo ""
