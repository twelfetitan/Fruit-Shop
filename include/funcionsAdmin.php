<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/entity/CredencialsBD.php';
require_once 'funcions.php';
function gestionaProductes() {
    $conn = new mysqli('localhost', CredencialsBD::USUARI, CredencialsBD::CONTRASENYA, 'projectePHPModestLuis');

    if ($conn->connect_error) {
        die("Error de connexió: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT id, nom, descripcio, preu, estoc, imatge FROM productes");

    if (!$result) {
        echo "<p style='color:red;'>Error en obtenir els productes: " . $conn->error . "</p>";
        return;
    }

    // Enllaç per afegir un nou producte
    echo '<a href="index.php?apartat=admin&apartatAdmin=productes&accio=afegir" class="btn">Afegir nou producte</a><br><br>';

    // Taula de productes
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; margin-top: 20px;'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Descripció</th><th>Preu</th><th>Estoc</th><th>Imatge</th><th>Accions</th></tr>";

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $nom = htmlspecialchars($row['nom']);
        $desc = htmlspecialchars($row['descripcio']);
        $preu = number_format($row['preu'], 2);
        $estoc = $row['estoc'];
        $imatge = $row['imatge'] ?? 'producteDefecte.png';

        $rutaImatge = "imagenes/productes/$imatge";

        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$nom</td>";
        echo "<td>$desc</td>";
        echo "<td>$preu €</td>";
        echo "<td>$estoc</td>";

        if (!empty($imatge)) {
            echo "<td><img src='$rutaImatge' alt='Imatge de $nom' width='80'></td>";
        } else {
            echo "<td><em>Sense imatge</em></td>";
        }

        echo "<td>";
        echo "<a href='index.php?apartat=admin&apartatAdmin=productes&accio=editar&id=$id'>Editar</a> | ";
        echo "<a href='include/eliminaProducteAdmin.php?id=$id&img=$imatge' style='color:red;' onclick='return confirm(\"Estàs segur que vols eliminar aquest producte?\");'>Eliminar</a>";
        echo "</td>";

        echo "</tr>";
    }

    echo "</table>";

    $conn->close();
}

function afegirProducte() {
    echo <<<HTML
    <form action="include/processaAfegirProducte.php" method="POST" enctype="multipart/form-data">
        <label>Nom: <input type="text" name="nom" required></label><br>
        <label>Descripció: <textarea name="descripcio"></textarea></label><br>
        <label>Preu: <input type="number" step="0.01" name="preu" required></label><br>
        <label>Estoc: <input type="number" name="estoc" required></label><br>
        <label>Imatge: <input type="file" name="imatge"></label><br><br>
        <button type="submit">Afegir Producte</button>
    </form>
    <br>
    <a href="index.php?apartat=admin&apartatAdmin=productes">Tornar</a>
HTML;
}

function editarProducte($id) {
    $conn = new mysqli('localhost', CredencialsBD::USUARI, CredencialsBD::CONTRASENYA, 'projectePHPModestLuis');
    $stmt = $conn->prepare("SELECT nom, descripcio, preu, estoc, imatge FROM productes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producte = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    $nom = htmlspecialchars($producte['nom']);
    $descripcio = htmlspecialchars($producte['descripcio']);
    $preu = number_format($producte['preu'], 2);
    $estoc = intval($producte['estoc']);
    $imatgeActual = $producte['imatge'];

    echo <<<HTML
    <form action="include/processaEditarProducte.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="$id">
        <input type="hidden" name="imatgeActual" value="$imatgeActual">
        <label>Nom: <input type="text" name="nom" value="$nom" required></label><br>
        <label>Descripció: <textarea name="descripcio">$descripcio</textarea></label><br>
        <label>Preu: <input type="number" name="preu" step="0.01" value="$preu" required></label><br>
        <label>Estoc: <input type="number" name="estoc" value="$estoc" required></label><br>
        <label>Imatge nova (opcional): <input type="file" name="imatge"></label><br>
        <p>Imatge actual: $imatgeActual</p>
        <button type="submit">Desar canvis</button>
    </form>
    <br>
    <a href="index.php?apartat=admin&apartatAdmin=productes">Tornar</a>
HTML;
}

?>
