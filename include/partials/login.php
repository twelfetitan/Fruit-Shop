<?php
// Iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ubicacio = __DIR__ . '/../../usuaris/passwd.txt';
$missatge = "";

// Verificar si el archivo de usuarios existe
if (!file_exists($ubicacio)) {
    die("<p style='color: red;'>ERROR: No se ha encontrado el archivo de usuarios: $ubicacio</p>");
}

// Verificar si ya está logueado
if (isset($_SESSION['usuari'])) {
    echo "<p>Bienvenido, " . htmlspecialchars($_SESSION['nom']) . " (" . htmlspecialchars($_SESSION['email']) . ")</p>";
    echo '<form method="post"><button type="submit" name="logout">Cerrar sesión</button></form>';

    if (isset($_POST['logout'])) {
        // Cerrar sesión
        session_destroy();
        header("Location: /wwwModestLuis2/index.php");
        exit;
    }
} else {
    // Si han enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'], $_POST['contrasenya'])) {
        $email = trim($_POST['email']);
        $contrasenya = trim($_POST['contrasenya']);

        $fitxer = fopen($ubicacio, "r");
        $usuariTrobat = false;

        while (($linea = fgets($fitxer)) !== false) {
            $dades = explode(":", trim($linea));
            if (count($dades) === 3) {
                list($nomUsuari, $correu, $contrasenyaGuardada) = $dades;
                if (strtolower($correu) === strtolower($email)) {
                    if (password_verify($contrasenya, $contrasenyaGuardada)) {
                        // Login correcto
                        $_SESSION['email'] = $email;
                        $_SESSION['nom'] = $nomUsuari;
                        $_SESSION['usuari'] = $email; // ESTE es el que usa mostraCompra.php
                        $usuariTrobat = true;

                        // Rol admin o usuario
                        if ($email === 'admin@dam.com') {
                            $_SESSION['rol'] = 'admin';
                            header("Location: index.php?apartat=admin");
                            exit;
                        } else {
                            $_SESSION['rol'] = 'usuari';
                            header("Location: /wwwModestLuis2/index.php");
                            exit;
                        }
                    }
                }
            }
        }
        fclose($fitxer);

        if (!$usuariTrobat) {
            $missatge = "Error: Correo o contraseña incorrectos.";
        }
    }

    // Formulario de login
    echo '<form method="post">
    <label>Correo: <input type="email" name="email" required></label>
    <label>Contraseña: <input type="password" name="contrasenya" required></label>
    <button type="submit">Entrar</button>
</form>';

}

// Mostrar mensaje de error
if (!empty($missatge)) {
    echo "<p style='color:red;'>$missatge</p>";
}
?>
