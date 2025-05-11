<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    error_log("Error: No se pudo cargar el archivo .env. " . $e->getMessage());
}

define('SGBD', $_ENV['DB_SGBD']);
define('HOST', $_ENV['DB_HOST']);
define('DB', $_ENV['DB_DATABASE']);
define('USER', $_ENV['DB_USERNAME']);
define('PASSWORD', $_ENV['DB_PASSWORD']);
define('UPLOADDIR', $_ENV['UPLOAD_DIR']);

define('MAILHOST', $_ENV['MAIL_HOST']);
define('MAILSMTP', $_ENV['MAIL_DRIVER']);
define('MAILPORT', $_ENV['MAIL_PORT']);
define('MAILUSER', $_ENV['MAIL_USERNAME']);
define('MAILPASSWORD', $_ENV['MAIL_PASSWORD']);

define('MP_ACCESS_TOKEN', $_ENV['MP_ACCESS_TOKEN']);
define('MP_PUBLIC_KEY', $_ENV['MP_PUBLIC_KEY']);
define('MP_ENVIRONMENT', $_ENV['MP_ENVIRONMENT']);

if (!MP_ACCESS_TOKEN) {
    error_log("ADVERTENCIA: MP_ACCESS_TOKEN no está definida. Revisa tu archivo .env y config.php.");
}

?>