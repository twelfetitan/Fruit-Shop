<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../entity/CarretCompra.php');

// Verificar que hay un carrito
if (!isset($_SESSION['carret'])) {
    echo "<p class='alert'>No hay carrito activo.</p>";
    echo '<a href="../../index.php?apartat=botiga" class="volver-btn">Volver a la tienda</a>';
    exit;
}

$carret = unserialize($_SESSION['carret']);

// Guardar pedido en base de datos (si tienes esa parte)
// ...

// Vaciar carrito
$carret->buidarCarret();
$_SESSION['carret'] = serialize($carret);

// Mostrar mensaje de éxito
echo "<div class='mensaje-compra'>
        <h2>Compra realizada</h2>
        <p>Gracias por tu pedido.</p>
        <a href='../../index.php?apartat=botiga' class='volver-btn'>Volver a la tienda</a>
      </div>";

echo
"<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

.mensaje-compra {
    text-align: center;
    margin-top: 100px;
    padding: 20px;
}

.mensaje-compra h2 {
    color: #28a745;
    margin-bottom: 10px;
}

.mensaje-compra p {
    color: #555;
    margin-bottom: 20px;
}

.volver-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
}

.volver-btn:hover {
    background-color: #0056b3;
}

.alert {
    padding: 10px;
    background-color: #f8d7da;
    color: #721c24;
    margin: 20px;
    border-radius: 4px;
}
</style>";
?>
