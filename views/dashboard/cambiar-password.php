<?php
    include_once __DIR__ . '/header-dashboard.php';
?>

<div class="contenedor-sm">
    <?php
        include_once __DIR__ . '/../templates/alertas.php';
    ?>
    <a href="/perfil" class="enlace">Volver al Perfil</a>
    <form  class="formulario" method="POST" action="/cambiar-password">
        <div class="campo">
            <label for="password">Password Actual</label>
            <input type="password" name="password_actual"  placeholder="Password Actual">
        </div>
        <div class="campo">
            <label for="password">Nuevo Password</label>
            <input type="password" name="password_nuevo"  placeholder="Nuevo Password">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php
    include_once __DIR__ . '/footer-dashboard.php';
?>