(function(){

    obtenerTareas();
    let tareas = [];
    // Boton para mostrar el modal para agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click',function(){
        mostrarFormulario(false);
    });

    async function obtenerTareas(){

        try {
            const id = obtenerProyecto();
            const url =`/api/tarea?url=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            tareas = resultado;
            mostrarTareas()
        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTareas(){
        
        limpiarHTML();

        if(tareas.length === 0){
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;

        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        };

        tareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.onclick = function() {
                mostrarFormulario(true,{...tarea});
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.onclick = function(){
                cambiarEstadoTarea({...tarea});
            }
            
            const btEliminarTarea = document.createElement('BUTTON');
            btEliminarTarea.classList.add('eliminar-tarea');
            btEliminarTarea.dataset.idTarea = tarea.id;
            btEliminarTarea.textContent = 'Eliminar'
            btEliminarTarea.onclick  = function(){
                confirmarEliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        });
    }

    function mostrarFormulario(editar = false,tarea = {}){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Tarea': 'Añade una nueva tarea'}</legend>
                <div class="campo">
                    <label>${editar ? 'Cambiar Tarea:': 'Nueva tarea:'}</tarea>
                    <input
                        type="text"
                        name="tarea"
                        placeholder="${tarea.nombre ? 'Edita la tarea' : 'Añadir Tarea al Proyecto Actual'}"
                        id="tarea"
                        value="${tarea.nombre ? tarea.nombre : ''}"
                    >
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Editar' : 'Guardar'}" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>  
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
                const nombreTarea = document.querySelector('#tarea').value.trim();
                if(nombreTarea === ''){
                    //Mostrar alerta de error
                    mostrarAlerta('El Nombre de la tarea es Obligatorio','error',document.querySelector('.formulario legend'));
                    return
                }

                if(editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                }else{
                    agregarTarea(nombreTarea);
                }
                
               
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
        

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
                
                //Agregar el objeto de tareas al global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId

                }

                tareas = [...tareas,tareaObj];
                
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }
    function cambiarEstadoTarea(tarea){
       
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }
    async function actualizarTarea(tarea){

        const {estado,id,nombre,proyectoId} = tarea;

        const datos = new FormData();
        datos.append('id',id);
        datos.append('nombre',nombre);
        datos.append('estado',estado);
        datos.append('proyectoId',obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';

            const respuesta = await fetch(url,{
                method:'POST',
                body:datos
            });

            const resultado = await respuesta.json();

            if(resultado.respuesta.tipo === 'exito'){
                
                Swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                )

                const modal = document.querySelector('.modal');
                
                if(modal){
                    modal.remove();
                }

                tareas = tareas.map(tareaMemoria=>{
                    if(tareaMemoria.id === id){
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }

                    return tareaMemoria;
                });

                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }  

    }
    function confirmarEliminarTarea(tarea){
        Swal.fire({
            title: "Seguro que quiere eliminar la tarea?",
            showCancelButton: true,
            confirmButtonText: "Si",
            denyButtonText: "No"
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              eliminarTarea(tarea);
            } 
          });


    }
    async function eliminarTarea(tarea) {
       
        const {estado,id,nombre,proyectoId} = tarea;

        const datos = new FormData();
        datos.append('id',id);
        datos.append('nombre',nombre);
        datos.append('estado',estado);
        datos.append('proyectoId',obtenerProyecto());


        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';

            const respuesta = await fetch(url,{
                method:'POST',
                body:datos
            })

            const resultado = await respuesta.json();
            if(resultado.resultado){
                //mostrarAlerta(resultado.mensaje,resultado.tipo,document.querySelector('.contenedor-nueva-tarea'));
                Swal.fire('Eliminado!',resultado.mensaje,'succes');
                tareas = tareas.filter(tareaMemoria=>tareaMemoria.id!==tarea.id);
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }
    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.url;
    }

    function limpiarHTML(){
        const listadoTareas = document.querySelector('#listado-tareas');

        while(listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }
})();