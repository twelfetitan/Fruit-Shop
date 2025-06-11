<?php
require_once 'include/funcions.php';
require_once 'include/entity/Producte.php';
require_once 'include/entity/CarretCompra.php';
require_once 'include/entity/CredencialsBD.php';

session_start();
escriureLog('prova@example.com', 'Test de funcionament del log');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['producte_id']) && isset($_POST['quantitat'])) {
        $id = intval($_POST['producte_id']);
        $quantitat = floatval($_POST['quantitat']);

        // Connexió a la base de dades
        $connexio = new mysqli("localhost", CredencialsBD::USUARI, CredencialsBD::CONTRASENYA, "projectePHPModestLuis");
        
        if ($connexio->connect_error) {
            $_SESSION['error'] = "Error de connexió a la base de dades";
            header("Location: index.php?apartat=botiga");
            exit();
        }

        // Obtenir dades del producte
        $stmt = $connexio->prepare("SELECT nom, descripcio, preu FROM productes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // Crear l'objecte Producte amb les dades de la BD
            $producte = new Producte($id, $row['nom'],  $row['descripcio'], $row['preu'], $quantitat);

            // Recuperar o crear carret
            if (isset($_SESSION['carret'])) {
                $carret = unserialize($_SESSION['carret']);
            } else {
                $usuari = $_SESSION['email'] ?? 'visitant';
                $carret = new CarretCompra($usuari);
            }

            // Afegir producte al carret
            $carret->afegirProducte($producte, $quantitat);

            // Guardar a sessió
            $_SESSION['carret'] = serialize($carret);
            $_SESSION['idProducte'] = $id;
            $_SESSION['quantitatProducte'] = $quantitat;
            
            $connexio->close();
            
            header("Location: index.php?apartat=botiga");
            exit();
        } else {
            $connexio->close();
            $_SESSION['error'] = "Producte no trobat a la base de dades";
            header("Location: index.php?apartat=botiga");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruiteria Verduleria Online</title>
</head>

<?php include 'include/partials/infocarret.php'; ?>

<body>
    <?php
    $apartat = $_GET['apartat'] ?? "inici";

    include 'include/capçalera.php';
    include 'include/menuNavegacion.php';
    ?>

    <main>
        <?php
        switch ($apartat) {
            case 'inici':
                include 'include/partials/inici.php';
                break;

            case 'registre':
                include 'include/partials/registre.php';
                break;

            case 'contacte':
                include 'include/partials/contacte.php';
                break;

            case 'botiga':
                include 'include/partials/botiga.php';

                if (isset($_SESSION['idProducte']) && isset($_SESSION['quantitatProducte'])) {
                    echo "<p style='color: green; font-weight: bold;'>Producte afegit al carret.</p>";
                }
                break;

            case 'admin':
                if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
                    include 'include/partials/admin.php';
                } else {
                    echo "<p style='color:red;'>Accés denegat.</p>";
                }
                break;
        }
        ?>
    </main>

    <?php include 'include/peu.php'; ?>
</body>
</html>
