# 🚀 Guía de Despliegue en Internet - DecorArte RH Operativo

## RESUMEN RÁPIDO (3 opciones)

| Opción | Dificultad | Costo | Tiempo | Recomendado para |
|--------|-----------|-------|--------|-----------------|
| **A. Railway.app** | ⭐ Fácil | Gratis | 10 min | Pruebas/demo |
| **B. VPS (DigitalOcean)** | ⭐⭐ Medio | $6/mes | 30 min | Producción real |
| **C. Docker Local → VPS** | ⭐⭐⭐ Avanzado | $6/mes | 45 min | Equipos técnicos |

---

## ✅ OPCIÓN A: Railway.app (GRATIS - Más fácil)

### Paso 1: Crear cuenta
1. Ve a https://railway.app
2. Regístrate con GitHub (más fácil)
3. Verifica tu email

### Paso 2: Subir proyecto
```bash
# En tu computadora local
cd decorarte-rh-operativo

# Instalar Railway CLI
curl -fsSL https://railway.app/install.sh | bash

# Login
railway login

# Inicializar proyecto
railway init --name decorarte-rh

# Subir código
railway up
```

### Paso 3: Configurar variables
En el dashboard de Railway:
1. Ve a "Variables"
2. Agrega estas variables:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-proyecto.railway.app
FRONTEND_URL=https://tu-proyecto.railway.app
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}
```

### Paso 4: Agregar PostgreSQL
1. En Railway dashboard, click "New"
2. Selecciona "Database" → "Add PostgreSQL"
3. Railway conectará automáticamente la DB

### Paso 5: Deploy
```bash
railway up
```

✅ **Listo!** Tu app estará en: `https://tu-proyecto.railway.app`

---

## ✅ OPCIÓN B: VPS con DigitalOcean (RECOMENDADO)

### Paso 1: Crear Droplet
1. Ve a https://digitalocean.com
2. Crea cuenta (obtienes $200 de crédito gratis)
3. Crea un Droplet:
   - **OS**: Ubuntu 24.04 (LTS)
   - **Plan**: Basic ($6/mes - 1GB RAM, 1 CPU)
   - **Datacenter**: Nueva York o Ciudad de México
   - **Authentication**: Password (más fácil) o SSH Key

### Paso 2: Conectarte al servidor
```bash
# Recibirás un email con IP y password
# Conéctate por SSH:
ssh root@TU_IP_DEL_SERVIDOR

# Te pedirá cambiar el password la primera vez
```

### Paso 3: Ejecutar instalador automático
```bash
# Una vez conectado al servidor, ejecuta:
wget https://raw.githubusercontent.com/tu-repo/decorarte-rh/main/deploy/install-vps.sh
chmod +x install-vps.sh
./install-vps.sh
```

O manualmente:
```bash
# 1. Actualizar sistema
apt update && apt upgrade -y

# 2. Instalar todo automáticamente
cd /var/www
git clone https://github.com/tu-repo/decorarte-rh.git

# 3. Ejecutar script de instalación
cd decorarte-rh
chmod +x deploy/install-vps.sh
./install-vps.sh
```

### Paso 4: Configurar dominio (opcional)
```bash
# Si tienes un dominio:
certbot --nginx -d tu-dominio.com -d www.tu-dominio.com
```

✅ **Listo!** Accede en: `http://TU_IP` o `https://tu-dominio.com`

---

## ✅ OPCIÓN C: Docker Compose (Para equipos técnicos)

### Requisitos
- Servidor con Docker y Docker Compose instalados
- Mínimo 2GB RAM recomendado

### Paso 1: Subir archivos al servidor
```bash
# En tu computadora
scp -r decorarte-rh-operativo/ root@TU_SERVIDOR:/opt/

# Conectarte al servidor
ssh root@TU_SERVIDOR
```

### Paso 2: Ejecutar Docker
```bash
cd /opt/decorarte-rh-operativo

# Crear archivo de variables de entorno
cat > .env << 'EOF'
DB_PASSWORD=tu_password_seguro_aqui
APP_URL=https://tu-dominio.com
FRONTEND_URL=https://tu-dominio.com
EOF

# Iniciar todos los servicios
docker-compose up -d

# Ver logs
docker-compose logs -f backend

# Ejecutar migraciones
docker-compose exec backend php artisan migrate --force
docker-compose exec backend php artisan db:seed --force
```

### Paso 3: Configurar Nginx en el host
```bash
# Instalar Nginx en el servidor host
apt install nginx certbot python3-certbot-nginx

# Copiar configuración
cp deploy/nginx-config.conf /etc/nginx/sites-available/decorarte
ln -s /etc/nginx/sites-available/decorarte /etc/nginx/sites-enabled/

# SSL
certbot --nginx -d tu-dominio.com

# Reiniciar
systemctl restart nginx
```

✅ **Listo!** Accede en: `https://tu-dominio.com`

---

## 🔧 Configuración Post-Instalación

### Cambiar contraseñas demo
```bash
# Conectarte al servidor
ssh root@TU_SERVIDOR

# Acceder a PostgreSQL
sudo -u postgres psql decorarte_rh

# Cambiar password del admin
UPDATE users SET password = '$2y$10$...' WHERE email = 'admin@decorarte.demo';

# O usar Laravel Tinker
cd /var/www/decorarte-rh/backend
php artisan tinker
>>> User::where('email', 'admin@decorarte.demo')->first()->update(['password' => bcrypt('nueva_password')])
```

### Configurar Google OAuth (para candidatos)
1. Ve a https://console.cloud.google.com
2. Crear proyecto → APIs & Services → Credentials
3. Crear OAuth 2.0 Client ID
4. Agregar URLs autorizadas:
   - Authorized JavaScript origins: `https://tu-dominio.com`
   - Authorized redirect URIs: `https://tu-dominio.com/api/v1/auth/google/callback`
5. Copiar Client ID y Secret al `.env`:
```env
GOOGLE_CLIENT_ID=tu-client-id
GOOGLE_CLIENT_SECRET=tu-client-secret
GOOGLE_REDIRECT_URL=https://tu-dominio.com/api/v1/auth/google/callback
```

### Configurar email
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@decorarte.com"
```

---

## 📊 Monitoreo y Mantenimiento

### Ver logs
```bash
# Backend
journalctl -u decorarte-backend -f

# Nginx
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# PostgreSQL
tail -f /var/log/postgresql/postgresql-16-main.log
```

### Backup automático
```bash
# Crear script de backup
cat > /usr/local/bin/backup-decorarte.sh << 'EOF'
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
pg_dump -U decorarte decorarte_rh > /backups/decorarte_$DATE.sql
tar -czf /backups/decorarte_files_$DATE.tar.gz /var/www/decorarte-rh/backend/storage/app/public
find /backups -type f -mtime +30 -delete
EOF

chmod +x /usr/local/bin/backup-decorarte.sh

# Agregar a cron (diario a las 2 AM)
echo "0 2 * * * root /usr/local/bin/backup-decorarte.sh" >> /etc/crontab
```

### Actualizar
```bash
cd /var/www/decorarte-rh

# Backup primero
pg_dump -U decorarte decorarte_rh > backup_pre_update.sql

# Actualizar código
git pull origin main

# Backend
cd backend
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache

# Frontend
cd ../frontend
npm install
npm run build
cp -r dist/* /var/www/decorarte-rh/public/

# Reiniciar
systemctl restart decorarte-backend
systemctl restart nginx
```

---

## 🆘 Solución de Problemas

### Error 500 / No carga
```bash
# Ver logs
tail -f /var/www/decorarte-rh/backend/storage/logs/laravel.log

# Permisos
chown -R www-data:www-data /var/www/decorarte-rh
chmod -R 775 /var/www/decorarte-rh/backend/storage
chmod -R 775 /var/www/decorarte-rh/backend/bootstrap/cache

# Reiniciar PHP
systemctl restart php8.3-fpm
```

### CORS errors
```bash
# Verificar .env
FRONTEND_URL=https://tu-dominio.com
SANCTUM_STATEFUL_DOMAINS=tu-dominio.com

# Reiniciar backend
systemctl restart decorarte-backend
```

### Base de datos no conecta
```bash
# Verificar PostgreSQL
systemctl status postgresql
sudo -u postgres psql -c "\l"

# Verificar credenciales en .env
cat /var/www/decorarte-rh/backend/.env | grep DB_
```

---

## 📞 Soporte

Si tienes problemas:
1. Revisa los logs: `journalctl -u decorarte-backend -f`
2. Verifica permisos de archivos
3. Confirma que PostgreSQL está corriendo
4. Revisa que el firewall permite puertos 80, 443, 8000

---

**¡Tu sistema DecorArte RH Operativo ya está en internet! 🎉**
