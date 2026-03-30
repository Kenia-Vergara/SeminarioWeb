<?php
/**
 * Sistema de log para desarrollo.
 * Incluir al inicio de CADA archivo PHP del proyecto.
 * Escribe en logs/app.log — NUNCA muestra errores en el navegador.
 *
 * Funciona sin importar ninguna otra clase del proyecto.
 */

// Ruta absoluta al archivo de log
 $logFile = __DIR__ . '/logs/app.log';
 $logDir  = dirname($logFile);

// Crear carpeta si no existe
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// Forzar que los errores vayan al archivo, no al navegador
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', $logFile);

// Capturar warnings también
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $type = match ($errno) {
        E_WARNING, E_USER_WARNING, E_DEPRECATED, E_USER_DEPRECATED => 'WARNING',
        E_NOTICE, E_USER_NOTICE => 'NOTICE',
        E_STRICT, E_RECOVERABLE_ERROR => 'STRICT',
        default => 'ERROR'
    };
    $date = date('Y-m-d H:i:s');
    $msg = "[$date] [$type] $errstr in $errfile:$errline";
    file_put_contents($logFile, $msg . PHP_EOL, FILE_APPEND);

    // Los warnings no deben detener la ejecución
    return false;
});

// Registrar shutdown para capturar errores fatales
register_shutdown_function(function () {
    $err = error_get_last();
    if ($err) {
        $date = date('Y-m-d H:i:s');
        $msg = "[$date] [FATAL] {$err['message']} in {$err['file']}:{$err['line']}";
        file_put_contents($logFile, $msg . PHP_EOL, FILE_APPEND);
    }
});