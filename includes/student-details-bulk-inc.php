<?php
require_once '../vendor/autoload.php';
require_once '../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php';
require_once 'connection-inc.php';

if ($_FILES['studentDetailsFile']['name'] != '') {

    $allowedExtensions = array('xls', 'csv', 'xlsx');
    $arrFile = explode('.', $_FILES['studentDetailsFile']['name']);
    $extension = end($arrFile);

    if (in_array($extension, $allowedExtensions)) {

        if ('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else if ('xls' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load($_FILES['studentDetailsFile']['tmp_name']);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $rowCount = $spreadsheet->getActiveSheet()->getHighestDataRow();

        $studentID = [];
        $name = [];
        $intake = [];

        if (!empty($sheetData)) {
            // starts reading from line 1, taking into account the Excel sheet has a header
            // IMPORTANT: settings in the sheet such as "Wrap Text" will cause the reader to think its not empty, 
            // use "Clear All" under the Editing tab in Excel to be safe
            for ($i = 1; $i < $rowCount; $i++) {
                array_push($studentID, $sheetData[$i][0]);
                array_push($name, $sheetData[$i][1]);

                // if the format is 2019/09, change it to 201909
                array_push($intake, str_replace(array('/', '-'), '', $sheetData[$i][2]));
            }

            echo $rowCount;

            $userCount = $rowCount - 1;

            $sql = "UPDATE student_details SET name = ?, intake = ? WHERE studentID = ?";
            try {
                $stmt = $con->prepare($sql);
                for ($i = 0; $i < $userCount; $i++) {
                    $stmt->bindParam(1, $name[$i], PDO::PARAM_STR);
                    $stmt->bindParam(2, $intake[$i], PDO::PARAM_STR);
                    $stmt->bindParam(3, $studentID[$i], PDO::PARAM_STR);

                    try {
                        $stmt->execute();
                    } catch (PDOException $e) {
                        echo "Error: " . $studentID[$i] . "'s details could not be updated due to " . $e->getMessage() . "<br>";
                    }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        echo 'Only .csv, .xls, or .xlsx files are allowed';
    }
} else {
    echo 'Please select a file';
}
