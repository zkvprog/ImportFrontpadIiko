<?php

require_once "bootstrap.php";

$frontpadReader = new \zkvprog\FrontpadReader\FrontpadReader();
$frontpadReader->read(\zkvprog\FrontpadReader\FrontpadReader::getImportFiles());

$columns = [
    'name', 'telephone', 'street', 'house', 'entrance', 'floor', 'apartment', 'comment', 'email',
    'not_notify', 'discount_card', 'discount', 'bonus', 'birthday', 'channel', 'department', 'created', 'order_amount',
    'total', 'last_order'
];

$dbColumns = $columns;
array_splice($dbColumns, array_search('telephone', $columns) + 1, 0, "telephone_formatted");
$dbColumnsNames = implode(', ', $dbColumns);
$dbColumnsVars = implode(', ', array_map(function($el) { return ':' . $el; }, $dbColumns));

$files = array_diff(scandir(getenv('IMPORT_DIR')), array('.', '..'));
if ($files) {
    try {
        $pdo = new PDO('mysql:host='. getenv('DB_HOST') .';dbname=' . getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));
        //$pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage();
        die();
    }

    //test
    $files = array_slice($files, 0, 1);

    foreach ($files as $fileName) {
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify(getenv('IMPORT_DIR') . DIRECTORY_SEPARATOR . $fileName);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load(getenv('IMPORT_DIR') . DIRECTORY_SEPARATOR . $fileName);


        $rowNumber = $spreadsheet->getActiveSheet()->getHighestDataRow();
        $columnNumber = $spreadsheet->getActiveSheet()->getHighestColumn();
        $columnNumber = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnNumber);

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        $stmt = $pdo->prepare("INSERT INTO clients(" . $dbColumnsNames . ") VALUES ($dbColumnsVars)");
        for ($i = 2; $i < $rowNumber + 1; $i++) {
            $params = [];

            for ($j = 1; $j < $columnNumber + 1; $j++) {
                switch ($columns[$j - 1]) {
                    case 'name':
                    case 'street':
                    case 'house':
                    case 'entrance':
                    case 'floor':
                    case 'apartment':
                    case 'comment':
                    case 'email':
                    case 'discount_card':
                    case 'birthday':
                    case 'channel':
                    case 'department':
                    case 'not_notify':
                        $stmt->bindValue($columns[$j - 1], $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue(), PDO::PARAM_STR);
                        break;

                    case 'bonus':
                    case 'total':
                    case 'discount':
                        $float =  $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                        $float = str_replace(",",".", $float);

                        $stmt->bindValue($columns[$j - 1], $float, PDO::PARAM_STR);
                        break;

                    case 'order_amount':
                        $stmt->bindValue($columns[$j - 1], $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue(), PDO::PARAM_INT);
                        break;

                    case 'created':
                    case 'last_order':
                        $date = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                        if (!empty($date)) {
                            $date = DateTime::createFromFormat('d.m.Y', $date)->format('Y-m-d');
                        }

                        $stmt->bindValue($columns[$j - 1], $date, PDO::PARAM_STR);
                        break;

                    case 'telephone':
                        $telephone = sanitizePhone($spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue());
                        $stmt->bindValue($columns[$j - 1], $telephone, PDO::PARAM_STR);

                        if (empty($telephone)) {
                            continue 3;
                        }

                        try {
                            $numberProto = $phoneUtil->parse($telephone, "RU");
                        } catch (\libphonenumber\NumberParseException $e) {
                            var_dump($e);
                        }

                        $stmt->bindValue('telephone_formatted', $phoneUtil->format($numberProto, \libphonenumber\PhoneNumberFormat::E164), PDO::PARAM_STR);
                        break;

                }
            }

            if ($stmt->execute()) {
                echo 'success' . '<br>';
            } else {
                echo '<pre>';
                print_r($stmt->errorInfo());
                echo '</pre>';
            }
        }
    }
}



