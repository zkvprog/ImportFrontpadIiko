<?php

require_once "bootstrap.php";

$frontpadReader = new \zkvprog\FrontpadReader\FrontpadReader(\zkvprog\FrontpadReader\FrontpadReader::getImportFiles());
$import = new \zkvprog\Import\Import();

$import
    ->addReader($frontpadReader)
    ->addWriter(new \zkvprog\IikoWriter\IikoBonusWriter())
    ->addConverter(new \zkvprog\Converter\IikoBonusConverter)
    ->execute()
;
