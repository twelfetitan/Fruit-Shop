<?php
session_start();
require_once __DIR__ . '/entity/CredencialsBD.php';
require_once 'funcions.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Accés denegat.");
}

$id = intval($_POST['id']);
$nom = $_POST['nom'] ?? '';
$descripcio = $_POST['descripcio'] ?? '';
$preu = floatval($_POST['preu']);
$estoc = intval($_POST['estoc']);
$imatgeActual = $_POST['imatgeActual'] ?? 'producteDefecte.png';
$imatgeFinal = $imatgeActual;

if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] === 0) {
    $nomNou = basename($_FILES['imatge']['name']);
    $rutaNova = __DIR__ . '/../imagenes/productes/' . $nomNou;
    if (move_uploaded_file($_FILES['imatge']['tmp_name'], $rutaNova)) {
        $imatgeFinal = $nomNou;

        $rutaAntiga = __DIR__ . '/../imagenes/productes/' . $imatgeActual;
        if ($imatgeActual !== 'producteDefecte.png' && file_exists($rutaAntiga)) {
            unlink($rutaAntiga);
        }
    }
}

$conn = new mysqli('localhost', CredencialsBD::USUARI, CredencialsBD::CONTRASENYA, 'projectePHPModestLuis');
$stmt = $conn->prepare("UPDATE productes SET nom = ?, descripcio = ?, preu = ?, estoc = ?, imatge = ? WHERE id = ?");
$stmt->bind_param("ssdssi", $nom, $descripcio, $preu, $estoc, $imatgeFinal, $id);
$stmt->execute();

escriureLog($_SESSION['usuari'] ?? 'admin', "Editat producte ID: $id");

$stmt->close();
$conn->close();

header("Location: ../index.php?apartat=admin&apartatAdmin=productes");
exit;
