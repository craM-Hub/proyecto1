<?php
require_once __DIR__ . '/../entity/ImagenGaleria.php';
require_once __DIR__ . '/../database/QueryBuilder.php';

class ImagenGaleriaRepository extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct('imagenes', 'ImagenGaleria');
    }
}