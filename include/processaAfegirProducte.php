<?php
session_start();
require_once __DIR__ . '/entity/CredencialsBD.php';
require_once 'funcions.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Accés denegat.");
}

$nom = $_POST['nom'] ?? '';
$descripcio = $_POST['descripcio'] ?? '';
$preu = floatval($_POST['preu'] ?? 0);
$estoc = intval($_POST['estoc'] ?? 0);

// Tractament imatge
$imatge = 'producteDefecte.png';
$carpeta = __DIR__ . '/../imagenes/productes/';
if (isset($_FILES['imatge']) && $_FILES['imatge']['error'] === 0) {
    $nomOriginal = basename($_FILES['imatge']['name']);
    $rutaDestino = $carpeta . $nomOriginal;

    if (move_uploaded_file($_FILES['imatge']['tmp_name'], $rutaDestino)) {
        $imatge = $nomOriginal;
    }
}

$conn = new mysqli('localhost', CredencialsBD::USUARI, CredencialsBD::CONTRASENYA, 'projectePHPModestLuis');
if ($conn->connect_error) {
    die("Error BD: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO productes (nom, descripcio, preu, estoc, imatge) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssdss", $nom, $descripcio, $preu, $estoc, $imatge);
$stmt->execute();

escriureLog($_SESSION['usuari'] ?? 'admin', "Afegit producte '$nom' amb ID: " . $stmt->insert_id);

$stmt->close();
$conn->close();

header("Location: ../index.php?apartat=admin&apartatAdmin=productes");
exit;
