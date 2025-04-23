console.log("si entro");


document.addEventListener("DOMContentLoaded", () => {
    // Función reutilizable para enviar formulario por fetch
    function enviarFormulario(formulario, url) {
        const formData = new FormData(formulario);

        fetch(url, {
            method: "POST",
            body: formData,
        })
            .then(response => response.text())
            .then(data => {
                console.log("Respuesta del servidor:", data);
                alert(data); // Mostrar al usuario
                formulario.reset(); // Limpiar formulario después de enviar
            })
            .catch(error => {
                console.error("Error al enviar el formulario:", error);
                alert("Ocurrió un error. Revisa la consola.");
            });
    }

    // Crear Cuenta
    const formCrearCuenta = document.querySelector('form[action="crear_cuenta.php"]');
    formCrearCuenta.addEventListener("submit", function (event) {
        event.preventDefault();
        enviarFormulario(formCrearCuenta, "crear_cuenta.php");
    });

    // Crear Plantilla
    const formCrearPlantilla = document.querySelector('form[action="crear_plantilla.php"]');
    formCrearPlantilla.addEventListener("submit", function (event) {
        event.preventDefault();
        enviarFormulario(formCrearPlantilla, "crear_plantilla.php");
    });

    // Enviar Mensaje
    const formEnviarMensaje = document.querySelector('form[action="enviar_mensaje.php"]');
    formEnviarMensaje.addEventListener("submit", function (event) {
        event.preventDefault();
        enviarFormulario(formEnviarMensaje, "enviar_mensaje.php");
    });


    const select = document.getElementById("selectPlantillas");

    fetch("get_plantillas.php")
        .then((res) => res.json())
        .then((data) => {
            data.forEach((plantilla) => {
                const option = document.createElement("option");
                option.value = plantilla.id;
                option.textContent = plantilla.nombre;
                select.appendChild(option);
            });
        })
        .catch((err) => {
            console.error("Error al cargar las plantillas:", err);
        });


    const tablaMensajes = document.getElementById("mensajesEnviados").querySelector("tbody");

    fetch("get_mensajes_enviados.php")
        .then(res => res.json())
        .then(data => {
            tablaMensajes.innerHTML = ""; // Limpiar la tabla antes de agregar filas
            data.forEach(mensaje => {
                const fila = document.createElement("tr");

                fila.innerHTML = `
                        <td>${mensaje.num_destino}</td>
                        <td>${mensaje.adjunto}</td>
                        <td>${mensaje.asunto}</td>
                        <td>${mensaje.plantilla_nombre ?? "Sin plantilla"}</td>
                    `;

                tablaMensajes.appendChild(fila);
            });
        })
        .catch(error => {
            console.error("Error al cargar los mensajes enviados:", error);
        });

    const selectCuentas = document.addEventListener("DOMContentLoaded", function () {
        fetch("get_cuentas.php")
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById("selectCuentas");
                data.forEach(cuenta => {
                    const option = document.createElement("option");
                    option.value = cuenta.nombre; // Usamos el nombre como valor
                    option.textContent = cuenta.nombre;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error("Error al cargar cuentas:", error));
    });

});







// document.addEventListener("DOMContentLoaded", () => {
//     // Formulario: Crear Cuenta
//     const formCrearCuenta = document.querySelector('form[action="crear_cuenta.php"]');
//     formCrearCuenta.addEventListener("submit", function (event) {
//         event.preventDefault(); // Evita que el formulario se envíe

//         const nombre = formCrearCuenta.querySelector('input[name="nombre"]').value;
//         const numero = formCrearCuenta.querySelector('input[name="numero"]').value;

//         console.log("Crear Cuenta:");
//         console.log("Nombre:", nombre);
//         console.log("Número:", numero);

//         // Aquí podrías hacer un fetch/AJAX para enviar los datos
//     });

//     // Formulario: Crear Plantilla
//     const formCrearPlantilla = document.querySelector('form[action="crear_plantilla.php"]');
//     formCrearPlantilla.addEventListener("submit", function (event) {
//         event.preventDefault();

//         const plantilla = formCrearPlantilla.querySelector('input[name="plantilla"]').value;
//         const mensaje = formCrearPlantilla.querySelector('textarea[name="mensaje"]').value;

//         console.log("Crear Plantilla:");
//         console.log("Plantilla:", plantilla);
//         console.log("Mensaje:", mensaje);
//     });

//     // Formulario: Enviar Mensaje
//     const formEnviarMensaje = document.querySelector('form[action="enviar_mensaje.php"]');
//     formEnviarMensaje.addEventListener("submit", function (event) {
//         event.preventDefault();

//         const numeroDestino = formEnviarMensaje.querySelector('input[name="num_destino"]').value;
//         const plantillaId = formEnviarMensaje.querySelector('select[name="plantilla_id"]').value;
//         const mensaje = formEnviarMensaje.querySelector('textarea[name="mensaje"]').value;

//         console.log("Enviar Mensaje:");
//         console.log("Número Destino:", numeroDestino);
//         console.log("Plantilla ID:", plantillaId);
//         console.log("Mensaje:", mensaje);
//     });
// });
