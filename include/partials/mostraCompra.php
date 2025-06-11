<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../entity/CarretCompra.php');

// Comprobar si hay carrito
if (isset($_SESSION['carret'])) {
    $carret = unserialize($_SESSION['carret']);

    echo "<div class='carrito-container'>";

    if (method_exists($carret, 'mostrarCarretCompra')) {
        echo $carret->mostrarCarretCompra();
    } else {
        echo "<p class='alert'>Error: El carrito no tiene el método mostrarCarretCompra().</p>";
    }

    // Si el usuario no está logueado
    if (!isset($_SESSION['usuari'])) {
        echo '<p class="alert">Debes iniciar sesión para confirmar la compra.</p>';
        echo '<a href="index.php?apartat=login" class="button">Iniciar sesión</a>';
    } else {
        echo '<form method="post" action="include/partials/processaComanda.php" class="confirmar-compra-form">';
        echo '<button type="submit" class="pagar-btn">Confirmar compra</button>';
        echo '</form>';
    }

    echo "</div>";
} else {
    echo "<div class='carrito-container'>";
    echo "<p class='alert'>No hay ningún carrito activo.</p>";
    echo '<a href="index.php?apartat=botiga" class="seguir-btn">🔙 Volver a la tienda</a>';
    echo "</div>";
}
?>


<style>
.carrito-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    font-family: Arial, sans-serif;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.alert {
    padding: 10px;
    background-color: #f8d7da;
    color: #721c24;
    margin-bottom: 15px;
    border-radius: 5px;
}

.button, .seguir-btn, .pagar-btn {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 10px;
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
    text-align: center;
}

.button {
    background-color: #007bff;
    color: white;
}

.seguir-btn {
    background-color: #6c757d;
    color: white;
}

.pagar-btn {
    background-color: #28a745;
    color: white;
    border: none;
}

.button:hover, .seguir-btn:hover, .pagar-btn:hover {
    opacity: 0.9;
}

.confirmar-compra-form {
    margin-top: 20px;
}

.confirmar-compra-form button {
    padding: 10px 20px;
    font-size: 16px;
}
</style>