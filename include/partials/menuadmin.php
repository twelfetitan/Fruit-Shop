<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "<p style='color:red;'>Acceso denegado.</p>";
    exit;
}

$apartatActual = $_GET['apartatAdmin'] ?? 'usuaris';

$items = [
    'usuaris' => 'Gestión Usuarios',
    'productes' => 'Gestión Productos',
    'comandes' => 'Gestión Comandes',
];

echo "<nav class='menu-admin'>";
foreach ($items as $key => $label) {
    if ($key === $apartatActual) {
        echo "<span class='actiu'>$label</span> | ";
    } else {
        echo "<a href='index.php?apartat=admin&apartatAdmin=$key'>$label</a> | ";
    }
}
echo "</nav><br>";
?>
