#!/bin/bash

# =================================================================
# DecorArte RH Operativo - Instalador Automático para VPS
# =================================================================
# Este script instala todo automáticamente en un servidor Ubuntu/Debian
# =================================================================

set -e

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Variables
PROJECT_NAME="decorarte-rh"
DOMAIN="${DOMAIN:-decorarte-rh.local}"
EMAIL="${EMAIL:-admin@decorarte.demo}"
DB_PASSWORD="${DB_PASSWORD:-$(openssl rand -base64 32)}"

# Funciones
print_status() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# =================================================================
# INICIO
# =================================================================
clear
echo -e "${GREEN}"
echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║           DecorArte RH Operativo - Instalador                 ║"
echo "║                    Versión 1.0                                ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo -e "${NC}"
echo ""

# Verificar root
if [ "$EUID" -ne 0 ]; then 
    print_error "Este script debe ejecutarse como root (sudo)"
    exit 1
fi

# =================================================================
# 1. ACTUALIZAR SISTEMA
# =================================================================
print_status "Actualizando sistema..."
apt-get update -qq
apt-get upgrade -y -qq
print_success "Sistema actualizado"

# =================================================================
# 2. INSTALAR DEPENDENCIAS
# =================================================================
print_status "Instalando dependencias..."

# Instalar utilidades básicas
apt-get install -y -qq     curl     wget     git     unzip     software-properties-common     apt-transport-https     ca-certificates     gnupg2     certbot     python3-certbot-nginx     ufw     htop     nano     nginx

print_success "Dependencias básicas instaladas"

# =================================================================
# 3. INSTALAR PHP 8.3
# =================================================================
print_status "Instalando PHP 8.3..."

# Agregar repositorio PHP
add-apt-repository -y ppa:ondrej/php 2>/dev/null || {
    # Para Debian
    curl -fsSL https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /usr/share/keyrings/php.gpg
    echo "deb [signed-by=/usr/share/keyrings/php.gpg] https://packages.sury.org/php/ $(lsb_release -cs) main" > /etc/apt/sources.list.d/php.list
    apt-get update -qq
}

apt-get install -y -qq     php8.3-fpm     php8.3-cli     php8.3-pgsql     php8.3-mbstring     php8.3-xml     php8.3-curl     php8.3-zip     php8.3-bcmath     php8.3-json     php8.3-opcache     php8.3-intl     php8.3-gd

# Configurar PHP-FPM
sed -i 's/memory_limit = .*/memory_limit = 256M/' /etc/php/8.3/fpm/php.ini
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 50M/' /etc/php/8.3/fpm/php.ini
sed -i 's/post_max_size = .*/post_max_size = 50M/' /etc/php/8.3/fpm/php.ini
sed -i 's/max_execution_time = .*/max_execution_time = 300/' /etc/php/8.3/fpm/php.ini

systemctl restart php8.3-fpm
print_success "PHP 8.3 instalado"

# =================================================================
# 4. INSTALAR POSTGRESQL
# =================================================================
print_status "Instalando PostgreSQL..."

apt-get install -y -qq postgresql postgresql-contrib

# Crear usuario y base de datos
sudo -u postgres psql -c "CREATE USER decorarte WITH PASSWORD '$DB_PASSWORD';" 2>/dev/null || true
sudo -u postgres psql -c "CREATE DATABASE decorarte_rh OWNER decorarte;" 2>/dev/null || true
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE decorarte_rh TO decorarte;"

systemctl enable postgresql
systemctl restart postgresql
print_success "PostgreSQL instalado y configurado"

# =================================================================
# 5. INSTALAR COMPOSER
# =================================================================
print_status "Instalando Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
print_success "Composer instalado"

# =================================================================
# 6. INSTALAR NODE.JS 20
# =================================================================
print_status "Instalando Node.js 20..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y -qq nodejs
print_success "Node.js $(node -v) instalado"

# =================================================================
# 7. CONFIGURAR PROYECTO
# =================================================================
print_status "Configurando proyecto..."

# Crear directorio
mkdir -p /var/www/$PROJECT_NAME
cd /var/www/$PROJECT_NAME

# Clonar o extraer proyecto (asumiendo que los archivos ya están aquí)
# Si necesitas clonar desde git:
# git clone https://github.com/tu-repo/decorarte-rh.git .

print_warning "Por favor, sube los archivos del proyecto a /var/www/$PROJECT_NAME"
print_status "Puedes usar: scp -r decorarte-rh-operativo/* root@tuservidor:/var/www/decorarte-rh/"

# =================================================================
# 8. CONFIGURAR BACKEND
# =================================================================
print_status "Configurando backend..."

cd /var/www/$PROJECT_NAME/backend

# Instalar dependencias
composer install --no-dev --optimize-autoloader --no-interaction

# Configurar .env
cp .env.example .env

# Generar APP_KEY
php artisan key:generate

# Configurar base de datos en .env
sed -i "s/DB_HOST=127.0.0.1/DB_HOST=localhost/" .env
sed -i "s/DB_DATABASE=decorarte_rh/DB_DATABASE=decorarte_rh/" .env
sed -i "s/DB_USERNAME=postgres/DB_USERNAME=decorarte/" .env
sed -i "s/DB_PASSWORD=password/DB_PASSWORD=$DB_PASSWORD/" .env
sed -i "s|APP_URL=http://localhost:8000|APP_URL=https://$DOMAIN|" .env
sed -i "s|FRONTEND_URL=http://localhost:5173|FRONTEND_URL=https://$DOMAIN|" .env
sed -i "s/APP_ENV=local/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=true/APP_DEBUG=false/" .env

# Migraciones y seeders
php artisan migrate --force
php artisan db:seed --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link

# Permisos
chown -R www-data:www-data /var/www/$PROJECT_NAME
chmod -R 775 /var/www/$PROJECT_NAME/backend/storage
chmod -R 775 /var/www/$PROJECT_NAME/backend/bootstrap/cache

print_success "Backend configurado"

# =================================================================
# 9. CONFIGURAR FRONTEND
# =================================================================
print_status "Compilando frontend..."

cd /var/www/$PROJECT_NAME/frontend
npm install
npm run build

# Mover build a directorio público
mkdir -p /var/www/$PROJECT_NAME/public
cp -r dist/* /var/www/$PROJECT_NAME/public/

print_success "Frontend compilado"

# =================================================================
# 10. CONFIGURAR NGINX
# =================================================================
print_status "Configurando Nginx..."

cat > /etc/nginx/sites-available/$PROJECT_NAME << 'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/decorarte-rh/public;
    index index.html;

    # Frontend SPA
    location / {
        try_files $uri $uri/ /index.html;
        add_header Cache-Control "no-cache";
    }

    # API Backend
    location /api/ {
        proxy_pass http://127.0.0.1:8000/api/;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }

    # Storage
    location /storage/ {
        alias /var/www/decorarte-rh/backend/storage/app/public/;
    }

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
}
EOF

# Activar sitio
ln -sf /etc/nginx/sites-available/$PROJECT_NAME /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Testear configuración
nginx -t

systemctl restart nginx
systemctl enable nginx

print_success "Nginx configurado"

# =================================================================
# 11. CONFIGURAR FIREWALL
# =================================================================
print_status "Configurando firewall..."

ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow http
ufw allow https
ufw allow 8000/tcp
ufw --force enable

print_success "Firewall configurado"

# =================================================================
# 12. CONFIGURAR SSL (Let's Encrypt)
# =================================================================
print_status "Configurando SSL..."

if [ "$DOMAIN" != "decorarte-rh.local" ]; then
    certbot --nginx -d $DOMAIN --non-interactive --agree-tos --email $EMAIL || {
        print_warning "No se pudo configurar SSL automáticamente"
        print_status "Puedes configurarlo manualmente con: certbot --nginx -d $DOMAIN"
    }
else
    print_warning "Dominio local detectado, omitiendo SSL"
    print_status "Para producción, configura un dominio real y ejecuta: certbot --nginx -d tu-dominio.com"
fi

# =================================================================
# 13. INICIAR SERVICIOS
# =================================================================
print_status "Iniciando servicios..."

# Crear systemd service para Laravel
 cat > /etc/systemd/system/decorarte-backend.service << EOF
[Unit]
Description=DecorArte RH Backend
After=network.target postgresql.service

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/decorarte-rh/backend
ExecStart=/usr/bin/php artisan serve --host=0.0.0.0 --port=8000
Restart=on-failure
RestartSec=5

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable decorarte-backend
systemctl start decorarte-backend

# =================================================================
# 14. RESUMEN
# =================================================================
clear
echo -e "${GREEN}"
echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║              ✅ INSTALACIÓN COMPLETADA                        ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo -e "${NC}"
echo ""
echo -e "${BLUE}📍 Acceso:${NC}"
echo "   • Sitio web: http://$DOMAIN (o IP del servidor)"
echo "   • API: http://$DOMAIN/api/v1"
echo ""
echo -e "${BLUE}🔑 Credenciales Demo:${NC}"
echo "   • Admin: admin@decorarte.demo / password"
echo "   • Gerente: gerente@decorarte.demo / password"
echo "   • Empleado: juan.perez@decorarte.demo / password"
echo ""
echo -e "${BLUE}🗄️ Base de Datos:${NC}"
echo "   • Usuario: decorarte"
echo "   • Contraseña: $DB_PASSWORD"
echo "   • Base: decorarte_rh"
echo ""
echo -e "${BLUE}📁 Directorios:${NC}"
echo "   • Proyecto: /var/www/decorarte-rh"
echo "   • Backend: /var/www/decorarte-rh/backend"
echo "   • Frontend: /var/www/decorarte-rh/public"
echo ""
echo -e "${BLUE}🔧 Comandos útiles:${NC}"
echo "   • Ver logs: journalctl -u decorarte-backend -f"
echo "   • Reiniciar: systemctl restart decorarte-backend"
echo "   • Status: systemctl status decorarte-backend"
echo ""
echo -e "${YELLOW}⚠️  IMPORTANTE:${NC}"
echo "   1. Cambia las contraseñas demo inmediatamente"
echo "   2. Configura un dominio real para SSL"
echo "   3. Realiza backups regulares de la base de datos"
echo ""
echo -e "${GREEN}¡DecorArte RH Operativo está listo! 🚀${NC}"
echo ""

# Guardar credenciales
 cat > /root/decorarte-credentials.txt << EOF
DecorArte RH Operativo - Credenciales
======================================
Fecha: $(date)
Dominio: $DOMAIN

Base de Datos:
  Usuario: decorarte
  Contraseña: $DB_PASSWORD
  Base: decorarte_rh

Credenciales Demo:
  Admin: admin@decorarte.demo / password
  Gerente: gerente@decorarte.demo / password
  Empleado: juan.perez@decorarte.demo / password

Comandos:
  Logs: journalctl -u decorarte-backend -f
  Reiniciar: systemctl restart decorarte-backend
  Backup DB: pg_dump -U decorarte decorarte_rh > backup.sql
EOF

chmod 600 /root/decorarte-credentials.txt
print_status "Credenciales guardadas en /root/decorarte-credentials.txt"
