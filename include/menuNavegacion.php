<?php
// Iniciar la sessió si no està iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <!-- Enlace a "Inici", con la clase 'active' si está en esa sección -->
    <a href="index.php?apartat=inici" class="<?php echo isset($_GET['apartat']) && $_GET['apartat'] == 'inici' ? 'active' : ''; ?>"
       <?php echo isset($_GET['apartat']) && $_GET['apartat'] == 'inici' ? 'onclick="return false;"' : ''; ?>>Inici</a>

    <?php
    // Comprovar si l'usuari està autenticat
    if (!isset($_SESSION['email'])) {
        // Mostrar l'enllaç al registre només si l'usuari no està autenticat
        echo '<a href="index.php?apartat=registre" 
                 class="' . (isset($_GET['apartat']) && $_GET['apartat'] == 'registre' ? 'active' : '') . '" 
                 ' . (isset($_GET['apartat']) && $_GET['apartat'] == 'registre' ? 'onclick="return false;"' : '') . '>Registre</a>';
    }
    ?>

    <!-- Enlace a "Contacte", con la clase 'active' si está en esa sección -->
    <a href="index.php?apartat=contacte" 
       class="<?php echo isset($_GET['apartat']) && $_GET['apartat'] == 'contacte' ? 'active' : ''; ?>"
       <?php echo isset($_GET['apartat']) && $_GET['apartat'] == 'contacte' ? 'onclick="return false;"' : ''; ?>>Contacte</a>

    <!-- Enlace a "Botiga", con la clase 'active' si está en esa sección -->
    <a href="index.php?apartat=botiga" 
       class="<?php echo isset($_GET['apartat']) && $_GET['apartat'] == 'botiga' ? 'active' : ''; ?>"
       <?php echo isset($_GET['apartat']) && $_GET['apartat'] == 'botiga' ? 'onclick="return false;"' : ''; ?>>Botiga</a>
</nav>
