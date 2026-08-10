-- Imagen de portada para las tarjetas públicas de talleres.
-- Ejecutar dentro de la base de datos seleccionada en phpMyAdmin.
ALTER TABLE public_form_settings ADD COLUMN cover_image VARCHAR(255) NULL AFTER slug;
