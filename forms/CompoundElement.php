<?php

require_once "./DataElement.php";

abstract class CompoundElement extends DataElement
{
    /**
     * Hijos del elemento
     *
     * @var array
     */
    protected $children;

    public function __construct()
    {
        $this->children = [];
    }

    /**
     * @param Element $child
     * @return void
     */
    public function appendChild(Element $child)
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }
}
