document.addEventListener("DOMContentLoaded", function () {
    // Cargar las cuentas en el select de cuentas
    const selectCuentas = document.getElementById("selectCuentas");
    fetch("get_cuentas.php") // Este archivo PHP debe devolver las cuentas en formato JSON
        .then(response => response.json())
        .then(data => {
            data.forEach(cuenta => {
                const option = document.createElement("option");
                option.value = cuenta.id; // Asume que 'id' es la propiedad de la cuenta
                option.textContent = cuenta.nombre; // 'nombre' es el nombre de la cuenta
                selectCuentas.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error al cargar las cuentas:", error);
        });

    // Cargar las plantillas en el select de plantillas
    const selectPlantillas = document.getElementById("selectPlantillas");
    fetch("get_plantillas.php") // Este archivo PHP debe devolver las plantillas en formato JSON
        .then(response => response.json())
        .then(data => {
            data.forEach(plantilla => {
                const option = document.createElement("option");
                option.value = plantilla.id; // Asume que 'id' es la propiedad de la plantilla
                option.textContent = plantilla.nombre; // 'nombre' es el nombre de la plantilla
                selectPlantillas.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error al cargar las plantillas:", error);
        });

    // Enviar el mensaje
    document.getElementById("formEnviarMensaje").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevenir el envío tradicional del formulario

        const formData = new FormData(this); // Crear un FormData con todos los campos del formulario

        fetch("enviar_mensaje.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json()) // Asumimos que el servidor devuelve un JSON
            .then(data => {
                if (data.status === "success") {
                    alert("Mensaje enviado correctamente.");
                    // Puedes realizar acciones adicionales, como limpiar el formulario o actualizar la lista
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error al enviar el mensaje:", error);
                alert("Hubo un problema al enviar el mensaje.");
            });
    });
});
