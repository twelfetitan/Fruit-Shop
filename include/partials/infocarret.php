<?php
require_once __DIR__ . '/../entity/Producte.php';
require_once __DIR__ . '/../entity/CarretCompra.php';
require_once __DIR__ . '/../entity/CredencialsBD.php';

if (isset($_SESSION['carret'])) {
    $carret = unserialize($_SESSION['carret']);
    $productes = $carret->getLlistaProductes();

    if (!empty($productes)) {
        // Connexió a la BBDD per obtenir noms i preus actualitzats
        $connexio = new mysqli("localhost", CredencialsBD::USUARI, CredencialsBD::CONTRASENYA, "projectePHPModestLuis");

        if ($connexio->connect_error) {
            echo "<p class='log-error'>Error de connexió amb la base de dades.</p>";
            return;
        }

        $totalGeneral = 0;
        ?>
        <aside style="position: absolute; top: 10px; right: 10px; width: 250px; border: 2px solid #000; padding: 10px; background: #f0f0f0;">
            <h3>Carret de la compra</h3>
            <?php
            foreach ($productes as $info) {
                $producte = $info['producte'];
                $quantitat = $info['quantitat'];
            
                $id = $producte->getId();
                $nom = $producte->getNom();
                $preu = $producte->getPreu();
            
                $subtotal = $quantitat * $preu;
                $totalGeneral += $subtotal;
                ?>
                <p><strong><?php echo htmlspecialchars($nom); ?></strong></p>
                <p>Quantitat: <?php echo htmlspecialchars($quantitat); ?> Kg</p>
                <p>Preu: <?php echo number_format($preu, 2); ?> €/Kg</p>
                <p>Total: <?php echo number_format($subtotal, 2); ?> €</p>
                <hr>
                <?php
            }
            
            ?>
            <p><strong>Total general: <?php echo number_format($totalGeneral, 2); ?> €</strong></p>
    
            <a href="index.php?apartat=botiga&mostrar=carret">
    <div class="botoCarret">Ves al carret</div>
</a>
<a href="index.php?apartat=botiga&mostrar=compra">
    <div class="botoCompra">Compra</div>
</a>

        </aside>
        <?php

        $connexio->close();
    }
}
?>
