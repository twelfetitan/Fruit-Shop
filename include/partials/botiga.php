<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once('include/funcions.php');

// Definir la cantidad de productos por página
define('PRODUCTS_PER_PAGE', 5);

// Verificar si se solicita mostrar el carrito o la compra
if (isset($_GET['mostrar'])) {
    if ($_GET['mostrar'] === 'carret') {
        include 'include/partials/mostraCarret.php';
        return;
    } elseif ($_GET['mostrar'] === 'compra') {
        include 'include/partials/mostraCompra.php';
        return;
    }
}

// Obtener la página actual desde el parámetro GET, por defecto es la página 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);

// Mostrar los productos con paginación
mostraProductesBD($page);

// Mensaje de confirmación después de agregar un producto al carrito
if (isset($_SESSION['idProducte']) && isset($_SESSION['quantitatProducte'])) {
    echo "<p style='color: green; font-weight: bold;'>Producte afegit al carret: "
        . htmlspecialchars($_SESSION['idProducte'])
        . " - Quantitat: "
        . htmlspecialchars($_SESSION['quantitatProducte'])
        . "</p>";

    unset($_SESSION['idProducte']);
    unset($_SESSION['quantitatProducte']);
}
?>
