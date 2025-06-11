
    
    <main>
        <div class="cuadro-inici">
            <div class="cuadro-rojo">Registre</div>
        </div>

        <?php
    // Mostrar el mensaje de error si las contraseñas no coinciden
    if (isset($_SESSION['error_message'])) {
        echo "<p style='color: red;'>" . $_SESSION['error_message'] . "</p>";
        unset($_SESSION['error_message']);
    }
    ?>

        <form action="include/procesaRegistre.php" method="post" class="formulari">
            
            <label for="nom">Nom: <span class="requerit">*</span></label>
            <input type="text" id="nom" name="nom" required>

            
            <label for="cognoms">Cognoms:</label>
            <input type="text" id="cognoms" name="cognoms">

            
            <label for="adreca">Adreça:</label>
            <input type="text" id="adreca" name="adreca">

            
            <label for="email">Correu Electrònic: <span class="requerit">*</span></label>
            <input type="email" id="email" name="email" required>

            
            <label for="contrasenya">Contrasenya:</label>
        <input type="password" name="contrasenya" id="contrasenya" required><br><br>

        <label for="confirmarContrasenya">Confirmar contrasenya:</label>
        <input type="password" name="confirmarContrasenya" id="confirmarContrasenya" required><br><br>

            
            <label for="poblacio">Població:</label>
            <select id="poblacio" name="poblacio">
                <option value="" selected>Tria una població</option>
                <option value="València">València</option>
                <option value="Barcelona">Barcelona</option>
                <option value="Madrid">Madrid</option>
                <option value="Sevilla">Sevilla</option>
            </select>

            
            <label for="telefon">Telèfon:</label>
            <input type="tel" id="telefon" name="telefon">

            
            <label>Horari repartiment:</label>
            <div class="radio-group">
                <input type="radio" id="mati" name="horari" value="Mati">
                <label for="mati">Matí</label>
                <input type="radio" id="vesprada" name="horari" value="Vesprada">
                <label for="vesprada">Vesprada</label>
            </div>

            
            

            <p>Fruita preferida:</p>
        <div class="checkbox-container">
            <label>
                <input type="checkbox" name="fruitaPreferida[]" value="poma"> Poma
            </label>
            <label>
                <input type="checkbox" name="fruitaPreferida[]" value="taronja"> Taronja
            </label>
            <label>
                <input type="checkbox" name="fruitaPreferida[]" value="melo"> Meló
            </label>
            <label>
                <input type="checkbox" name="fruitaPreferida[]" value="banana"> Banana
            </label>
            <label>
                <input type="checkbox" name="fruitaPreferida[]" value="raim"> Raïm
            </label>
        </div>

            
            <div class="botons">
                <input type="submit" value="Envia">
                <input type="reset" value="Neteja">
            </div>
        </form>
    </main>