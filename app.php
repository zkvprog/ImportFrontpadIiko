<?php

require_once "bootstrap.php";

(new \zkvprog\Import\Import())
    ->addSource(new \zkvprog\Import\ImportFilePathsSource(__DIR__ . DIRECTORY_SEPARATOR . 'import'))
    ->addReader(new \zkvprog\FrontpadReader\FrontpadReader())
    ->addConverter(new \zkvprog\Converter\IikoBonusConverter)
    ->addWriter(new \zkvprog\IikoWriter\IikoBonusWriter(__DIR__ . DIRECTORY_SEPARATOR . 'export', 'iiko_bonus.xlsx'))
    ->execute()
;

echo "Done!";