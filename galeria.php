<?php
$title = "Galería";
require_once "./utils/utils.php";
require_once "./utils/File.php";
require_once "./utils/SimpleImage.php";
require_once "./entity/ImagenGaleria.php";
require_once "./exceptions/FileException.php";

//Variables

$info = $description = $urlImagen = "";
$descriptionError = $imagenErr = $hayErrores = false;
$errores = [];

if ("POST" === $_SERVER["REQUEST_METHOD"]) {
    //Procesamos el campo de tipo file
    try {
        //Nunca confiar en que lleguen todos los datos
        if (empty($_POST)) {
            throw new FileException("Se ha producido un error al procesar el formulario");
        }
        $imageFile = new File("imagen", array("image/jpeg", "image/jpg", "image/png"), (2 * 1024 * 1024));
        $imageFile->saveUploadedFile(ImagenGaleria::RUTA_IMAGENES_GALLERY);
        try {

            // Create a new SimpleImage object
            $simpleImage = new \claviska\SimpleImage();
            $simpleImage
                ->fromFile(ImagenGaleria::RUTA_IMAGENES_GALLERY . $imageFile->getFileName())
                ->resize(975, 525)
                ->toFile(ImagenGaleria::RUTA_IMAGENES_PORTFOLIO . $imageFile->getFileName())
                ->resize(650, 350)
                ->toFile(ImagenGaleria::RUTA_IMAGENES_GALLERY . $imageFile->getFileName());
        } catch (Exception $err) {
            $errores[] = $err->getMessage();
            $imagenErr = true;
        }
    } catch (FileException $fe) {
        $errores[] = $fe->getMessage();
        $imagenErr = true;
    }
    $description = sanitizeInput(($_POST["description"] ?? ""));
    if (empty($description)) {
        $errores[] = "La descripción es obligatoria";
        $descriptionError = true;
    }

    if (0 == count($errores)) {
        $info = "Imagen enviada correctamente: ";
        $urlImagen = ImagenGaleria::RUTA_IMAGENES_GALLERY . $imageFile->getFileName();
        //reseteamos datos del formulario
        $description = "";
    } else {
        $info = "Datos erróneos";
    }




    /* //comprobamos que llegan los datos
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

    if (isset($_FILES['imagen']) && ($_FILES['imagen']['error'] == UPLOAD_ERR_OK)) {
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
                $_FILES["imagen"]["tmp_name"],
                "images/index/gallery/" . $_FILES["imagen"]["name"]
            )) {
                $errores[] = "Se ha producido un error al mover la imagen";
                $imagenErr = true;
            }
        }
    } else {
        $errores[] = "Se ha producido un error. Código de error: " . $_FILES["imagen"]["error"];
        $imagenErr = true;
    }

    if (sizeof($errores) > 0) {
        $hayErrores = true;
    }

    //si no hay errores
    if (!$hayErrores) {
        $info = "Imagen enviada correctamente.";
        $urlImagen = "images/index/gallery/" . $_FILES["imagen"]["name"];

        //reseteamos datos del formulario
        $description = "";
    } else {
        $info = "Datos erróneos";
    } */
}

include("./views/galeria.view.php");
