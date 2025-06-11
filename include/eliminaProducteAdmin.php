<?php
session_start();
require_once __DIR__ . '/entity/CredencialsBD.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Accés denegat.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$img = $_GET['imatge'] ?? '';

$conn = new mysqli('localhost', CredencialsBD::USUARI, CredencialsBD::CONTRASENYA, 'projectePHPModestLuis');
if ($conn->connect_error) {
    die("Error de connexió: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM productes WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Eliminar imagen si existe y no es imagen por defecto
    if ($img && $img !== 'producteDefecte.png') {
         $rutaImatge = '../imagenes/productes/' . $img;
    if (file_exists($rutaImatge)) {
            unlink($rutaImatge);
        }
    }

    // Registrar log (si tienes la función)
    // escriureLog($_SESSION['nom'] ?? 'admin', "Eliminació del producte ID: $id");

    // Redirigir a la página de productos con mensaje de éxito
    header("Location: ../index.php?apartat=admin&apartatAdmin=productes&eliminat=ok");
    exit;
} else {
    echo "Error en eliminar: " . $conn->error;
}




$stmt->close();
$conn->close();
?>
