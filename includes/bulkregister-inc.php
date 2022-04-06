<?php
require_once '../vendor/autoload.php';
require_once 'connection-inc.php';
require_once '../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php';

if ($_FILES['registerFile']['name'] != '') {

    $allowedExtensions = array('xls', 'csv', 'xlsx');
    $arrFile = explode('.', $_FILES['registerFile']['name']);
    $extension = end($arrFile);

    if (in_array($extension, $allowedExtensions)) {

        if ('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else if ('xls' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load($_FILES['registerFile']['tmp_name']);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $rowCount = $spreadsheet->getActiveSheet()->getHighestDataRow();

        $userID = [];
        $hashedPassword = [];
        $role = [];

        if (!empty($sheetData)) {
            // starts reading from line 1, taking into account the Excel sheet has a header
            // IMPORTANT: settings in the sheet such as "Wrap Text" will cause the reader to think its not empty, 
            // use "Clear All" under the Editing tab in Excel to be safe

            for ($i = 1; $i < count($sheetData); $i++) {
                array_push($userID, $sheetData[$i][0]);
                array_push($hashedPassword, password_hash($sheetData[$i][1], PASSWORD_DEFAULT));
                array_push($role, $sheetData[$i][2]);
            }

            $userCount = $rowCount - 1;

            $sql = "INSERT INTO users (userID, password, role) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($con);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "Something went wrong";
                exit();
            }

            for ($i = 0; $i < $userCount; $i++) {
                mysqli_stmt_bind_param($stmt, "sss", $userID[$i], $hashedPassword[$i], $role[$i]);

                $executionResult = mysqli_stmt_execute($stmt);

                if ($executionResult === false) {
                    echo "Error: " . $userID[$i] . " could not be added due to " . mysqli_error($con) . "<br>";
                }
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        echo 'Only .csv .xls or .xlsx files are allowed';
    }
} else {
    echo 'Please select a file';
}
