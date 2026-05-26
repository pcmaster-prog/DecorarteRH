# DecorArte RH - Despliegue en Railway (Windows)
# Ejecutar en PowerShell como Administrador

Write-Host ""
Write-Host "╔═══════════════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║     DecorArte RH - Despliegue en Railway.app                ║" -ForegroundColor Green
Write-Host "╚═══════════════════════════════════════════════════════════════╝" -ForegroundColor Green
Write-Host ""

# Verificar Node.js
Write-Host "ℹ️  Verificando Node.js..." -ForegroundColor Blue
if (!(Get-Command node -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Node.js no está instalado. Descárgalo de https://nodejs.org" -ForegroundColor Red
    exit 1
}

# Instalar Railway CLI
Write-Host ""
Write-Host "ℹ️  PASO 1/6: Instalando Railway CLI..." -ForegroundColor Blue
npm install -g @railway/cli

# Login
Write-Host ""
Write-Host "ℹ️  PASO 2/6: Iniciando sesión en Railway..." -ForegroundColor Blue
Write-Host "⚠️  Se abrirá tu navegador para autenticarte" -ForegroundColor Yellow
railway login

# Inicializar proyecto
Write-Host ""
Write-Host "ℹ️  PASO 3/6: Creando proyecto..." -ForegroundColor Blue
railway init --name decorarte-rh

# Variables
Write-Host ""
Write-Host "ℹ️  PASO 4/6: Configurando variables..." -ForegroundColor Blue
$appKey = [Convert]::ToBase64String((1..32 | ForEach-Object { Get-Random -Maximum 256 } | ForEach-Object { [byte]$_ }))
railway variables --set APP_ENV=production
railway variables --set APP_DEBUG=false
railway variables --set APP_KEY="base64:$appKey"

# Deploy
Write-Host ""
Write-Host "ℹ️  PASO 5/6: Desplegando..." -ForegroundColor Blue
Write-Host "⚠️  IMPORTANTE: Ve a https://railway.app/dashboard" -ForegroundColor Yellow
Write-Host "⚠️  Agrega una base de datos PostgreSQL a tu proyecto" -ForegroundColor Yellow
Write-Host "⚠️  Presiona ENTER cuando esté listo..." -ForegroundColor Yellow
Read-Host

railway up

Write-Host ""
Write-Host "✅ ¡DESPLIEGUE COMPLETADO!" -ForegroundColor Green
Write-Host ""
Write-Host "🌐 Verifica tu app en: https://railway.app/dashboard" -ForegroundColor Green
Write-Host ""
