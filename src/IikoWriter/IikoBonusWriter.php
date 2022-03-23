<?php

namespace zkvprog\IikoWriter;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IikoBonusWriter
{
    protected $startRow = 2;
    protected $exportDir = 'export';
    protected $fileName = 'iiko_bonus.xlsx';
    protected $fullpath;
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

    public function setExportDir($exportDir)
    {
        $this->exportDir = $exportDir;
    }

    public function setFullPath($fullpath)
    {
        $this->fullpath = $fullpath;
    }

    public function write($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $rowNumber = count($data);
        $columnNumber = count($this->columnsKey);

        for ($i = $this->startRow; $i < $rowNumber + 1; $i++) {
            for ($j = 1; $j < $columnNumber + 1; $j++) {
                $sheet->setCellValueByColumnAndRow($j, $i, $data[$i][$this->columnsKey[$j - 1]]);
            }
        }

        try {
            $writer = new Xlsx($spreadsheet);
            if ($this->fullpath) {
                $writer->save($this->fullpath);
            } else {
                $writer->save($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $this->exportDir . DIRECTORY_SEPARATOR . $this->fileName);
            }
        } catch (PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            echo $e->getMessage();
        }
    }
}
