# Ruta Docente — PHP MVC + MySQL

Aplicación para cPanel desarrollada en PHP 8.1+, PDO y MySQL 5.7/8. Incluye login diferenciado, panel administrativo, área docente, usuarios, roles, asignaturas, grupos, subgrupos, tests y tabuladores.

## Instalación en cPanel

1. Sube todo el proyecto a `public_html`.
2. Crea una base de datos y usuario MySQL desde cPanel.
3. Importa `database/rutadocente.sql` con phpMyAdmin.
4. Copia `config/database.local.example.php` como `config/database.local.php` y configura las credenciales.
5. Si está en una subcarpeta, cambia `base_url` en `config/app.php`.
6. Usa PHP 8.1 o superior y habilita `pdo_mysql`.
7. Da permisos de escritura a `storage/uploads` (normalmente 755).

Acceso inicial administrador: `admin@rutadocente.cl` / `password`.
Acceso inicial docente: `docente@rutadocente.cl` / `password`.

Debes cambiar ambas contraseñas al instalar.

## Seguridad incluida

- Consultas preparadas con PDO.
- Contraseñas con `password_hash` y `password_verify`.
- Protección CSRF.
- Regeneración de sesión al iniciar sesión.
- Separación de permisos docente/administrador.
- Lista blanca para tipos de archivo y límite de tamaño.
- Bloqueo de ejecución de PHP dentro de cargas.

## Estructura

- `app/Controllers`: controladores MVC.
- `app/Models`: modelos de datos.
- `app/Views`: vistas y diseño.
- `config`: aplicación y conexión MySQL separada.
- `database`: esquema SQL y datos iniciales.
- `public`: entrada, CSS y JavaScript.
- `storage/uploads`: archivos cargados.
