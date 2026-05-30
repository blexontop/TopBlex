# TopBlex - Mi Proyecto Ecommerce en Laravel

Este proyecto es una tienda online que he ido construyendo paso a paso con Laravel 12.
Aqui dejo todo explicado en primera persona: que monte, por que lo hice y como se ejecuta.

## 1) Objetivo Del Proyecto

Mi objetivo con TopBlex fue crear una base solida de ecommerce con:

- Catalogo de productos con categorias por genero y tipo.
- Busqueda, filtros y ordenacion.
- Carrito en sesion.
- Registro, login y area de cuenta.
- Flujo de pedido y pago (estado inicial pendiente).
- Pagina de contacto y preguntas frecuentes.

## 2) Stack Tecnologico Que Use

- Backend: Laravel 12 + PHP 8.2.
- Base de datos: MySQL.
- ORM: Eloquent.
- Frontend build: Vite.
- Estilos: Tailwind CSS v4 + CSS propio.
- JS cliente: vanilla JS (helpers de UI).

Dependencias importantes:

- `laravel/framework`, `laravel/tinker`.
- Dev: `phpunit`, `laravel/pint`, `laravel/sail`, `concurrently`.

## 3) Como Lo Fui Montando Paso A Paso

### Paso 1. Arranque del proyecto

Empece con un proyecto Laravel base y deje configurado el flujo tipico de trabajo:

- Instalacion de dependencias con Composer y npm.
- Generacion de `APP_KEY`.
- Migraciones iniciales.
- Vite para desarrollo y build.

Scripts utiles que deje listos:

- `composer setup`: instala todo, crea `.env`, genera key, migra y build.
- `composer dev`: levanta servidor Laravel + cola + Vite en paralelo.
- `composer test`: limpia config y ejecuta tests.

### Paso 2. Diseno de base de datos

Despues modele las tablas para cubrir ecommerce completo.

Tablas principales que cree:

- Usuarios y soporte base de Laravel: `users`, `cache`, `jobs`.
- Catalogo: `categorias`, `productos`, `imagen_productos`, `coleccions`, `colors`, `tallas`, `variante_productos`.
- Compra: `carritos`, `item_carritos`, `pedidos`, `item_pedidos`, `pagos`.
- Contenido y soporte: `pregunta_frecuentes`, `mensaje_contactos`, `entrada_blogs`, `suscriptor_newsletters`, `favoritos`, `direccions`, `solicitud_devolucions`.

Ademas hice migraciones de ampliacion para:

- Campos de perfil en usuario (`telefono`, `ciudad`, `direccion`).
- Campos extra en contacto, FAQ, pedidos, items de pedido y pagos.

### Paso 3. Modelos Eloquent

Fui creando modelos por dominio para trabajar cada parte del negocio.

Modelos incluidos:

- `Categoria`, `Producto`, `ImagenProducto`, `Coleccion`, `Color`, `Talla`, `VarianteProducto`.
- `Pedido`, `ItemPedido`, `Pago`.
- `Carrito`, `ItemCarrito`, `Favorito`.
- `Direccion`, `SolicitudDevolucion`.
- `MensajeContacto`, `PreguntaFrecuente`, `EntradaBlog`, `SuscriptorNewsletter`.
- `User`.

Relaciones clave que deje implementadas:

- Categoria jerarquica: `parent` y `children`.
- Producto -> pertenece a categoria, y tiene muchas imagenes.
- User -> tiene muchos pedidos y mensajes de contacto.
- Pedido -> pertenece a user, tiene items y pagos.

### Paso 4. Semillas de datos (seeders)

Para no arrancar en vacio prepare datos de demo.

Seeders relevantes:

- `CatalogoDemoSeeder`: crea estructura Hombre/Mujer + tipos (chandal, vaqueros, zapatos, accesorios) y productos demo con imagen.
- `PreguntaFrecuenteSeeder`: rellena preguntas frecuentes.
- `PedidoDemoSeeder`: genera un pedido de ejemplo con items y pago para el usuario demo.
- `DatabaseSeeder`: crea usuario de prueba y llama a seeders principales.

Usuario demo que queda creado:

- Email: `test@example.com`
- Password: `password`

### Paso 5. Rutas publicas y de tienda

Luego me centre en el flujo de navegacion real de la tienda desde `routes/web.php`.

Rutas principales que implemente:

- `/` -> home con productos visibles recientes.
- `/productos` -> listado con filtros por genero/tipo, busqueda por texto y orden (precio asc/desc o latest).
- `/productos/{producto}` -> detalle de producto.
- `/categorias` y `/colecciones` -> redirigen al listado de productos.
- `/carrito` -> muestra items guardados en sesion y calcula total.
- `POST /carrito/agregar` -> agrega producto a carrito y suma cantidad si ya existe.
- `/contacto` GET/POST -> formulario y guardado en BD.
- `/preguntas-frecuentes` -> listado de FAQ activas y ordenadas.

### Paso 6. Autenticacion y cuenta de usuario

Despues arme toda la parte de acceso y cuenta:

- Login y registro para invitados (`guest`).
- Logout para usuarios autenticados (`auth`).
- `/mi-cuenta` para ver y actualizar perfil.

Validaciones que aplique:

- Email unico.
- Password con confirmacion.
- Campos de perfil opcionales (telefono, ciudad, direccion).

### Paso 7. Flujo de pedido

Con el usuario autenticado, implemente el cierre de compra:

- `POST /pedidos/confirmar`.
- Leo carrito desde sesion.
- Si esta vacio, no deja confirmar.
- Si hay productos, ejecuto transaccion:
	- Creo pedido con codigo tipo `TBX-XXXXXXXX`.
	- Creo lineas en `item_pedidos`.
	- Creo registro en `pagos` con estado pendiente.
- Vacio carrito al finalizar.
- Muestro mensaje de exito con codigo del pedido.

Tambien deje:

- `/pedidos` -> historial del usuario autenticado.

### Paso 8. Frontend y experiencia visual

En la capa visual hice una mezcla de Tailwind + CSS propio:

- Variables CSS para paleta y estilos de marca.
- Header y navegacion personalizados.
- Estetica oscura y tipografias marcadas para identidad.
- Animaciones suaves en logo y elementos de navegacion.

En JavaScript anadi helper simple:

- Cambio de imagen principal en detalle de producto al pulsar miniaturas.

## 4) Estructura Del Proyecto (Resumen)

Carpetas que considero mas importantes:

- `app/Models`: toda la capa de dominio y relaciones.
- `routes/web.php`: flujo principal de la aplicacion.
- `resources/views`: vistas Blade (home, productos, carrito, auth, cuenta, pedidos, contacto, faq, layouts).
- `database/migrations`: evolucion de esquema.
- `database/seeders`: datos de demo.
- `resources/css` y `resources/js`: estilos y scripts cliente.
- `scripts`: utilidades para listar y borrar tablas de MySQL en local.

## 5) Scripts Auxiliares Que Me Hice

En `scripts/` deje dos utilidades para entorno local:

- `list_tables.php`: lista tablas de la BD MySQL.
- `drop_all_tables.php`: elimina todas las tablas (desactiva FK temporalmente).

Nota: esos scripts usan conexion hardcodeada a `127.0.0.1`, puerto `3306`, BD `topblex`, usuario `root`, clave `toor`.

## 6) Como Levantar El Proyecto

### Opcion A: Desarrollo Local (sin Docker)

#### Requisitos

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL

#### Instalacion

1. Clonar repositorio y entrar al proyecto.
2. Instalar dependencias PHP:

```bash
composer install
```

3. Instalar dependencias frontend:

```bash
npm install
```

4. Crear `.env` desde `.env.example` y configurar BD.
5. Generar key:

```bash
php artisan key:generate
```

6. Migrar y seedear:

```bash
php artisan migrate --seed
```

7. Levantar en desarrollo (todo junto):

```bash
composer dev
```

O en terminales separadas:

```bash
php artisan serve
npm run dev
```

### Opcion B: Con Docker (Recomendado Para Produccion)

#### Requisitos

- Docker
- Docker Compose
- OpenSSL (para generar certificados)

#### Configuracion Inicial

1. Generar certificados SSL autofirmados (desarrollo):

```bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout docker/nginx/ssl/topblex.key \
  -out docker/nginx/ssl/topblex.crt \
  -subj "/CN=localhost"
```

2. Crear archivo `.env` desde `.env.example`:

```bash
cp .env.example .env
```

3. Iniciar contenedores:

```bash
docker compose up -d --build
```

4. Las migraciones y cacheos se ejecutan automaticamente en el entrypoint.

5. Acceder a la aplicacion:
   - **HTTPS**: https://localhost
   - **HTTP**: http://localhost (redirige automaticamente a HTTPS)

#### Detener y Limpiar

```bash
# Detener contenedores
docker compose down

# Detener y eliminar volumenes (borra datos de MySQL)
docker compose down -v
```

## 6.1) Arquitectura Docker, Nginx y HTTPS Explicada En Detalle

### Estructura De Contenedores

El proyecto usa **3 contenedores independientes** orquestados por Docker Compose:

```
┌─────────────────────────────────────────────────────┐
│         Docker Network: topblex_network             │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ┌──────────────────┐    ┌──────────────────┐     │
│  │  topblex_nginx   │    │   topblex_app    │     │
│  │ (nginx:alpine)   │───▶│  (PHP 8.4 FPM)   │     │
│  │ Puertos:         │    │                  │     │
│  │  - 80 → HTTP     │    │ Carpeta munt:    │     │
│  │  - 443 → HTTPS   │    │ /var/www (local) │     │
│  └──────────────────┘    └──────────────────┘     │
│         ▲                        ▲                 │
│         │                        │                 │
│         └────────────────────────┘                 │
│                                                     │
│  ┌──────────────────────────────────────┐         │
│  │      topblex_mysql (mysql:8.0)       │         │
│  │  Puerto: 3307 (expuesto al host)     │         │
│  │  Datos persistidos en volumen local  │         │
│  └──────────────────────────────────────┘         │
│                                                     │
└─────────────────────────────────────────────────────┘
```

### Flujo De Peticiones (HTTP → HTTPS)

1. **Cliente hace peticion a `http://localhost`**
   - Nginx escucha en puerto 80
   - Configuracion en `docker/nginx/default.conf`:
     ```nginx
     server {
         listen 80;
         return 301 https://$host$request_uri;  # Redirige a HTTPS
     }
     ```

2. **Nginx redirige automaticamente a HTTPS (`https://localhost`)**
   - Nginx escucha en puerto 443 con SSL
   - Valida el certificado (`topblex.crt` y `topblex.key`)
   - Procesa la peticion HTTPS

3. **Nginx enruta la peticion al servidor PHP**
   - Usa `fastcgi_pass app:9000`
   - Se conecta al contenedor PHP por nombre DNS interno
   - PHP procesa la peticion y devuelve respuesta

4. **Respuesta regresa al cliente por HTTPS**

### Certificados SSL (HTTPS)

#### Que son los certificados?

Los certificados SSL permiten comunicacion encriptada entre cliente (navegador) y servidor. Hay dos archivos:

- **`topblex.crt`** (Certificate): certificado publico, dice al navegador que confie en este servidor
- **`topblex.key`** (Private Key): clave privada, secreta, solo el servidor la conoce

#### Tipos de certificados

| Tipo | Uso | Validez | Costo | Navegador |
|------|-----|---------|-------|-----------|
| **Autofirmado** (que usamos) | Desarrollo local | 365 dias | Gratis | Muestra advertencia |
| **Let's Encrypt** | Produccion | 90 dias | Gratis | Válido sin advertencia |
| **Comercial** | Produccion empresarial | 1-3 años | $$ | Válido con marca |

#### Como generar los certificados (desarrollo)

```bash
# Generar automaticamente (una sola vez)
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout docker/nginx/ssl/topblex.key \
  -out docker/nginx/ssl/topblex.crt \
  -subj "/CN=localhost"

# Explicacion de flags:
# -x509              : generar certificado X.509 (estandar)
# -nodes             : sin encriptar la clave privada
# -days 365          : valido por 1 año
# -newkey rsa:2048   : crear clave RSA de 2048 bits
# -keyout            : donde guardar la clave privada
# -out               : donde guardar el certificado
# -subj              : nombre del sitio sin pedir interactivamente
```

#### Archivos de certificados en el proyecto

```
docker/
├── nginx/
│   ├── default.conf         ← Configuracion de Nginx
│   └── ssl/
│       ├── topblex.crt      ← Certificado SSL (publico)
│       └── topblex.key      ← Clave privada (secreto)
└── entrypoint.sh            ← Script de inicio
```

#### Configuracion en Nginx

En `docker/nginx/default.conf`:

```nginx
server {
    listen 443 ssl;
    http2 on;
    
    # Rutas a los certificados dentro del contenedor
    ssl_certificate     /etc/nginx/ssl/topblex.crt;
    ssl_certificate_key /etc/nginx/ssl/topblex.key;
    
    # Protocolos seguros (TLS moderno)
    ssl_protocols       TLSv1.2 TLSv1.3;
    
    # Headers de seguridad
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Strict-Transport-Security "max-age=31536000" always;
}
```

### Configuracion Detallada De Cada Contenedor

#### 1. Contenedor Nginx (`topblex_nginx`)

**Imagen**: `nginx:1.25-alpine` (ligera, solo 50MB)

**Puerto expuesto**:
- `80:80` → HTTP (redirige a HTTPS)
- `443:443` → HTTPS (conexion segura)

**Volumenes**:
- `./:/var/www` → Sincroniza el proyecto local con el contenedor
- `./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf:ro` → Configuracion de Nginx (solo lectura)
- `./docker/nginx/ssl:/etc/nginx/ssl:ro` → Certificados SSL (solo lectura)

**Dependencias**:
- Espera a que `app` este listo para conectarse

#### 2. Contenedor PHP (`topblex_app`)

**Base**: `php:8.4-fpm` (FastCGI Process Manager)

**Que instala en Dockerfile**:
```dockerfile
# Extensiones de PHP necesarias
- pdo_mysql    : conexion a MySQL
- mbstring     : soporte UTF-8
- exif         : metadata de imagenes
- pcntl        : control de procesos
- bcmath       : calculos de precision
- gd           : procesamiento de imagenes
- zip          : manejo de archivos ZIP

# Herramientas
- composer     : gestor de paquetes PHP
- git, curl    : utilidades
```

**Que ejecuta en startup** (`docker/entrypoint.sh`):

```bash
# 1. Espera a que MySQL este listo (timeout 5s, reintentos 3s)
until mysqladmin ping -h mysql; do
    sleep 3
done

# 2. Ejecuta migraciones de BD
php artisan migrate --force

# 3. Optimiza cacheos (mejora rendimiento)
php artisan config:cache      # Cache de configuracion
php artisan route:cache       # Cache de rutas
php artisan view:cache        # Cache de vistas

# 4. Crea enlace simbolico para storage (publica archivos)
php artisan storage:link

# 5. Inicia PHP-FPM
exec php-fpm
```

**Volumen**:
- `./:/var/www` → Todo el proyecto montado en vivo (cambios locales = cambios en contenedor)

#### 3. Contenedor MySQL (`topblex_mysql`)

**Imagen**: `mysql:8.0`

**Configuracion**:
```dockerfile
MYSQL_DATABASE=topblex
MYSQL_ROOT_PASSWORD=secret
MYSQL_USER=topblex
MYSQL_PASSWORD=secret
```

⚠️ **IMPORTANTE**: Credenciales por defecto **no son seguras** para produccion. Cambiar en `.env`.

**Puerto expuesto**:
- `3307:3306` → Permite conectarse desde host local con herramientas como TablePlus, MySQL Workbench

**Volumen**:
- `topblex_mysql_data:/var/lib/mysql` → Datos persistidos (no se pierden al `docker down`)

**Health Check**:
```dockerfile
test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
interval: 10s
timeout: 5s
retries: 5
```
Comprueba cada 10 segundos si MySQL esta vivo. Si falla 5 veces, el contenedor se marca como unhealthy.

### Variables De Entorno

El archivo `.env` define las conexiones entre contenedores:

```ini
# En web.php + routes, la app se conecta a "mysql" (nombre del contenedor)
# Docker traduce "mysql" al IP interno del contenedor
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=topblex
DB_USERNAME=topblex
DB_PASSWORD=secret
```

### Comandos Utiles

```bash
# Ver estado de contenedores
docker compose ps

# Ver logs del PHP en vivo
docker compose logs app -f

# Ver logs de Nginx
docker compose logs nginx -f

# Entrar a consola del PHP
docker compose exec app bash

# Ejecutar comandos Artisan dentro del contenedor
docker compose exec app php artisan migrate
docker compose exec app php artisan tinker

# Ver volumen de MySQL
docker volume ls
docker volume inspect topblex_mysql_data

# Rebuild solo una imagen (ej: cambios en Dockerfile)
docker compose build app

# Reiniciar un contenedor
docker compose restart app
```

### Diferencias Con Desarrollo Local

| Aspecto | Local | Docker |
|--------|-------|--------|
| **Setup** | Instalar PHP, MySQL, Node localmente | Solo Docker |
| **Aislamiento** | Cambios afectan todo el SO | Contenedores aislados |
| **Consistencia** | "Funciona en mi PC" | Igual en dev y produccion |
| **Performance** | Directo del SO | Pequeña sobrecarga |
| **HTTPS** | Configuracion manual | Ya incluido |
| **Base de datos** | En SO, puertos fijos | En contenedor, volumen |
| **Limpieza** | Dejar archivos temporales | `docker down -v` limpia todo |

## 7) Estado Actual

Ahora mismo tengo una base funcional de ecommerce con:

- Catalogo navegable y filtrable.
- Carrito por sesion.
- Registro/login y gestion de perfil.
- Confirmacion de pedido con persistencia en BD.
- Historial de pedidos por usuario.
- Contacto y FAQ.
- Datos demo para probar sin cargar todo a mano.

## 8) Siguientes Mejoras Que Tengo En Mente

- Integrar pasarela real de pago.
- Panel admin para gestionar catalogo, pedidos y FAQ.
- Tests de feature mas amplios en checkout y auth.
- Politicas/roles para separar admin y cliente.
- Notificaciones por email al confirmar pedido.

---

Este README refleja exactamente el estado del proyecto que he ido construyendo hasta hoy (15/04/2026).
