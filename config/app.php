<?php
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$detectedBaseUrl = preg_replace('#/(?:public/)?index\.php$#', '', $scriptName) ?? '';

return [
    'name' => 'Ruta Docente',
    // Detecta automáticamente instalaciones como /rutadocente y también
    // funciona cuando el dominio apunta directamente a la carpeta public.
    'base_url' => rtrim((string) (getenv('APP_BASE_URL') ?: $detectedBaseUrl), '/'),
    'upload_dir' => dirname(__DIR__) . '/storage/uploads',
    'max_upload_mb' => 15,
    'allowed_extensions' => ['pdf','doc','docx','xls','xlsx','ppt','pptx','zip'],
];
