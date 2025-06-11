<link rel="stylesheet" href="css/log.css">
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validación de rol
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "<p style='color:red;'>Acceso denegado.</p>";
    exit;
}

// Incluye el menú de navegación para admin
include 'include/partials/menuadmin.php';

// Incluye las funciones necesarias
require_once 'include/funcions.php';
require_once 'include/funcionsAdmin.php';
require_once __DIR__ . '/../gestionaUsuaris.php';

// Lee qué sección quiere ver el admin
$apartatActual = $_GET['apartatAdmin'] ?? 'usuaris';

$accio = $_GET['accio'] ?? null;
$idEditar = isset($_GET['id']) ? intval($_GET['id']) : null;


echo "<h1>Administración</h1>";

// Mostrar contenido según el apartado actual
switch ($apartatActual) {
    case 'productes':
        echo "<h2>Gestión de Productos</h2>";
if ($accio === 'afegir') {
    afegirProducte(); // te lo doy en el siguiente paso
} elseif ($accio === 'editar' && $idEditar !== null) {
    editarProducte($idEditar); // te lo doy también
} else {
    gestionaProductes();
}
        break;

    case 'usuaris':
    default:
        echo "<h2>Gestión de Usuarios</h2>";
        gestionaUsuaris();
        break;
}

// Mostrar u ocultar el log
$mostrarLog = $_GET['mostrarLog'] ?? 'false';

echo "<h2>Control de Registro</h2>";
echo '<a href="index.php?apartat=admin&apartatAdmin=' . $apartatActual . '&mostrarLog=true">Mostrar Log</a> | ';
echo '<a href="index.php?apartat=admin&apartatAdmin=' . $apartatActual . '&mostrarLog=false">Ocultar Log</a><br><br>';

if ($mostrarLog === 'true') {
    mostraLog();
}
?>
