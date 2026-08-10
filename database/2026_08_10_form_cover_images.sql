-- Imagen de portada para las tarjetas públicas de talleres.
-- Ejecutar dentro de la base de datos seleccionada en phpMyAdmin.
-- Puede importarse nuevamente sin generar un error por columna duplicada.

SET @migration_sql = IF(
 (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='public_form_settings' AND COLUMN_NAME='cover_image')=0,
 'ALTER TABLE public_form_settings ADD COLUMN cover_image VARCHAR(255) NULL AFTER slug',
 'SELECT ''La columna cover_image ya existe; no es necesario crearla nuevamente'''
);
PREPARE migration_statement FROM @migration_sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;
