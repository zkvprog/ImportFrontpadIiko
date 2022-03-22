<?php

namespace zkvprog\FrontpadReader;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class FrontpadReader
{
    protected $startRow = 2;

    protected $columnsKey = [
        'name', 'telephone', 'street', 'house', 'entrance', 'floor', 'apartment', 'comment', 'email',
        'not_notify', 'discount_card', 'discount', 'bonus', 'birthday', 'channel', 'department', 'created', 'order_amount',
        'total', 'last_order'
    ];

    protected $data;

    public static function getImportFiles($dir = false, $files = false)
    {
        $frontpadImportFiles = new FrontpadImportFiles($dir, $files);
        if ($files) {
            return $frontpadImportFiles->getFiles();
        } else {
            $frontpadImportFiles->scanImportFiles();
            return $frontpadImportFiles->getFilesFull();
        }
    }

    public function __construct()
    {

    }

    public function setStartRow(int $startRow)
    {
        $this->startRow = $startRow;
    }

    public function read(FrontpadImportFiles $files)
    {
        foreach ($files as $file) {
            $this->readXls($file);
        }

        return $this->data;
    }

    protected function readXls($file)
    {
        $inputFileType = IOFactory::identify($file);
        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($file);

        $rowNumber = $spreadsheet->getActiveSheet()->getHighestDataRow();
        $columnNumber = Coordinate::columnIndexFromString($spreadsheet->getActiveSheet()->getHighestColumn());

        for ($i = $this->startRow; $i < $rowNumber + 1; $i++) {
            $data = [];
            for ($j = 1; $j < $columnNumber + 1; $j++) {
                $data[$this->columnsKey[$j - 1]] = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
            }

            $this->data[] = $data;
        }

    }
}
