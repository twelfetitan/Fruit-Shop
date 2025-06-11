<?php

function guardarUsuari(string $nom, string $email, string $contrasenya): bool {
    // Convertir nombre y correo a minúsculas
    $nom = strtolower(trim($nom));
    $email = strtolower(trim($email));
    $contrasenya = trim($contrasenya);

    // Usar una ruta relativa para garantizar la ubicación correcta del archivo
    $ubicacio = '../usuaris/passwd.txt';  // Subir un solo nivel desde 'include'

    // Verificar si el archivo existe
    if (!file_exists($ubicacio)) {
        error_log("El archivo no existe: $ubicacio");
        return false; // El archivo no existe, no podemos guardar los datos
    }

    // Verificar si podemos leer el contenido del archivo
    $contingut = file($ubicacio, FILE_IGNORE_NEW_LINES);
    if ($contingut === false) {
        error_log("No se puede leer el archivo: $ubicacio");
        return false;  // Si no se puede leer el archivo, retornamos false
    }

    // Comprobar si el correo ya está registrado
    foreach ($contingut as $linia) {
        $linia = trim($linia);
        $dades = explode(":", $linia);

        // Asegurar que la línea tiene al menos 2 elementos (evitar errores)
        if (count($dades) >= 2) {
            $correu = trim($dades[1]); // Segundo elemento es el correo
            if ($correu === $email) {
                error_log("El correo ya está registrado: $email");
                return false;
            }
        }
    }
    

    // Cifrar la contrasenya antes de guardarla
    $contrasenyaCifrada = password_hash($contrasenya, PASSWORD_DEFAULT);

    // Añadir el nuevo usuario al archivo
    $novaLinia = "$nom:$email:$contrasenyaCifrada" . PHP_EOL;

    // Verificar si la escritura en el archivo es exitosa
    $resultado = file_put_contents($ubicacio, $novaLinia, FILE_APPEND);
    if ($resultado === false) {
        error_log("Error al escribir en el archivo: $ubicacio");
        return false;  // Si no se puede escribir en el archivo, retornamos false
    }

    return true;  // El usuario se ha agregado correctamente
}



function mostrarMissatge(string $email, bool $estat): void
{
    if ($estat) {
        echo "<p style='color: green;'>Usuari amb correu $email registrat correctament.</p>";
    } else {
        echo "<p style='color: red;'>Error: L'usuari amb correu $email ja existeix o hi ha hagut un problema.</p>";
    }
}
function mostraProductes($rutaFitxer) {
    // Incluir el archivo de productos
    include($rutaFitxer);

    // Verificar que el array de productos no esté vacío
    if (!empty($productes)) {
        // Recorrer el array de productos y mostrar la información
        foreach ($productes as $id => $producte) {
            echo "<div class='producte'>";
            echo "<h3>" . htmlspecialchars($producte['nom']) . "</h3>";
            echo "<img src='imatges/productes/" . htmlspecialchars($producte['imatge']) . "' alt='" . htmlspecialchars($producte['nom']) . "' />";
            echo "<p>" . htmlspecialchars($producte['descripcio']) . "</p>";
            echo "<p>Preu: €" . number_format($producte['preu'], 2) . "</p>";
            // Mostrar el formulario para añadir el producto a la cesta
            mostraFormulariProducte($id);
            echo "</div>";
        }
    } else {
        echo "<p>No hi ha productes disponibles.</p>";
    }
}

function mostraFormulariProducte($id) {
    echo "<form action='index.php?apartat=botiga' method='post'>";
    echo "<label for='quantitat_$id'>Quantitat:</label>";
    echo "<input type='number' id='quantitat_$id' name='quantitat' value='1' min='1' required />";
    echo "<input type='hidden' name='producte_id' value='$id' />";
    echo "<button type='submit'>Afegir a la cistella</button>";
    echo "</form>";
}

//log
function escriureLog(string $usuari, string $accio): void {
    $arrelProjecte = dirname(__FILE__, 2); 
    $carpetaLog = $arrelProjecte . '/log';


    if (!file_exists($carpetaLog)) {
        mkdir($carpetaLog, 0777, true);
    }

    $fitxerLog = $carpetaLog . '/registre.log';

    $dataHora = date('Y-m-d H:i:s');
    $linia = "[$dataHora] Usuari: $usuari - Acció: $accio" . PHP_EOL;

    file_put_contents($fitxerLog, $linia, FILE_APPEND);
}

function mostraLog(): void {
    $arrelProjecte = dirname(__FILE__, 2); 
    $fitxerLog = $arrelProjecte . '/log/registre.log';

    if (!file_exists($fitxerLog)) {
        echo "<p class='log-error'>El fitxer de registre no existeix.</p>";
        return;
    }

    $linies = file($fitxerLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    echo "<div class='log-container'>";
    foreach ($linies as $linia) {
        if (strpos($linia, 'Registre') !== false) {
            $classe = 'log-registre';
        } elseif (strpos($linia, 'Login correcte') !== false) {
            $classe = 'log-login-correcte';
        } elseif (strpos($linia, 'Login incorrecte') !== false) {
            $classe = 'log-login-incorrecte';
        } elseif (strpos($linia, 'Logout') !== false) {
            $classe = 'log-logout';
        } elseif (strpos($linia, 'Eliminació') !== false) {
            $classe = 'log-eliminacio';
        } else {
            $classe = 'log-general';
        }

        echo "<div class='$classe'>" . htmlspecialchars($linia) . "</div>";
    }
    echo "</div>";
}
function mostraProductesBD($page = null) {
    include_once(__DIR__ . '/entity/CredencialsBD.php');

    $servidor = "localhost";
    $usuari = CredencialsBD::USUARI;
    $contrasenya = CredencialsBD::CONTRASENYA;
    $basedades = "projectePHPModestLuis";

    $connexio = new mysqli($servidor, $usuari, $contrasenya, $basedades);

    if ($connexio->connect_error) {
        echo "<p class='log-error'>No es pot connectar amb la base de dades: " . $connexio->connect_error . "</p>";
        return;
    }

    $perPage = PRODUCTS_PER_PAGE;
    $page = $page ?? 1;
    $offset = ($page - 1) * $perPage;

    // Obtener el número total de productos
    $sqlCount = "SELECT COUNT(*) as total FROM productes";
    $resultCount = $connexio->query($sqlCount);
    $totalProducts = $resultCount->fetch_assoc()['total'];
    $totalPages = ceil($totalProducts / $perPage);

    $sql = "SELECT * FROM productes LIMIT $perPage OFFSET $offset";
    $resultat = $connexio->query($sql);

    if ($resultat->num_rows > 0) {
        while ($producte = $resultat->fetch_assoc()) {
            echo "<div class='producte'>";
            echo "<h3>" . htmlspecialchars($producte['nom']) . "</h3>";
            echo "<img src='imagenes/productes/" . htmlspecialchars($producte['imatge']) . "' alt='" . htmlspecialchars($producte['nom']) . "' width='150 px' />";
            echo "<p>" . htmlspecialchars($producte['descripcio']) . "</p>";
            echo "<p>Preu: €" . number_format($producte['preu'], 2) . "</p>";
            mostraFormulariProducte($producte['id']);
            echo "</div>";
        }
    } else {
        echo "<p>No hi ha productes disponibles.</p>";
    }

    // Mostrar la paginación
    echo "<div class='pagination' style='display: flex; gap: 8px; justify-content: center; margin-top: 20px;'>";
    if ($page > 1) {
        echo "<a href='?apartat=botiga&page=1' style='padding: 8px 12px; background-color: #007bff; color: #fff; border-radius: 5px; text-decoration: none; transition: background-color 0.3s;'>Inici</a>";
        echo "<a href='?apartat=botiga&page=" . ($page - 1) . "' style='padding: 8px 12px; background-color: #007bff; color: #fff; border-radius: 5px; text-decoration: none; transition: background-color 0.3s;'>&laquo; Anterior</a>";
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?apartat=botiga&page=$i'" . ($i === $page ? " style='padding: 8px 12px; background-color: #28a745; color: #fff; border-radius: 5px; text-decoration: none; font-weight: bold;'" : " style='padding: 8px 12px; background-color: #007bff; color: #fff; border-radius: 5px; text-decoration: none; transition: background-color 0.3s;'") . ">$i</a>";
    }

    if ($page < $totalPages) {
        echo "<a href='?apartat=botiga&page=" . ($page + 1) . "' style='padding: 8px 12px; background-color: #007bff; color: #fff; border-radius: 5px; text-decoration: none; transition: background-color 0.3s;'>Següent &raquo;</a>";
        echo "<a href='?apartat=botiga&page=$totalPages' style='padding: 8px 12px; background-color: #007bff; color: #fff; border-radius: 5px; text-decoration: none; transition: background-color 0.3s;'>Final</a>";
    }
    echo "</div>";

    $connexio->close();
}

function usuariAutenticat(): bool {
    return isset($_SESSION['usuari']) && !empty($_SESSION['usuari']);
}



?>