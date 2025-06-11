<?php
class CarretCompra {
    private $productes; // Array associatiu: id => ['producte' => obj, 'quantitat' => int]

    public function __construct() {
        $this->productes = [];
    }

    public function afegirProducte($producte, $quantitat) {
        $id = $producte->getId();

        if (!isset($this->productes[$id])) {
            $this->productes[$id] = ['producte' => $producte, 'quantitat' => $quantitat];
        } else {
            $this->productes[$id]['quantitat'] += $quantitat;
        }

        // Ordenar per ID (opcional però recomanat)
        uksort($this->productes, function($a, $b) {
            return $a <=> $b;
        });
    }

    public function eliminarProducte($id) {
        if (isset($this->productes[$id])) {
            unset($this->productes[$id]);
        }
    }

    public function canviarQuantitatProducte($id, $novaQuantitat) {
        if (isset($this->productes[$id])) {
            $this->productes[$id]['quantitat'] = $novaQuantitat;
        }
    }

    public function mostrarCarret() {
        if (empty($this->productes)) {
            return "<p>El carret està buit.</p>";
        }

        $output = "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
        $output .= "<tr><th>ID</th><th>Nom</th><th>Quantitat</th><th>Preu</th><th>Total</th></tr>";

        $totalCompra = 0;

        foreach ($this->productes as $id => $info) {
            $producte = $info['producte'];
            $quantitat = $info['quantitat'];
            $preu = $producte->getPreu();
            $nom = $producte->getNom();
            $total = $quantitat * $preu;
            $totalCompra += $total;

            $output .= "<tr>
                            <td>$id</td>
                            <td>$nom</td>
                            <td>$quantitat</td>
                            <td>" . number_format($preu, 2) . " €</td>
                            <td>" . number_format($total, 2) . " €</td>
                        </tr>";
        }

        $output .= "<tr><td colspan='4' style='text-align: right; font-weight: bold;'>Total compra:</td>
                    <td><strong>" . number_format($totalCompra, 2) . " €</strong></td></tr>";
        $output .= "</table>";

        return $output;
    }

    public function getLlistaProductes() {
        return $this->productes;
    }

    public function getQuantitatProducte($id) {
        return $this->productes[$id]['quantitat'] ?? 0;
    }
    public function mostrarCarretCompra()
{
    $productes = $this->getLlistaProductes();
    
    if (empty($productes)) {
        return "<p class='alert'>El carrito está vacío.</p>";
    }

    // Conexión a la base de datos
    $conn = new mysqli('localhost', CredencialsBD::USUARI, CredencialsBD::CONTRASENYA, 'projectePHPModestLuis');
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $ids = array_map(function($item) {
        return $item['producte']->getId();
    }, $productes);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT id, nom, preu FROM productes WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();

    $productesInfo = [];
    while ($row = $result->fetch_assoc()) {
        $productesInfo[$row['id']] = $row;
    }
    $stmt->close();
    $conn->close();

    // Construcción HTML
    $html = "<div class='carrito-container'>";
    $html .= "<h2>Resumen de tu compra</h2>";
    $html .= "<table class='carrito-table'>";
    $html .= "<tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th></tr>";

    $totalCompra = 0;
    $numProductes = 0;

    foreach ($productes as $id => $info) {
        if (!isset($productesInfo[$id])) continue;

        $producte = $productesInfo[$id];
        $quantitat = $info['quantitat'];
        $preu = $producte['preu'];
        $total = $quantitat * $preu;
        $totalCompra += $total;
        $numProductes++;

        $html .= "<tr>";
        $html .= "<td>".htmlspecialchars($producte['nom'])."</td>";
        $html .= "<td>".number_format($preu, 2)." €</td>";
        $html .= "<td>".htmlspecialchars($quantitat)."</td>";
        $html .= "<td>".number_format($total, 2)." €</td>";
        $html .= "</tr>";
    }

    $html .= "<tr class='total-row'>
                <td colspan='2'>Productos distintos: $numProductes</td>
                <td colspan='2'>Total: ".number_format($totalCompra, 2)." €</td>
              </tr>";
    $html .= "</table></div>";

    return $html;
}
public function buidarCarret() {
    $this->productes = []; 
}
}

?>
