<?php
session_start();
require_once __DIR__ . '/../entity/CarretCompra.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducte'])) {
    if (isset($_SESSION['carret'])) {
        $carret = unserialize($_SESSION['carret']);
        $carret->eliminarProducte(intval($_POST['idProducte']));
        $_SESSION['carret'] = serialize($carret);
        
        // Mensaje de confirmación
        $_SESSION['mensaje'] = 'Producto eliminado correctamente';
    }
}

// Redirección absoluta
$redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . '/wwwModestLuis2/index.php?apartat=botiga&mostrar=carret';
header('Location: ' . $redirect_url);
exit();
?>