<?php
session_start();
require_once __DIR__ . '/../entity/CarretCompra.php';

// Verificar si la solicitud es POST y contiene los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducte'], $_POST['novaQuantitat'])) {
    $id = intval($_POST['idProducte']);
    $novaQuantitat = intval($_POST['novaQuantitat']);

    if (isset($_SESSION['carret'])) {
        $carret = unserialize($_SESSION['carret']);

        // Cambiar la cantidad solo si es válida (mayor a 0)
        if ($novaQuantitat > 0) {
            $carret->canviarQuantitatProducte($id, $novaQuantitat);
            $_SESSION['mensaje'] = 'Cantidad actualizada correctamente';
        } else {
            $_SESSION['mensaje'] = 'La cantidad debe ser mayor que 0';
        }

        $_SESSION['carret'] = serialize($carret);
    }
}

// Redireccionar de nuevo al carrito (ajustar ruta al index correcto)
$redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . '/wwwModestLuis2/index.php?apartat=botiga&mostrar=carret';
header('Location: ' . $redirect_url);
exit();
?>
