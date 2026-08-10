# Base de datos

Importa `rutadocente.sql` desde phpMyAdmin. El archivo crea tablas, relaciones, catálogos iniciales, un administrador, un docente de demostración y dos recursos de ejemplo.

Si la plataforma ya está instalada, ejecuta únicamente `2026_08_07_public_form.sql`. Esta migración agrega la configuración del formulario público, sus campos, las respuestas y los datos bancarios iniciales del intensivo 2026.

Para habilitar múltiples formularios en una instalación existente, ejecuta después `2026_08_10_multiple_public_forms.sql`. El sistema también aplica esta actualización automáticamente al abrir el gestor de formularios por primera vez.

La migración `2026_08_10_form_cover_images.sql` agrega la imagen de portada utilizada en la página de talleres. Esta columna también se crea automáticamente desde el panel.

La conexión no se guarda aquí: se configura de forma separada en `config/database.local.php`.
