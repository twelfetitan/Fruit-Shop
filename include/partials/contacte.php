


    
    <main>
        <div class="cuadro-inici">
            <div class="cuadro-rojo">Contacte</div>
        </div class="formulari">
        <form method="post" action="include/procesaContacte.php">

            <label for="email">Correu Electrònic: <span class="requerit">*</span></label>
            <input type="email" id="email" name="email" required>

            <label for="asunto">Asunto:<span class="requerit">*</span></label>
            <input type="text" id="asunto" name="asunto" required>

            <label for="mensaje">Mensaje:<span class="requerit">*</span></label>
            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>

            <label for="puntua">Puntua la nostra pàgina (1 a 5):</label>
            <input type="number" id="puntua" name="puntua" min="1" max="5" step="1" required>

            <label for="slider">Selecciona un valor (1 a 100):</label>
            <input type="range" id="slider" name="slider" min="1" max="100" step="1" value="50">
            <br>

            <div class="botons">
                <input type="submit" value="Envia">
                <input type="reset" value="Neteja">
            </div>
        </form>
    </main>

    