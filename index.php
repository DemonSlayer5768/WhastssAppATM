<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Proyecto WhatsApp Business</title>
    <link rel="stylesheet" href="style.css" />
    <script src="script.js"></script>
</head>

<body>
    <div class="container">
        <h1 id="titulo">WhatsApp Business</h1>

        <!-- Crear Cuenta -->
        <section class="formSection">
            <h2>Crear Cuenta</h2>
            <form action="crear_cuenta.php" method="post">
                <label class="textLabel">Nombre de la cuenta</label>
                <input class="textInput" type="text" name="nombre" required />

                <label class="textLabel">Número de WhatsApp</label>
                <input class="textInput" type="text" name="numero" required />

                <button class="btnSuccess" type="submit">Crear Cuenta</button>
            </form>
        </section>

        <!-- Crear Plantilla -->
        <section class="formSection">
            <h2>Crear Plantilla</h2>
            <form action="crear_plantilla.php" method="post">
                <label class="textLabel">Nombre de la plantilla</label>
                <input class="textInput" type="text" name="nombrePlantilla" required />

                <label class="textLabel">Mensaje</label>
                <textarea class="textArea" name="cuerpoPlantilla" required></textarea>

                <button class="btnSuccess" type="submit">Crear Plantilla</button>
            </form>
        </section>

        <!-- Mensaje directo -->
        <section class="formSection">
            <form action="enviar_mensaje.php" method="post" enctype="multipart/form-data">

                <h2>Enviar Mensaje</h2>
                <label class="textLabel">Asunto</label>
                <Input class="textInput" type="text" name="asunto" required />
                <h2>Mensaje Plantilla</h2>

                <label class="textLabel">Seleccinar cuenta</label>
                <select name="cuentas_id" id="selectCuentas">
                    <option value="">Seleccione una cuenta</option>
                </select>

                <label class="textLabel"> SELECCIONAR PLANTILLA</label>
                <select name="plantilla_id" id="selectPlantillas">
                    <option value="">Selecciona una plantilla</option>
                </select>

                <h2>Mensaje Directo</h2>
                <label class="textLabel">Número destino</label>
                <input class="textInput" type="text" name="num_destino" />



                <label class="textLabel">Mensaje</label>
                <textarea class="textArea" name="mensaje"></textarea>

                <label class="textLabel">Archivo Adjunto</label>
                <input class="textInput" type="file" name="archivoAdjunto[]" multiple />


                <button class="btnSuccess" type="submit">Enviar Mensaje</button>
            </form>
        </section>


        <!-- MENSAJES ENVIADOS -->
        <section class="formSection">
            <h2>Mensajes Enviados</h2>
            <table id="mensajesEnviados">
                <thead>
                    <tr>
                        <th>Número Destinatario</th>
                        <th>Archivos Adjuntos</th>
                        <th>Asunto</th>
                        <th>Plantilla</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <!-- <td>+52 1234567890</td>
                        <td>archivo.pdf</td>
                        <td>Promoción</td>
                        <td>Plantilla 1</td> -->
                    </tr>
                    <!-- más filas aquí -->
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>