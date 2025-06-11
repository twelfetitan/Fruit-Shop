<?php
        session_start();
        $email = "";
        if (isset($_POST['email']) && $_POST['email'] != "") {
            $email = trim(htmlspecialchars($_POST['email']));
        } else {
            $email = "Sense Valor";
        }

        $asunto = "";
        if (isset($_POST['asunto']) && $_POST['asunto'] != "") {
            $asunto = trim(htmlspecialchars($_POST['asunto']));
        } else {
            $asunto = "Sense Valor";
        }

        $mensaje = "";
        if (isset($_POST['mensaje']) && $_POST['mensaje'] != "") {
            $mensaje = trim(htmlspecialchars($_POST['mensaje']));
        } else {
            $mensaje = "Sense Valor";
        }

        $puntua = "";
        if (isset($_POST['puntua']) && $_POST['puntua'] != ""){
            $puntua = trim(htmlspecialchars($_POST['puntua']));
        }else{
            $puntua = "Sense valor";
        }

        $slider = "";
        if (isset($_POST['slider']) && $_POST['slider'] != ""){
            $slider = trim(htmlspecialchars($_POST['slider']));
        }


        $multiplicacion = $slider * $puntua;

        

        
        
        

        ?>
        <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruiteria Verduleria Online - Confirmació del Registre</title>
    <link rel="stylesheet" href="../css/contacte.css">
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
        
        
        echo "El teu email és: ".$email . "<br>";

        
        echo "El teu asunto és: ".$asunto . "<br>";

        
        echo "La teua missatge és: ".$mensaje . "<br>";

        

        




        echo "Puntuacion de la pagina:";
        echo '<br />';
        while($multiplicacion-- > 0)
        echo '<img src="../imagenes/punto.png" alt="Puntos" height ="100px">';

        
        
        

        ?>

        <div class="botons">
           
            <a href="../index.php?apartat=contacte" class="boton-regresar">Tornar al formulari</a>
        </div>
    </main>

    <?php
    
    include 'peu.php';
    ?>
</body>
</html>
