<?php
declare(strict_types=1);

// Reparación de un solo uso. Por seguridad solo funciona en el servidor local.
$remoteAddress = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($remoteAddress, ['127.0.0.1', '::1'], true)) {
    http_response_code(403);
    exit('Esta reparación solo puede ejecutarse desde localhost.');
}

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $file = __DIR__ . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

try {
    $database = App\Core\Database::connection();
    $temporaryPassword = 'password';
    $passwordHash = password_hash($temporaryPassword, PASSWORD_DEFAULT);

    $update = $database->prepare(
        "UPDATE users SET password = ?, active = 1
         WHERE email IN ('admin@rutadocente.cl', 'docente@rutadocente.cl')"
    );
    $update->execute([$passwordHash]);

    $verification = $database->query(
        "SELECT email, password FROM users
         WHERE email IN ('admin@rutadocente.cl', 'docente@rutadocente.cl')"
    )->fetchAll();

    $verifiedEmails = [];
    foreach ($verification as $user) {
        if (password_verify($temporaryPassword, $user['password'])) {
            $verifiedEmails[] = $user['email'];
        }
    }

    if (count($verifiedEmails) !== 2) {
        throw new RuntimeException('No se pudieron verificar las dos cuentas.');
    }

    // Elimina esta herramienta de la instalación local después de usarla.
    $removed = @unlink(__FILE__);

    header('Content-Type: text/html; charset=utf-8');
    echo '<h1>Acceso reparado correctamente</h1>';
    echo '<p>Las dos cuentas fueron activadas y verificadas por PHP.</p>';
    echo '<p><a href="login">Ir al acceso docente</a></p>';
    echo $removed
        ? '<p>La herramienta temporal se eliminó automáticamente.</p>'
        : '<p>Elimina manualmente reparar-acceso.php antes de publicar el sitio.</p>';
} catch (Throwable $error) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'No se pudo reparar el acceso: ' . $error->getMessage();
}
