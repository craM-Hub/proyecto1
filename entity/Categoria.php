<?php
require_once __DIR__ . '/Entity.php';

class Categoria extends Entity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var int
     */
    private $numImagenes;

    public function __construct(string $nombre = '', int $numImagenes = 0)
    {
        parent::__construct();
        $this->id = null;
        $this->nombre = $nombre;
        $this->numImagenes = $numImagenes;
    }

    //Setters y getters

    // .....

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'numImagenes' => $this->getNumImagenes()
        ];
    }
}