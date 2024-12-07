<div class="contenedor reestablecer">
    <?php  include_once __DIR__ . '/../templates/nombre-sitio.php'?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa tu nuevo Password</p>
        
        <?php  include_once __DIR__ . '/../templates/alertas.php'?>

        <?php if ($mostrar){?>
        <form class="formulario" method="POST">

            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu Password" name="password">
            </div>
            <div class="enviar">
                <input type="submit" class="boton" value="Guardar Password">
            </div>
        </form>
        <?php };?>
        <div class="acciones">
        <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
        <a href="/recuperar">¿Olvidaste tu Password?</a>
        </div>
    </div><!--.contenedor-sm-->
</div>