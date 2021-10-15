<?php
$title = "Contact";
require_once "./utils/utils.php";

$info = $firstName = $lastName = $email = $subject = $message = "";
$firstNameError = $emailError = $subjectError = $hayErrores = false;
$errores = [];

if ("POST" === $_SERVER["REQUEST_METHOD"]) {
    $firstName = sanitizeInput(($_POST["firstName"] ?? ""));
    $lastName = sanitizeInput(($_POST["lastName"] ?? "")); //campo opcional
    $email = sanitizeInput(($_POST["email"] ?? ""));
    $subject = sanitizeInput(($_POST["subject"] ?? "")); //campo opcional
    $message = sanitizeInput(($_POST["message"] ?? ""));

    //Comprobaciones
    if (empty($firstName)) {
        $errores[] = "El nombre es obligatorio";
        $firstNameError = true;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Formato inválido de correo";
        $emailError = true;
    }
    if (empty($subject)) {
        $errores[] = "El asunto es obligatorio";
        $subjectError = true;
    }
    if (sizeof($errores) > 0) {
        $hayErrores = true;
    }
    if (!$hayErrores) {
        //Todo correcto
        //insertar codigo
        //mostrmos mensaje al usuario
        $info = "Mensaje insertado correctamente.";
        //reseteamos datos del formulario
        $firstName = $lasatName = $email = $subject = $message = "";
    } else {
        $info = "Datos erróneos";
    }
}
include("./views/contact.view.php");
