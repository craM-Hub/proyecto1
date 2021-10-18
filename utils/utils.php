<?php
function esOpcionMenuActiva(string $option): bool
{
    $uri = $_SERVER["REQUEST_URI"];
    return (strpos($uri, $option) > 0) ? true : false;
}

function  existeOpcionMenuActivaEnArray(array $options): bool
{
    foreach ($options as $key => $value) {
        if (esOpcionMenuActiva($value)) return true;
    }
    return false;
}

function sanitizeInput(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
