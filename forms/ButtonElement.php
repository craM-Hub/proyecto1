<?php

require_once "./Element.php";

class ButtonElement extends DataElement
{
    /**
     * Texto del botón
     *
     * @var string
     */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function render(): string
    {
        return
            "<button type='submit'" .
            (!empty($this->name) ? " name='$this->name' " : '') .
            (!empty($this->id) ? " id='$this->id' " : '') .
            (!empty($this->cssClass) ? " class='$this->cssClass' " : '') .
            (!empty($this->style) ? " style='$this->style' " : '') .
            ">{$this->text}</button>";
    }
}
