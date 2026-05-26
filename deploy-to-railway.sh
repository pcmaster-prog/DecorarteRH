#!/bin/bash

# =================================================================
# DecorArte RH - Despliegue en Railway.app (GRATIS)
# =================================================================
# Este script te guía paso a paso para subir tu app a Railway
# =================================================================

echo ""
echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║     DecorArte RH - Despliegue en Railway.app              ║"
echo "║              Versión Gratuita                                ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""

# Colores
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_status() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# =================================================================
# VERIFICAR PREREQUISITOS
# =================================================================
print_status "Verificando prerequisitos..."

# Verificar Node.js
if ! command -v node &> /dev/null; then
    echo "❌ Node.js no está instalado. Por favor instálalo primero:"
    echo "   https://nodejs.org (descarga la versión LTS)"
    exit 1
fi

# Verificar Git
if ! command -v git &> /dev/null; then
    echo "❌ Git no está instalado. Por favor instálalo:"
    echo "   https://git-scm.com/downloads"
    exit 1
fi

print_success "Prerequisitos verificados"

# =================================================================
# PASO 1: INSTALAR RAILWAY CLI
# =================================================================
echo ""
print_status "PASO 1/6: Instalando Railway CLI..."

if ! command -v railway &> /dev/null; then
    npm install -g @railway/cli
    print_success "Railway CLI instalado"
else
    print_success "Railway CLI ya está instalado"
fi

# =================================================================
# PASO 2: LOGIN EN RAILWAY
# =================================================================
echo ""
print_status "PASO 2/6: Iniciando sesión en Railway..."
print_status "Se abrirá tu navegador para autenticarte"
print_warning "Si no tienes cuenta, créala gratis en https://railway.app"

railway login

print_success "Sesión iniciada en Railway"

# =================================================================
# PASO 3: INICIALIZAR PROYECTO
# =================================================================
echo ""
print_status "PASO 3/6: Creando proyecto en Railway..."

# Verificar si ya está inicializado
if [ -f ".railway/config.json" ]; then
    print_warning "Proyecto ya inicializado. Saltando este paso."
else
    railway init --name decorarte-rh
    print_success "Proyecto 'decorarte-rh' creado"
fi

# =================================================================
# PASO 4: CONFIGURAR VARIABLES DE ENTORNO
# =================================================================
echo ""
print_status "PASO 4/6: Configurando variables de entorno..."

print_status "Agregando variables necesarias..."

# Variables del backend
railway variables --set APP_ENV=production
railway variables --set APP_DEBUG=false
railway variables --set APP_KEY=base64:$(openssl rand -base64 32)
railway variables --set APP_URL=\$(RAILWAY_STATIC_URL)
railway variables --set FRONTEND_URL=\$(RAILWAY_STATIC_URL)

# Variables de base de datos (Railway las configura automáticamente)
railway variables --set DB_CONNECTION=pgsql

# Variables de Sanctum
railway variables --set SANCTUM_STATEFUL_DOMAINS=\$(RAILWAY_STATIC_URL)

print_success "Variables configuradas"

# =================================================================
# PASO 5: AGREGAR POSTGRESQL
# =================================================================
echo ""
print_status "PASO 5/6: Agregando base de datos PostgreSQL..."

print_status "En el dashboard de Railway, agrega una base de datos:"
print_status "1. Ve a https://railway.app/dashboard"
print_status "2. Selecciona tu proyecto 'decorarte-rh'"
print_status "3. Click en 'New' → 'Database' → 'Add PostgreSQL'"
print_status "4. Railway conectará automáticamente la base de datos"

print_warning "Espera a que Railway termine de provisionar la base de datos"
print_warning "Luego presiona ENTER para continuar..."
read

print_success "Base de datos configurada"

# =================================================================
# PASO 6: DESPLEGAR
# =================================================================
echo ""
print_status "PASO 6/6: Desplegando aplicación..."

print_status "Subiendo código y compilando..."
railway up

print_success "¡Despliegue completado!"

# =================================================================
# RESUMEN
# =================================================================
echo ""
echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║              ✅ DESPLIEGUE COMPLETADO                         ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""

# Obtener URL
URL=$(railway status --json 2>/dev/null | grep -o '"domain":"[^"]*"' | cut -d'"' -f4 || echo "")

if [ -n "$URL" ]; then
    echo -e "${GREEN}🌐 Tu aplicación está en:${NC}"
    echo -e "${BLUE}   https://$URL${NC}"
    echo ""
    echo -e "${GREEN}🔑 Credenciales Demo:${NC}"
    echo "   Admin: admin@decorarte.demo / password"
    echo "   Gerente: gerente@decorarte.demo / password"
    echo "   Empleado: juan.perez@decorarte.demo / password"
    echo ""
    echo -e "${GREEN}📊 Dashboard de Railway:${NC}"
    echo -e "${BLUE}   https://railway.app/dashboard${NC}"
else
    echo -e "${YELLOW}⚠️  Verifica el estado en:${NC}"
    echo -e "${BLUE}   https://railway.app/dashboard${NC}"
    echo ""
    echo "Tu app debería estar lista en unos minutos."
fi

echo ""
echo -e "${GREEN}¡DecorArte RH Operativo ya está en internet! 🚀${NC}"
echo ""

# =================================================================
# COMANDOS ÚTILES POST-DESPLIEGUE
# =================================================================
echo ""
echo "Comandos útiles:"
echo "  railway status       - Ver estado del proyecto"
echo "  railway logs         - Ver logs en tiempo real"
echo "  railway variables    - Ver/Editar variables"
echo "  railway up           - Redesplegar después de cambios"
echo "  railway ssh          - Conectarte al contenedor"
echo ""
