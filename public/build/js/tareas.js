!function(){!async function(){try{const a=`/api/tarea?url=${r()}`,o=await fetch(a),n=await o.json();e=n,t()}catch(e){console.log(e)}}();let e=[];function t(){if(function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}(),0===e.length){const e=document.querySelector("#listado-tareas"),t=document.createElement("LI");return t.textContent="No hay tareas",t.classList.add("no-tareas"),void e.appendChild(t)}const o={0:"Pendiente",1:"Completa"};e.forEach((c=>{const i=document.createElement("LI");i.dataset.tareaId=c.id,i.classList.add("tarea");const s=document.createElement("P");s.textContent=c.nombre,s.onclick=function(){a(!0,{...c})};const d=document.createElement("DIV");d.classList.add("opciones");const l=document.createElement("BUTTON");l.classList.add("estado-tarea"),l.classList.add(`${o[c.estado].toLowerCase()}`),l.textContent=o[c.estado],l.dataset.estadoTarea=c.estado,l.onclick=function(){!function(e){const t="1"===e.estado?"0":"1";e.estado=t,n(e)}({...c})};const u=document.createElement("BUTTON");u.classList.add("eliminar-tarea"),u.dataset.idTarea=c.id,u.textContent="Eliminar",u.onclick=function(){!function(a){Swal.fire({title:"Seguro que quiere eliminar la tarea?",showCancelButton:!0,confirmButtonText:"Si",denyButtonText:"No"}).then((o=>{o.isConfirmed&&async function(a){const{estado:o,id:n,nombre:c,proyectoId:i}=a,s=new FormData;s.append("id",n),s.append("nombre",c),s.append("estado",o),s.append("proyectoId",r());try{const o="http://localhost:3000/api/tarea/eliminar",n=await fetch(o,{method:"POST",body:s}),r=await n.json();r.resultado&&(Swal.fire("Eliminado!",r.mensaje,"succes"),e=e.filter((e=>e.id!==a.id)),t())}catch(e){console.log(e)}}(a)}))}({...c})},d.appendChild(l),d.appendChild(u),i.appendChild(s),i.appendChild(d);document.querySelector("#listado-tareas").appendChild(i)}))}function a(a=!1,c={}){const i=document.createElement("DIV");i.classList.add("modal"),i.innerHTML=`\n            <form class="formulario nueva-tarea">\n                <legend>${a?"Editar Tarea":"Añade una nueva tarea"}</legend>\n                <div class="campo">\n                    <label>${a?"Cambiar Tarea:":"Nueva tarea:"}</tarea>\n                    <input\n                        type="text"\n                        name="tarea"\n                        placeholder="${c.nombre?"Edita la tarea":"Añadir Tarea al Proyecto Actual"}"\n                        id="tarea"\n                        value="${c.nombre?c.nombre:""}"\n                    >\n                </div>\n                <div class="opciones">\n                    <input type="submit" class="submit-nueva-tarea" value="${c.nombre?"Editar":"Guardar"}" />\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                </div>  \n            </form>\n        `,setTimeout((()=>{document.querySelector(".formulario").classList.add("animar")}),0),i.addEventListener("click",(function(s){if(s.preventDefault(),s.target.classList.contains("cerrar-modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout((()=>{i.remove()}),500)}if(s.target.classList.contains("submit-nueva-tarea")){const i=document.querySelector("#tarea").value.trim();if(""===i)return void o("El Nombre de la tarea es Obligatorio","error",document.querySelector(".formulario legend"));a?(c.nombre=i,n(c)):async function(a){const n=new FormData;n.append("nombre",a),n.append("proyectoId",r());try{const r="http://localhost:3000/api/tarea",c=await fetch(r,{method:"POST",body:n}),i=await c.json();if(o(i.mensaje,i.tipo,document.querySelector(".formulario legend")),"exito"===i.tipo){const o=document.querySelector(".modal");setTimeout((()=>{o.remove()}),2e3);const n={id:String(i.id),nombre:a,estado:"0",proyectoId:i.proyectoId};e=[...e,n],t()}}catch(e){console.log(e)}}(i)}})),document.querySelector(".dashboard").appendChild(i)}function o(e,t,a){const o=document.querySelector(".alerta");o&&o.remove();const n=document.createElement("DIV");n.classList.add("alerta",t),n.textContent=e,a.parentElement.insertBefore(n,a.nextElementSibling),setTimeout((()=>{n.remove()}),5e3)}async function n(a){const{estado:o,id:n,nombre:c,proyectoId:i}=a,s=new FormData;s.append("id",n),s.append("nombre",c),s.append("estado",o),s.append("proyectoId",r());try{const a="http://localhost:3000/api/tarea/actualizar",r=await fetch(a,{method:"POST",body:s}),i=await r.json();if("exito"===i.respuesta.tipo){Swal.fire(i.respuesta.mensaje,i.respuesta.mensaje,"success");const a=document.querySelector(".modal");a&&a.remove(),e=e.map((e=>(e.id===n&&(e.estado=o,e.nombre=c),e))),t()}}catch(e){console.log(e)}}function r(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).url}document.querySelector("#agregar-tarea").addEventListener("click",(function(){a(!1)}))}();