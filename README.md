# DecorArte RH Operativo

Sistema integral de Recursos Humanos para DecorArte.

## 🚀 Despliegue Rápido

### Opción 1: VPS (Recomendado para producción)
```bash
# En un servidor Ubuntu 24.04 limpio:
curl -fsSL https://raw.githubusercontent.com/tu-repo/decorarte-rh/main/deploy/install-vps.sh | bash
```

### Opción 2: Railway (Gratis para pruebas)
```bash
npm install -g @railway/cli
railway login
railway init
railway up
```

### Opción 3: Docker Compose
```bash
docker-compose up -d
```

## 📖 Documentación Completa

- [Guía de Despliegue](DEPLOY-GUIDE.md) - Pasos detallados para cada plataforma
- [Guía de Instalación Local](README.md) - Desarrollo local sin Docker
- [Configuración de Servidor](deploy/README-DEPLOY.md) - Nginx, SSL, backups

## 🔑 Credenciales Demo

| Rol | Email | Password |
|-----|-------|----------|
| Administrador | admin@decorarte.demo | password |
| Gerente | gerente@decorarte.demo | password |
| Supervisor | ana.supervisor@decorarte.demo | password |
| Empleado | juan.perez@decorarte.demo | password |

## 📦 Tecnologías

- **Backend**: Laravel 11, PostgreSQL, Sanctum, Redis
- **Frontend**: React 19, Vite, TailwindCSS, shadcn/ui
- **Infraestructura**: Docker, Nginx, Let's Encrypt

## 📄 Licencia

Proprietary - DecorArte
