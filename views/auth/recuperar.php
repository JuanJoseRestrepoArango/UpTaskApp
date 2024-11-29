<div class="contenedor recuperar">
    <?php  include_once __DIR__ . '/../templates/nombre-sitio.php'?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recuperar Password</p>
        <form action="/recuperar" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email">
            </div>
            <div class="enviar">
                <input type="submit" class="boton" value="Enviar Instrucciones">
            </div>
        </form>
        <div class="acciones">
        <a href="/">Iniciar Sesión</a>
        <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
        
        </div>
    </div><!--.contenedor-sm-->
</div>