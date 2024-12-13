<?php
    include_once __DIR__ . '/header-dashboard.php';
?>
    <div class="contenedor-sm">
        <div class="contenedor-nueva-tarea">
            <button type="button" class="agregar-tarea" id="agregar-tarea">
                &#43; Nueva Tarea
            </button>
        </div>
    </div>

    <div id="filtros" class="filtros">
        <div class="filtros-inputs">
            <h2>Filtros:</h2>
            <div class="campos">
                <label for="todas">Todas</label>
                <input type="radio" name="filtro" id="todas" value="" checked>
            </div>
            <div class="campos">
                <label for="completadas">Completadas</label>
                <input type="radio" name="filtro" id="completadas" value="1">
            </div>
            <div class="campos">
                <label for="pendientes">Pendientes</label>
                <input type="radio" name="filtro" id="pendientes" value="0">
            </div>
        </div>
    </div>

    <ul class="listado-tareas" id="listado-tareas">

    </ul>


<?php
    include_once __DIR__ . '/footer-dashboard.php';
?>

<?php 
    $script .= '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="build/js/tareas.js"></script>
        '
?>