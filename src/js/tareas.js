(function(){
    // Boton para mostrar el modal para agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click',mostrarFormulario);

    function mostrarFormulario(){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>Añade una nueva tarea</legend>
                <div class="campo">
                    <label>Tarea</tarea>
                    <input
                        type="text"
                        name="tarea"
                        placeholder="Añadir Tarea al Proyecto Actual"
                        id="tarea"
                    >
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e){
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }
            if(e.target.classList.contains('submit-nueva-tarea')){
                submitFormularioNuevaTarea();
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
        

    }

    function submitFormularioNuevaTarea(){
        const tarea = document.querySelector('#tarea').value.trim();
        if(tarea === ''){
            //Mostrar alerta de error
            mostrarAlerta('El Nombre de la tarea es Obligatorio','error',document.querySelector('.formulario legend'));
            return
        }

        agregarTarea(tarea);
    }

    //Muestra un mensaje en la interfaz
    function mostrarAlerta(mensaje,tipo,referencia){
        //Previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia){
            alertaPrevia.remove();
        }
        
        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta',tipo);

        alerta.textContent = mensaje;
        //Inserta la alerta antes del legend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //Eliminar alerta despues de cierto tiempo
        setTimeout(() => {
            alerta.remove();
        }, 5000);

    }

    //consultar el servidor para abrir una nueva tarea al proyecto actual
    async function agregarTarea(tarea){
        //Construir la peticion
        const datos = new FormData();
        datos.append('nombre',tarea);
        datos.append('proyectoId',obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url,{
                method:'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje,resultado.tipo,document.querySelector('.formulario legend'));
            if(resultado.tipo === 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 2000);
            }
        } catch (error) {
            console.log(error)
        }
    }
    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.url;
    }
})();