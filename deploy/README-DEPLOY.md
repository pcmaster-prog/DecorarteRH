# Guía de Despliegue - DecorArte RH Operativo

## Requisitos del Servidor

### Mínimos
- PHP 8.2+
- PostgreSQL 14+
- Composer 2+
- Node.js 20+ (solo para build)
- Nginx o Apache

### Recomendados
- PHP 8.3 con OPcache
- PostgreSQL 16
- Redis (para caché y colas)
- SSL/TLS certificado

## Instalación en Servidor (Sin Docker)

### 1. Clonar o subir archivos
```bash
cd /var/www
git clone <repo> decorarte-rh
cd decorarte-rh
```

### 2. Configurar Backend
```bash
cd backend
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Editar .env con credenciales reales
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

### 3. Configurar Frontend
```bash
cd ../frontend
npm install
npm run build
# Los archivos estáticos quedan en dist/
```

### 4. Configurar Nginx
```bash
sudo cp deploy/nginx-config.conf /etc/nginx/sites-available/decorarte-rh
sudo ln -s /etc/nginx/sites-available/decorarte-rh /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 5. Permisos
```bash
sudo chown -R www-data:www-data /var/www/decorarte-rh
sudo chmod -R 775 /var/www/decorarte-rh/backend/storage
sudo chmod -R 775 /var/www/decorarte-rh/backend/bootstrap/cache
```

### 6. Supervisor (para queues)
```bash
sudo apt install supervisor
# Configurar worker de Laravel en /etc/supervisor/conf.d/decorarte-worker.conf
```

## Despliegue en Hosting Compartido

1. Subir archivos vía FTP/SFTP
2. Configurar .env con credenciales de la base de datos
3. Ejecutar migraciones desde terminal o usar phpMyAdmin
4. Subir frontend compilado (dist/) a public_html
5. Configurar .htaccess para SPA

## Despliegue en VPS (DigitalOcean, Linode, AWS Lightsail)

1. Crear droplet con Ubuntu 24.04
2. Instalar LEMP stack: `sudo apt update && sudo apt install nginx php8.3-fpm php8.3-pgsql postgresql composer`
3. Seguir pasos de "Instalación en Servidor"
4. Configurar firewall: `sudo ufw allow 'Nginx Full'`
5. Configurar SSL con Certbot

## Variables de Entorno Importantes

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com
FRONTEND_URL=https://tudominio.com

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=decorarte_rh
DB_USERNAME=decorarte
DB_PASSWORD=password_seguro

SANCTUM_STATEFUL_DOMAINS=tudominio.com
```

## Actualización

```bash
cd /var/www/decorarte-rh
git pull origin main
cd backend
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
cd ../frontend
npm install
npm run build
```

## Backup

```bash
# Backup base de datos
pg_dump -U postgres decorarte_rh > backup_$(date +%Y%m%d).sql

# Backup archivos
tar -czf backup_files_$(date +%Y%m%d).tar.gz backend/storage/app/public
```

## Troubleshooting

### Error 500
- Verificar permisos de storage/ y bootstrap/cache/
- Revisar logs: `tail -f backend/storage/logs/laravel.log`

### CORS errors
- Verificar SANCTUM_STATEFUL_DOMAINS en .env
- Reiniciar servidor después de cambios

### Assets no cargan
- Ejecutar `php artisan storage:link`
- Verificar configuración de Nginx/Apache

## Soporte

Para soporte técnico contactar al equipo de desarrollo de DecorArte.
