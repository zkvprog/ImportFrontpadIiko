<?php

namespace zkvprog\FrontpadReader;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use zkvprog\Interfaces\ReaderInterface;


class FrontpadReader implements ReaderInterface
{
    protected $startRow = 2;

    protected $columnsKey = [
        'name', 'telephone', 'street', 'house', 'entrance', 'floor', 'apartment', 'comment', 'email',
        'not_notify', 'discount_card', 'discount', 'bonus', 'birthday', 'channel', 'department', 'created', 'order_amount',
        'total', 'last_order'
    ];

    protected $data;

    /**
     * @param int $startRow
     */
    public function setStartRow(int $startRow)
    {
        $this->startRow = $startRow;
    }

    /**
     * @param array $filePaths
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function read(array $filePaths) : array
    {
        foreach ($filePaths as $filePath) {
            $inputFileType = IOFactory::identify($filePath);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($filePath);

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

        return $this->data;
    }
}
