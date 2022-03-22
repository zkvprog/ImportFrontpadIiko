<?php

namespace zkvprog\IikoWriter;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IikoBonusWriter
{
    protected $startRow = 2;
    protected $fileName = 'iiko_bonus.xlsx';
    protected $columnsKey = [
        'telephone', 'track', 'number', 'bonus'
    ];

    public function __construct()
    {
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function write($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $rowNumber = count($data);
        $columnNumber = count($this->columnsKey);

        for ($i = $this->startRow; $i < $rowNumber + 1; $i++) {
            for ($j = 0; $j < $columnNumber + 1; $j++) {
                $sheet->setCellValueByColumnAndRow($j, $i, $data[$i][$this->columnsKey[$j]]);
            }
        }

        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save($this->fileName);

        } catch (PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            echo $e->getMessage();
        }
    }
}
