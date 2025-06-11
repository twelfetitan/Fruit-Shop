<?php
function gestionaUsuaris()
{
    // Ruta del archivo de usuarios
    $ruta_archivo = __DIR__ . '/../usuaris/passwd.txt';
    if (!file_exists($ruta_archivo)) {
        echo "<p class='error-msg'>Error: No se encuentra el archivo de usuarios.</p>";
        return;
    }

    $usuarios = file($ruta_archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Incluir el CSS
    echo "<link rel='stylesheet' type='text/css' href='../css/tablaUsuarios.css'>";

    echo "<table class='user-table'>";
    echo "<tr><th>#</th><th>Nombre</th><th>Email</th><th>Contraseña</th><th>Acción</th></tr>";

    $contador = 1;
    foreach ($usuarios as $usuario) {
        $datos = explode(':', $usuario);
        if (count($datos) < 3)
            continue; // Validar datos correctos

        list($nom, $email, $contrasenya) = $datos;

        echo "<tr>";
        echo "<td>{$contador}</td>";
        echo "<td>{$nom}</td>";
        echo "<td>{$email}</td>";
        echo "<td>{$contrasenya}</td>";

        // No permitir eliminar el usuario admin
        if ($email !== 'admin@dam.com') {
            echo "<td>
        <a href='include/eliminaUsuari.php?email=" . urlencode($email) . "'>
            Eliminar
        </a>
      </td>";

        } else {
            echo "<td><img src='../img/admin_icon.png' alt='Admin' class='admin-icon'/></td>"; // Icono para admin
        }

        echo "</tr>";
        $contador++;
    }
    echo "</table>";
}
?>