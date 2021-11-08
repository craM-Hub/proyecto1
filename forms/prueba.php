<?php
require_once "./InputElement.php";
require_once "./ButtonElement.php";
require_once "./FormElement.php";

$a = new InputElement('text');

$a->setName('campo1')
  ->setId('campo1');

$b = new ButtonElement('Send');
$form = new FormElement();

echo $form
  ->appendChild($a)
  ->appendChild($b)
  ->render();