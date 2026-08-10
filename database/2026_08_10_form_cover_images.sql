-- Imagen de portada para las tarjetas públicas de talleres.
USE rutadocente;
ALTER TABLE public_form_settings ADD COLUMN cover_image VARCHAR(255) NULL AFTER slug;
