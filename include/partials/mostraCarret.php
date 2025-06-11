<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../entity/CredencialsBD.php');
require_once(__DIR__ . '/../entity/CarretCompra.php');

// Verificar si hay carrito
if (!isset($_SESSION['carret'])) {
    echo "<p class='alert'>No hay productos en el carrito</p>";
    echo '<a href="index.php?apartat=botiga" class="button">Volver a la tienda</a>';
    return;
}

// Obtener carrito de la sesión
$carret = unserialize($_SESSION['carret']);
$productes = $carret->getLlistaProductes();

if (empty($productes)) {
    echo "<p class='alert'>El carrito está vacío</p>";
    echo '<a href="index.php?apartat=botiga" class="button">Volver a la tienda</a>';
    return;
}

// Conexión a la base de datos
$conn = new mysqli('localhost', CredencialsBD::USUARI, CredencialsBD::CONTRASENYA, 'projectePHPModestLuis');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener IDs de productos
$ids = array_map(function($item) {
    return $item['producte']->getId();
}, $productes);

// Consulta para obtener información de productos
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$sql = "SELECT id, nom, preu FROM productes WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
$stmt->execute();
$result = $stmt->get_result();

// Almacenar información de productos
$productesInfo = [];
while ($row = $result->fetch_assoc()) {
    $productesInfo[$row['id']] = $row;
}
$stmt->close();

// Configurar la ruta base
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$base_url = rtrim($base_url, '/\\');

// Mostrar tabla del carrito
echo "<div class='carrito-container'>";
echo "<h2>Tu Carrito</h2>";
echo "<table class='carrito-table'>";
echo "<tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th><th>Eliminar</th></tr>";

$totalCompra = 0;

foreach ($productes as $id => $info) {
    if (!isset($productesInfo[$id])) continue;
    
    $producte = $productesInfo[$id];
    $quantitat = $info['quantitat'];
    $preu = $producte['preu'];
    $total = $quantitat * $preu;
    $totalCompra += $total;

    echo "<tr>";
    echo "<td>".htmlspecialchars($producte['nom'])."</td>";
    echo "<td>".number_format($preu, 2)." €</td>";
    echo "<td>
            <form method='post' action='".$base_url."/include/partials/canviaQuantitatProducte.php' class='form-cantidad'>
                <input type='hidden' name='idProducte' value='".htmlspecialchars($id)."' />
                <input type='number' name='novaQuantitat' value='".htmlspecialchars($quantitat)."' 
                       min='1' step='1' class='cantidad-input' required />
                <button type='submit' class='actualizar-btn'>✓</button>
            </form>
          </td>";
    echo "<td>".number_format($total, 2)." €</td>";
    echo "<td>
            <form method='post' action='".$base_url."/include/partials/eliminaProducteCarret.php' class='form-eliminar'>
                <input type='hidden' name='idProducte' value='".htmlspecialchars($id)."' />
                <button type='submit' class='eliminar-btn'>X</button>
            </form>
          </td>";
    echo "</tr>";
}

echo "<tr class='total-row'>
        <td colspan='3'>Total:</td>
        <td>".number_format($totalCompra, 2)." €</td>
        <td></td>
      </tr>";
echo "</table>";

// Botones de acción
echo "<div class='acciones-carrito'>";
echo '<a href="index.php?apartat=botiga" class="seguir-btn">Seguir comprando</a>';
echo '<a href="index.php?apartat=botiga&mostrar=compra" class="pagar-btn">Pagar ahora</a>';
echo "</div>";

echo "</div>";
$conn->close();
?>

<style>
.carrito-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.carrito-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.carrito-table th, .carrito-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.carrito-table th {
    background-color: #f2f2f2;
}

.total-row {
    font-weight: bold;
    background-color: #f9f9f9;
}

.form-cantidad, .form-eliminar {
    display: flex;
    align-items: center;
    gap: 5px;
}

.cantidad-input {
    width: 60px;
    padding: 5px;
}

.actualizar-btn {
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.eliminar-btn {
    padding: 5px 10px;
    background-color: #f44336;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.actualizar-btn:hover {
    background-color: #45a049;
}

.eliminar-btn:hover {
    background-color: #d32f2f;
}

.acciones-carrito {
    display: flex;
    justify-content: space-between;
}

.seguir-btn, .pagar-btn {
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 4px;
}

.seguir-btn {
    background-color: #6c757d;
    color: white;
}

.pagar-btn {
    background-color: #28a745;
    color: white;
}

.seguir-btn:hover, .pagar-btn:hover {
    opacity: 0.9;
}

.alert {
    padding: 10px;
    background-color: #f8d7da;
    color: #721c24;
    margin-bottom: 15px;
}

.button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}
</style>