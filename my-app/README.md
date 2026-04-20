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

### Requisitos

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL

### Instalacion

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
