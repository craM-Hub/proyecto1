<?php
$title = "Galería";
require_once "./utils/utils.php";

//Variables

$info = $description = $urlImagen = "";
$descriptionError = $imagenErr = $hayErrores = false;
$errores = [];

if ("POST" === $_SERVER["REQUEST_METHOD"]) {
    //comprobamos que llegan los datos
    if (empty($_POST)) {
        $errores[] = 'Se ha producido un error al procesar el formulario';
        $imagenErr = true;
    }

    if (!$imagenErr) {
        $description = sanitizeInput(($_POST["description"] ?? ""));

        //comprobaciones
        if (empty($description)) {
            $errores[] = "La descripción es obligatoria";
            $descriptionError = true;
        }
    }

    if (isset($_FILES['imagen']) && ($_FILES['imagen']['error'] === UPLOAD_ERR_OK)) {
        if ($_FILES['imagen']['size'] > (2 * 1024 * 1024)) {
            $errores[] = 'El archivo no puede superar los 2MB';
            $imagenErr = true;
        }

        //comprobar el mime tipy
        $extensions = array("image/jpeg", "image/jpg", "image/png");

        if (false === in_array($_FILES['imagen']['type'], $extensions)) {
            $errores[] = 'Extensión no permitida, sólo son válidos archivos jpg o png';
            $imagenErr = true;
        }

        if (!$imagenErr) {
            //mover si no hay errores
            if (false === move_uploaded_file(
                $_FILES['imagen']['tmp_name'],
                "images/index/gallery/" . $_FILES['imagen']['name']
            )) {
                $errores[] = "Se ha producido un error al mover la imagen";
                $imagenErr = true;
            }
        }
    } else {
        $errores[] = "Se ha producido un error. Código de error: " . $_FILES['imagen']['error'];
        $imagenErr = true;
    }

    if (sizeof($errores) > 0) {
        $hayErrores = true;
    }

    //si no hay errores
    if (!$hayErrores) {
        $info = "Imagen enviada correctamente.";
        $urlImagen = "images/index/gallery/" . $_FILES['imagen']['name'];

        //reseteamos datos del formulario
        $description = "";
    } else {
        $info = "Datos erróneos";
    }
}

include("./views/galeria.view.php");
