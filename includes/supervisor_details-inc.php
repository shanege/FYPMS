<?php
require_once '../vendor/autoload.php';
require_once '../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php';
require_once 'connection-inc.php';

if ($_FILES['supervisorFile']['name'] != '') {

    $allowedExtensions = array('xls', 'csv', 'xlsx');
    $arrFile = explode('.', $_FILES['supervisorFile']['name']);
    $extension = end($arrFile);

    if (in_array($extension, $allowedExtensions)) {

        if ('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else if ('xls' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load($_FILES['supervisorFile']['tmp_name']);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $rowCount = $spreadsheet->getActiveSheet()->getHighestDataRow();

        $supervisorID = [];
        $name = [];
        $research_area = [];
        $email = [];

        if (!empty($sheetData)) {
            // starts reading from line 1, taking into account the Excel sheet has a header
            // IMPORTANT: settings in the sheet such as "Wrap Text" will cause the reader to think its not empty, 
            // use "Clear All" under the Editing tab in Excel to be safe
            for ($i = 1; $i < $rowCount; $i++) {
                array_push($supervisorID, $sheetData[$i][0]);
                array_push($name, $sheetData[$i][1]);
                array_push($research_area, $sheetData[$i][2]);
                array_push($email, $sheetData[$i][3]);
            }

            $userCount = $rowCount - 1;

            $sql = "UPDATE supervisor_details SET name = ?, research_area = ?, email = ? WHERE supervisorID = ?";
            try {
                $stmt = $con->prepare($sql);
                for ($i = 0; $i < $userCount; $i++) {
                    $stmt->bindParam(1, $name[$i], PDO::PARAM_STR);
                    $stmt->bindParam(2, $research_area[$i], PDO::PARAM_STR);
                    $stmt->bindParam(3, $email[$i], PDO::PARAM_STR);
                    $stmt->bindParam(4, $supervisorID[$i], PDO::PARAM_STR);

                    try {
                        $stmt->execute();
                    } catch (PDOException $e) {
                        if (str_contains($e->getMessage(), "Integrity constraint violation: 1452")) {
                            echo "Error: " . $supervisorID[$i] . " could not be added. Is this person a user in the system yet? <br>";
                        } else {
                            echo "Error: " . $supervisorID[$i] . " could not be added due to " . $e->getMessage() . "<br>";
                        }
                    }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        echo 'Only .csv .xls or .xlsx files are allowed';
    }
} else {
    echo 'Please select a file';
}
