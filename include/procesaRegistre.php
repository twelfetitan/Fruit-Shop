<?php
session_start();
require_once 'funcions.php';//Incluir archivo funciones
        
        $nom = "";
        if (isset($_POST['nom']) && $_POST['nom'] != "") {
            $nom = trim(htmlspecialchars($_POST['nom']));
        } else {
            $nom = "Sense Valor"; 
        }

        $cognoms = "";
        if (isset($_POST['cognoms']) && $_POST['cognoms'] != "") {
            $cognoms = trim(htmlspecialchars($_POST['cognoms']));
        } else {
            $cognoms = "Sense Valor";
        }

        $adreca = "";
        if (isset($_POST['adreca']) && $_POST['adreca'] != "") {
            $adreca = trim(htmlspecialchars($_POST['adreca']));
        } else {
            $adreca = "Sense Valor"; 
        }

        $email = "";
        if (isset($_POST['email']) && $_POST['email'] != "") {
            $email = trim(htmlspecialchars($_POST['email']));
        } else {
            $email = "Sense Valor";
        } 

        $contrasenya = "";
        if (isset($_POST['contrasenya']) && $_POST['contrasenya'] != "") {
            $contrasenya = trim(htmlspecialchars($_POST['contrasenya']));
        } else {
            $contrasenya = "Sense Valor";
        }

        $confirmarContrasenya = "";
        if (isset($_POST['confirmarContrasenya']) && $_POST['confirmarContrasenya'] != "") {
            $confirmarContrasenya = trim(htmlspecialchars($_POST['confirmarContrasenya']));
        } else {
            $confirmarContrasenya = "Sense Valor";
        }

        $poblacio = "";
        if (isset($_POST['poblacio']) && $_POST['poblacio'] != "") {
            $poblacio = trim(htmlspecialchars($_POST['poblacio']));
        } else {
            $poblacio = "Sense Valor";
        }
        if ($poblacio == "València") {
            $imagen = "../imagenes/valencia.jpeg";
            
        }else if ($poblacio == "Barcelona") {
            $imagen = "../imagenes/barcelona.jpg";
        }else if ($poblacio == "Madrid") {
            $imagen = "../imagenes/madrid.jpg";
        }else if ($poblacio == "Sevilla") {
            $imagen = "../imagenes/sevilla.jpg";
        }else{
            $imagen = "../imagenes/base.jpg";
    
        }

        include('dades.php');

$poblacio = "";

if (isset($_POST['poblacio']) && $_POST['poblacio'] != "") {
    $poblacio = trim(htmlspecialchars($_POST['poblacio']));

}

        


        $telefon = "";
        if (isset($_POST['telefon']) && $_POST['telefon'] != "") {
            $telefon = trim(htmlspecialchars($_POST['telefon']));
        } else {
            $telefon = "Sense Valor";
        }

        $horari = "";
        if (isset($_POST['horari']) && $_POST['horari'] != "") {
            $horari = trim(htmlspecialchars($_POST['horari']));
        } else {
            $horari = "Sense Valor";
        }

        
        if ($contrasenya != $confirmarContrasenya) {
            // Mensaje de error si las contraseñas no coinciden
            $errorMessage = "Les contrasenyes no són iguals. Torna a intentar-ho.";
            
            // Guardar los datos ingresados para mostrarlos de nuevo en el formulario
            $_SESSION['form_data'] = $_POST;
            $_SESSION['error_message'] = $errorMessage;
            
            // Redirigir de nuevo al formulario de registro
            header("Location: ../index.php?apartat=registre");
            exit;
        }

        $ubicacio = '../../usuaris/passwd.txt';//Ruta donde se guardaran los usuarios

        $estat = guardarUsuari($nom, $email, $contrasenya, $ubicacio);//llamar a la funcion


        mostrarMissatge($email, $estat);//Mensaje de confirmacion o error
        ?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruiteria Verduleria Online - Confirmació del Registre</title>
    <link rel="stylesheet" href="../css/registre.css">
</head>
<body>
    <?php
    include 'capçalera.php';
    include 'menuNavegacion2.php';
    include 'partials/dataPartial.php'
    ?>

    <main>
        <h1>Confirmació del Registre</h1>

        <?php
        
        echo "El teu nom és: ".$nom . "<br>";

        echo "El teus cognoms és: ".$cognoms . "<br>";

        echo "La teua adreça és: ".$adreca . "<br>";

        echo "El teu email és: ".$email . "<br>";

        echo "La teua contrasenya és: ".$contrasenya . "<br>";       

        echo "La teua població és: ".$poblacio . "<br>";
        echo "<img src='" . $imagen . "' alt='Imagen de la población seleccionada' width='50%'>" . "<br>";

        include('dades.php');


    if (isset($dadesPoblacions[$poblacio])) {
        $informacion = $dadesPoblacions[$poblacio];
        echo "<h2>Información de " . $poblacio . "</h2>";
        echo "<p><strong>Habitantes:</strong> " . $informacion['habitants'] . "</p>";
        echo "<p><strong>Comarca:</strong> " . $informacion['comarca'] . "</p>";
        echo "<p><strong>Gentilici:</strong> " . $informacion['gentilici'] . "</p>";
        echo "<p><strong>Superficie:</strong> " . $informacion['superficie'] . "</p>";
    } else {
        echo "<p>No se encontró información sobre la población ingresada.</p>";
    }

        


        echo "El teu telèfon és: ".$telefon . "<br>";

        echo "El teu horari és: ".$horari . "<br>";

        echo"<br>";
        $fruites = array();
        if (isset($_POST['fruitaPreferida']) && !empty($_POST['fruitaPreferida'])) {
            $fruites = $_POST['fruitaPreferida']; 
        
            echo "Fruites preferides:";
            echo '<br />';
        
            
            foreach ($fruites as $fruita) {
                echo "<li>" . htmlspecialchars($fruita) . "</li>";  
        
                switch ($fruita) {
                    case 'poma':
                        $imagen = "../imagenes/poma.jpg";  
                        break;
                    case 'taronja':
                        $imagen = "../imagenes/taronja.jpg";  
                        break;
                    case 'melo':
                        $imagen = "../imagenes/melo.jpg";  
                        break;
                    case 'banana':
                        $imagen = "../imagenes/banana.jpeg";  
                        break;
                    case 'raim':
                        $imagen = "../imagenes/raim.png"; 
                        break;
                    default:
                        break;
                }
        
                echo "<img src='$imagen' alt='$fruita' width='10%'>";
            }
        } else {
            $fruita = "../imagenes/base2.png";
            echo "Fruites preferides:";
            echo "<br>";
            $imagen2 = "../imagenes/base2.jpg";
            echo "<img src='" . $imagen2 . "' alt='noFruta' width='10%'>" . "<br>";
        }
        



        
        ?>

        <div class="botons">
            
            <a href="../index.php?apartat=registre" class="boton-regresar">Tornar al formulari</a>
        </div>
    </main>



<?php
    
    include 'peu.php';
    ?>
</body>
</html>