<?php

require_once "./DataElement.php";

class InputElement extends DataElement
{
    /**
     * Tipo del input
     *
     * @var string
     */
    private $type;
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**

     * Protección ante hackeos del campo del POST

     *

     * @return mixed

     */

    protected function sanitizeInput()
    {
        if (isset($_POST[$this->name])) {
            $_POST[$this->name] =  $this->sanitize($_POST[$this->name]);
            return $_POST[$this->name];
        }
        return "";
    }

    /**
     * Protección ante hackeos
     *
     * @return mixed
     */
    protected function sanitize($data)
    {
        if (isset($data)) {
            return htmlspecialchars(stripslashes(trim($data)));
        }
        return "";
    }


    /**
     * Genera el HTML del elemento
     *
     * @return string
     */
    public function render(): string

    {
        $html = "<input type='{$this->type}' name='{$this->name}'";
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $html .= " value='" . $this->sanitizeInput() . "'";
        }
        $html .= (!empty($this->id) ? " id='$this->id' " : '');
        $html .= (!empty($this->class) ? " class='$this->class' " : '');
        $html .= (!empty($this->style) ? " style='$this->style' " : '');
        $html .= '>';
        return $html;
    }
}
