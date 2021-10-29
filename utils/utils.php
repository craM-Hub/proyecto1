<?php
function esOpcionMenuActiva(string $option): bool
{
    $uri = $_SERVER["REQUEST_URI"];
    if (strpos($uri, $option) > 0) {
        return true;
    } elseif (('/' === $uri) && ('index' == $option)) {
        return true;
    } else return false;
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

function getAsociados(array $asociados): array
{
    shuffle($asociados);
    return array_slice($asociados, 0, 3);
}
