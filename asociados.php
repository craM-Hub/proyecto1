<?php
$title = "Asociados";
require_once "./utils/utils.php";
require_once "./utils/File.php";
require_once "./utils/SimpleImage.php";
require_once "./entity/Asociado.php";
require_once "./exceptions/FileException.php";

//Variables

$info = $nombre = $descripcion = $urlImagen = "";
$nombreError = $logoErr = $hayErrores = false;
$errores = [];

if ("POST" === $_SERVER["REQUEST_METHOD"]) {
    //Procesamos el campo de tipo file
    try {
        //Nunca confiar en que lleguen todos los datos
        if (empty($_POST)) {
            throw new FileException("Se ha producido un error al procesar el formulario");
        }
        $imageFile = new File("logo", array("image/jpeg", "image/jpg", "image/png"), (2 * 1024 * 1024));
        $imageFile->saveUploadedFile(Asociado::RUTA_IMAGENES_LOGO);
        try {

            // Create a new SimpleImage object
            $simpleImage = new \claviska\SimpleImage();
            $simpleImage
                ->fromFile(Asociado::RUTA_IMAGENES_LOGO . $imageFile->getFileName())
                ->resize(50, 50)
                ->toFile(Asociado::RUTA_IMAGENES_LOGO . $imageFile->getFileName());
        } catch (Exception $err) {
            $errores[] = $err->getMessage();
            $logoErr = true;
        }
    } catch (FileException $fe) {
        $errores[] = $fe->getMessage();
        $logoErr = true;
    }
    $nombre = sanitizeInput(($_POST["nombre"] ?? ""));
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
        $nombreError = true;
    }

    if (0 == count($errores)) {
        $info = "Imagen enviada correctamente: ";
        $urlImagen = Asociado::RUTA_IMAGENES_LOGO . $imageFile->getFileName();
        //reseteamos datos del formulario
        $nombre = "";
    } else {
        $info = "Datos erróneos";
    }
};

include("./views/asociados.view.php");
