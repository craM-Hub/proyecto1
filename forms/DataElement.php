<?php

require_once "./Element.php";

abstract class DataElement extends Element

{

    /**
     * Nombre del campo en el formulario
     *
     * @var string
     */

    protected $name;

    /**
     * Valor del campo después del post
     *
     * @var string
     */
    protected $value;



    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get valor del campo
     *
     * @return  string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set valor del campo
     *
     * @param  string  $value  Valor del campo
     *
     * @return  self
     */
    protected function setValue(string $value)

    {
        $this->value = $value;
        return $this;
    }
}
