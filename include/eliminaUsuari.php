<?php
if (!isset($_GET['email'])) {
    echo "<p class='error-msg'>Error: No se proporcionó un email.</p>";
    exit;
}

$email_a_eliminar = $_GET['email'];
$ruta_archivo = __DIR__ . '/../usuaris/passwd.txt';

// Verificar si el archivo existe
if (!file_exists($ruta_archivo)) {
    echo "<p class='error-msg'>Error: No se encontró el archivo de usuarios.</p>";
    exit;
}


// Leer todas las líneas del archivo
$usuarios = file($ruta_archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$nuevos_usuarios = [];

foreach ($usuarios as $usuario) {
    $datos = explode(':', $usuario);
    if (count($datos) < 3) continue;

    list($nom, $email, $contrasenya) = $datos;

    // No añadir a la nueva lista si el email coincide con el que se quiere eliminar
    if ($email !== $email_a_eliminar) {
        $nuevos_usuarios[] = $usuario;
    }
}

// Reescribir el archivo sin el usuario eliminado
file_put_contents($ruta_archivo, implode("\n", $nuevos_usuarios) . "\n");

// Redirigir de nuevo a la página de administración
header("Location: ../index.php?apartat=admin");
exit;
?>
