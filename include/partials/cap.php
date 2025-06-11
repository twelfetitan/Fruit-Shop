<?php
if (isset($_POST['estil']) && !empty($_POST['estil'])) {
    setcookie('estil', $_POST['estil'], time() + 3600, "/");
    $archivocss = $_POST['estil'];
} elseif (isset($_COOKIE['estil'])) {
    $archivocss = $_COOKIE['estil'];
} else {
    $archivocss = "default.css";
}
?>



<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?apartat=' . $apartat; ?>">
    <label for="estil">Canvia l'estil:</label>
    <select name="estil" id="estil">
        <option value="default.css" <?php if ($archivocss == 'default.css') echo 'selected'; ?>>Estil per defecte</option>
        <option value="estilsregistre1.css" <?php if ($archivocss == 'estilsregistre1.css') echo 'selected'; ?>>Estil 1 (Morat)</option>
        <option value="estilsregistre2.css" <?php if ($archivocss == 'estilsregistre2.css') echo 'selected'; ?>>Estil 2 (Groc)</option>
    </select>
    <button type="submit">Aplicar</button>
</form>



