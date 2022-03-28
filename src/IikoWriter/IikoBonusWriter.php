<?php

namespace zkvprog\IikoWriter;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use zkvprog\Interfaces\WriterInterface;

class IikoBonusWriter implements WriterInterface
{
    protected $startRow = 2;
    protected $writerFilePath;
    protected $columnsKey = [
        'telephone', 'track', 'number', 'bonus'
    ];

    /**
     * IikoBonusWriter constructor.
     * @param $writerDir
     * @param $writerFileName
     */
    public function __construct($writerDir, $writerFileName)
    {
        $this->writerFilePath = $writerDir . DIRECTORY_SEPARATOR . $writerFileName;
    }

    /**
     * @param array $data
     */
    public function write(array $data)
    {
        $spreadsheet = new Spreadsheet();
        try {
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
                $writer->save($this->writerFilePath);
            } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
                echo $e->getMessage();
            }
        } catch (Exception $e) {

        }
    }
}
