document.addEventListener("DOMContentLoaded", function () {
    // Cargar las cuentas
    const selectCuentas = document.getElementById("selectCuentas");
    fetch("get_cuentas.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(cuenta => {
                const option = document.createElement("option");
                option.value = cuenta.id;
                option.textContent = cuenta.nombre;
                selectCuentas.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error al cargar las cuentas:", error);
        });

    // Cargar las plantillas
    const selectPlantillas = document.getElementById("selectPlantillas");
    fetch("get_plantillas.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(plantilla => {
                const option = document.createElement("option");
                option.value = plantilla.id;
                option.textContent = plantilla.nombre;
                selectPlantillas.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error al cargar las plantillas:", error);
        });

    // Enviar mensaje de plantilla
    document.getElementById("formularioMensajePlantilla").addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch("insertar_mensaje_plantilla.php", {
            method: "POST",
            body: formData
        })
            .then(response => {
                // Intenta parsear directamente como JSON si la cabecera es correcta
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.includes("application/json")) {
                    return response.json();
                }
                // Si no es JSON, devuelve texto y lo manejará más adelante
                return response.text();
            })
            .then(data => {
                if (typeof data === "string") {
                    // Intenta parsear manualmente si vino como texto
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        console.error("Respuesta no es un JSON válido:", data);
                        alert("Respuesta inválida del servidor.");
                        return;
                    }
                }

                if (data.status === "success") {
                    alert("✅ Mensaje de plantilla enviado correctamente.");
                    console.log("Respuesta:", data);
                } else {
                    alert("❌ Error: " + data.message);
                    console.warn("Error del servidor:", data);
                }
            })
            .catch(error => {
                console.error("❌ Error al enviar mensaje de plantilla:", error);
                alert("Hubo un problema al enviar el mensaje de plantilla.");
            });
    });


    // Enviar mensaje directo
    document.getElementById("formEnviarMensajeDirecto").addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch("enviar_mensaje_directo.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(text => {
                console.log("Respuesta del servidor:", text);
                try {
                    const data = JSON.parse(text);
                    if (data.status === "success") {
                        alert("Mensaje directo enviado correctamente.");
                    } else {
                        alert("Error: " + data.message);
                    }
                } catch (e) {
                    console.error("Respuesta no es un JSON válido:", text);
                    alert("Respuesta inválida del servidor.");
                }
            })
            .catch(error => {
                console.error("Error al enviar mensaje directo:", error);
                alert("Hubo un problema al enviar el mensaje directo.");
            });
    });
});
