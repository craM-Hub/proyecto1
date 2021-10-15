<?php
function esOpcionMenuActiva(string $option): bool
{
    $uri = $_SERVER["REQUEST_URI"];
    return (str_contains($uri, $option)) ? true : false;
}

function  existeOpcionMenuActivaEnArray(array $options): bool
{
    foreach ($options as $key => $value) {
        if (esOpcionMenuActiva($value)) return true;
    }
    return false;
}
