# Facturify laravel 12

Sistema de mensajería

## Requisitos

- PHP >= 8.3.20
- MySQL 9.3.0
- Composer
- Laravel 12

## Instalación

1. Clonar repositorio:

```bash
git clone https://github.com/charlyrm14/facturify-backend 
cd facturify-backend
```

2. Instalar dependencias:
```bash
composer install
```

3. Archivo de entorno:
```bash
cp .env.example .env
```

4. Editar conexión de base de datos
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

5. Generar clave de aplicación
```bash
php artisan key:generate
```

6. Ejecutar migraciones 
```bash
php artisan migrate
ó 
php artisan migrate --seed
```

7. Levantar servidor
```bash
php artisan migrate
```

Endpoints

> ⚠️ Todos los endpoints requieren Bearer Token

### **Auth**
- POST /api/login: Autenticación JWT
- GET /api/user: Información usuario autenticado

### **Conversaciones**
- GET /api/threads: Listado de conversaciones por usuario autenticado
- GET /api/threads/{thread_id}: Información de hilo
- POST /api/threads: Crea nuevo hilo con primer mensaje
- POST api/threads/{thread_id}/messages: Crea la respuesta de un hilo

### **Notificaciones**
- GET api/notifications: Listado de notificaciones por usuario autenticado


### Herramientas usadas:
- Postman Documenter: para generar la documentación de algunos de los endpoints
- Mintlify: Para documentar funciones en controladores y endpoints

### **Notas:**

Dentro de la carpeta public se encuentran los siguientes archivos importantes para el proyecto:

- Diagrama de Entidad-Relación (ER): diagrama-modelo-er.png

- Colección de Postman: Archivo JSON con la colección de endpoints para probar la API y facilitar el desarrollo y pruebas.

### **Autores:**
Carlos I. Ramos Morales
