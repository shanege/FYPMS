<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");

function emptyInput($userID, $password)
{
    if (empty($userID) || empty($password)) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}

function invalidPassword($password)
{
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}

function userExists($con, $userID)
{
    // prepared statement to check if user exists
    $sql = "SELECT * FROM users WHERE userID = ?;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $userID, PDO::PARAM_STR);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } else {
            $result = false;
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
}

function createUser($con, $userID, $password)
{
    // updates whenever PHP discovers the hashing algorithm is no longer secure
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // prepared statement to create user
    $sql = "INSERT INTO users (userID, password) VALUES (?, ?);";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $userID, PDO::PARAM_STR);
        $stmt->bindParam(2, $hashedPassword, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: ../index.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
}

function loginUser($con, $userID, $password)
{
    $userData = userExists($con, $userID);

    if ($userData === false) {
        header("location: ../login.php?error=nouser");
        exit();
    }
    $hashedPassword = $userData["password"];
    $checkPassword = password_verify($password, $hashedPassword);

    if ($checkPassword === false) {
        header("location: ../login.php?error=wrongpassword");
        exit();
    } else if ($checkPassword === true) {

        // log the user in and create a session variable of their userID
        session_start();
        $_SESSION["userID"] = $userData["userID"];
        $_SESSION["role"] = $userData["role"];
        header("Location: ../index.php");
    }
}

function checkLogin($con)
{
    // check if session value exists
    if (isset($_SESSION['userID'])) {
        $id = $_SESSION['userID'];
        $sql = "SELECT * FROM users WHERE userID = ?;";

        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_STR);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $row;
            } else {
                $result = false;
                return $result;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            header("location: ../index.php?error=stmtfailed");
            exit();
        }
    }

    // if not, redirect to login
    header("Location: login.php");
    die;
}

function getSupervisor($con, $supervisorID)
{
    $sql = "SELECT name, email, research_areas, description FROM supervisor_details WHERE supervisorID = ? LIMIT 1;";

    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $supervisorID, PDO::PARAM_STR);

        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } else {
            $result = false;
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getAllSupervisors($con)
{
    $sql = "SELECT supervisorID, name, email, research_areas FROM supervisor_details ORDER BY name;";

    try {
        $stmt = $con->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getStudent($con, $studentID)
{
    $sql = "SELECT name, email, working_title FROM student_details WHERE studentID = ? LIMIT 1;";

    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $studentID, PDO::PARAM_STR);

        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } else {
            $result = false;
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function requestExists($con, $studentID)
{
    $sql = "SELECT supervisorID, status FROM supervisor_student_pairs WHERE studentID = ? LIMIT 1;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $studentID, PDO::PARAM_STR);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } else {
            $result = false;
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getAllStudentIDsForSupervisorByStatus($con, $supervisorID, $status)
{
    $sql = "SELECT studentID FROM supervisor_student_pairs WHERE supervisorID = ? AND status = ?;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $supervisorID, PDO::PARAM_STR);
        $stmt->bindParam(2, $status, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getSupervisorStudentQuota($con, $semester)
{
    $sql = "SELECT quota FROM supervisor_student_quotas WHERE semester = ? LIMIT 1;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $semester, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['quota'];
        } else {
            $result = false;
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function countStudentsForSupervisor($con, $supervisorID)
{
    $status = "Ongoing";
    $sql = "SELECT COUNT(studentID) as total_students FROM supervisor_student_pairs WHERE supervisorID = ? AND status = ? LIMIT 1;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $supervisorID, PDO::PARAM_STR);
        $stmt->bindParam(2, $status, PDO::PARAM_STR);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['total_students'];
        } else {
            $result = false;
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getAllProposedTopicsForSupervisor($con, $supervisorID)
{
    $sql = "SELECT topicID, topic, description, expected_output, skills, field_of_study FROM proposed_topics WHERE supervisorID = ?;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $supervisorID, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getAllProposedTopics($con)
{
    $sql = "SELECT topicID, supervisorID, topic, description, expected_output, skills, field_of_study FROM proposed_topics ORDER BY topicID;";
    try {
        $stmt = $con->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getProposedTopic($con, $topicID)
{
    $sql = "SELECT topic, description, expected_output, skills, field_of_study FROM proposed_topics WHERE topicID = ? LIMIT 1;";

    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $topicID, PDO::PARAM_STR);

        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } else {
            $result = false;
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getTasksForPair($con, $studentID, $supervisorID)
{
    $sql = "SELECT taskID, title, description, deadline_at, 
    supervisor_upload_path, student_submit_path, submission_text, 
    status, grade, remarks 
    FROM student_tasks WHERE studentID = ? AND supervisorID = ?;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $studentID, PDO::PARAM_STR);
        $stmt->bindParam(2, $supervisorID, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getTask($con, $taskID)
{
    $sql = "SELECT studentID, supervisorID, title, description,  
    deadline_at, supervisor_upload_path, student_submit_path, 
    submission_text, status, grade, remarks 
    FROM student_tasks WHERE taskID = ? LIMIT 1;";

    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $taskID, PDO::PARAM_STR);

        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } else {
            $result = false;
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getStudentSupervisorPairsByStatus($con, $status)
{
    $sql = "SELECT studentID, supervisorID, starting_semester FROM supervisor_student_pairs WHERE status = ?;";

    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $status, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getStudentsWithoutSupervisor($con)
{
    $sql = 'SELECT studentID FROM student_details WHERE studentID NOT IN
        (SELECT studentID FROM supervisor_student_pairs WHERE status="Ongoing")';

    try {
        $stmt = $con->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function mimeToExtension($mimeType)
{
    $mimeMap = [
        'video/3gpp2'                                                               => '3g2',
        'video/3gp'                                                                 => '3gp',
        'video/3gpp'                                                                => '3gp',
        'application/x-compressed'                                                  => '7zip',
        'audio/x-acc'                                                               => 'aac',
        'audio/ac3'                                                                 => 'ac3',
        'application/postscript'                                                    => 'ai',
        'audio/x-aiff'                                                              => 'aif',
        'audio/aiff'                                                                => 'aif',
        'audio/x-au'                                                                => 'au',
        'video/x-msvideo'                                                           => 'avi',
        'video/msvideo'                                                             => 'avi',
        'video/avi'                                                                 => 'avi',
        'application/x-troff-msvideo'                                               => 'avi',
        'application/macbinary'                                                     => 'bin',
        'application/mac-binary'                                                    => 'bin',
        'application/x-binary'                                                      => 'bin',
        'application/x-macbinary'                                                   => 'bin',
        'image/bmp'                                                                 => 'bmp',
        'image/x-bmp'                                                               => 'bmp',
        'image/x-bitmap'                                                            => 'bmp',
        'image/x-xbitmap'                                                           => 'bmp',
        'image/x-win-bitmap'                                                        => 'bmp',
        'image/x-windows-bmp'                                                       => 'bmp',
        'image/ms-bmp'                                                              => 'bmp',
        'image/x-ms-bmp'                                                            => 'bmp',
        'application/bmp'                                                           => 'bmp',
        'application/x-bmp'                                                         => 'bmp',
        'application/x-win-bitmap'                                                  => 'bmp',
        'application/cdr'                                                           => 'cdr',
        'application/coreldraw'                                                     => 'cdr',
        'application/x-cdr'                                                         => 'cdr',
        'application/x-coreldraw'                                                   => 'cdr',
        'image/cdr'                                                                 => 'cdr',
        'image/x-cdr'                                                               => 'cdr',
        'zz-application/zz-winassoc-cdr'                                            => 'cdr',
        'application/mac-compactpro'                                                => 'cpt',
        'application/pkix-crl'                                                      => 'crl',
        'application/pkcs-crl'                                                      => 'crl',
        'application/x-x509-ca-cert'                                                => 'crt',
        'application/pkix-cert'                                                     => 'crt',
        'text/css'                                                                  => 'css',
        'text/x-comma-separated-values'                                             => 'csv',
        'text/comma-separated-values'                                               => 'csv',
        'application/vnd.msexcel'                                                   => 'csv',
        'application/x-director'                                                    => 'dcr',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
        'application/x-dvi'                                                         => 'dvi',
        'message/rfc822'                                                            => 'eml',
        'application/x-msdownload'                                                  => 'exe',
        'video/x-f4v'                                                               => 'f4v',
        'audio/x-flac'                                                              => 'flac',
        'video/x-flv'                                                               => 'flv',
        'image/gif'                                                                 => 'gif',
        'application/gpg-keys'                                                      => 'gpg',
        'application/x-gtar'                                                        => 'gtar',
        'application/x-gzip'                                                        => 'gzip',
        'application/mac-binhex40'                                                  => 'hqx',
        'application/mac-binhex'                                                    => 'hqx',
        'application/x-binhex40'                                                    => 'hqx',
        'application/x-mac-binhex40'                                                => 'hqx',
        'text/html'                                                                 => 'html',
        'image/x-icon'                                                              => 'ico',
        'image/x-ico'                                                               => 'ico',
        'image/vnd.microsoft.icon'                                                  => 'ico',
        'text/calendar'                                                             => 'ics',
        'application/java-archive'                                                  => 'jar',
        'application/x-java-application'                                            => 'jar',
        'application/x-jar'                                                         => 'jar',
        'image/jp2'                                                                 => 'jp2',
        'video/mj2'                                                                 => 'jp2',
        'image/jpx'                                                                 => 'jp2',
        'image/jpm'                                                                 => 'jp2',
        'image/jpeg'                                                                => 'jpeg',
        'image/pjpeg'                                                               => 'jpeg',
        'application/x-javascript'                                                  => 'js',
        'application/json'                                                          => 'json',
        'text/json'                                                                 => 'json',
        'application/vnd.google-earth.kml+xml'                                      => 'kml',
        'application/vnd.google-earth.kmz'                                          => 'kmz',
        'text/x-log'                                                                => 'log',
        'audio/x-m4a'                                                               => 'm4a',
        'audio/mp4'                                                                 => 'm4a',
        'application/vnd.mpegurl'                                                   => 'm4u',
        'audio/midi'                                                                => 'mid',
        'application/vnd.mif'                                                       => 'mif',
        'video/quicktime'                                                           => 'mov',
        'video/x-sgi-movie'                                                         => 'movie',
        'audio/mpeg'                                                                => 'mp3',
        'audio/mpg'                                                                 => 'mp3',
        'audio/mpeg3'                                                               => 'mp3',
        'audio/mp3'                                                                 => 'mp3',
        'video/mp4'                                                                 => 'mp4',
        'video/mpeg'                                                                => 'mpeg',
        'application/oda'                                                           => 'oda',
        'audio/ogg'                                                                 => 'ogg',
        'video/ogg'                                                                 => 'ogg',
        'application/ogg'                                                           => 'ogg',
        'font/otf'                                                                  => 'otf',
        'application/x-pkcs10'                                                      => 'p10',
        'application/pkcs10'                                                        => 'p10',
        'application/x-pkcs12'                                                      => 'p12',
        'application/x-pkcs7-signature'                                             => 'p7a',
        'application/pkcs7-mime'                                                    => 'p7c',
        'application/x-pkcs7-mime'                                                  => 'p7c',
        'application/x-pkcs7-certreqresp'                                           => 'p7r',
        'application/pkcs7-signature'                                               => 'p7s',
        'application/pdf'                                                           => 'pdf',
        'application/octet-stream'                                                  => 'pdf',
        'application/x-x509-user-cert'                                              => 'pem',
        'application/x-pem-file'                                                    => 'pem',
        'application/pgp'                                                           => 'pgp',
        'application/x-httpd-php'                                                   => 'php',
        'application/php'                                                           => 'php',
        'application/x-php'                                                         => 'php',
        'text/php'                                                                  => 'php',
        'text/x-php'                                                                => 'php',
        'application/x-httpd-php-source'                                            => 'php',
        'image/png'                                                                 => 'png',
        'image/x-png'                                                               => 'png',
        'application/powerpoint'                                                    => 'ppt',
        'application/vnd.ms-powerpoint'                                             => 'ppt',
        'application/vnd.ms-office'                                                 => 'ppt',
        'application/msword'                                                        => 'doc',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/x-photoshop'                                                   => 'psd',
        'image/vnd.adobe.photoshop'                                                 => 'psd',
        'audio/x-realaudio'                                                         => 'ra',
        'audio/x-pn-realaudio'                                                      => 'ram',
        'application/x-rar'                                                         => 'rar',
        'application/rar'                                                           => 'rar',
        'application/x-rar-compressed'                                              => 'rar',
        'audio/x-pn-realaudio-plugin'                                               => 'rpm',
        'application/x-pkcs7'                                                       => 'rsa',
        'text/rtf'                                                                  => 'rtf',
        'text/richtext'                                                             => 'rtx',
        'video/vnd.rn-realvideo'                                                    => 'rv',
        'application/x-stuffit'                                                     => 'sit',
        'application/smil'                                                          => 'smil',
        'text/srt'                                                                  => 'srt',
        'image/svg+xml'                                                             => 'svg',
        'application/x-shockwave-flash'                                             => 'swf',
        'application/x-tar'                                                         => 'tar',
        'application/x-gzip-compressed'                                             => 'tgz',
        'image/tiff'                                                                => 'tiff',
        'font/ttf'                                                                  => 'ttf',
        'text/plain'                                                                => 'txt',
        'text/x-vcard'                                                              => 'vcf',
        'application/videolan'                                                      => 'vlc',
        'text/vtt'                                                                  => 'vtt',
        'audio/x-wav'                                                               => 'wav',
        'audio/wave'                                                                => 'wav',
        'audio/wav'                                                                 => 'wav',
        'application/wbxml'                                                         => 'wbxml',
        'video/webm'                                                                => 'webm',
        'image/webp'                                                                => 'webp',
        'audio/x-ms-wma'                                                            => 'wma',
        'application/wmlc'                                                          => 'wmlc',
        'video/x-ms-wmv'                                                            => 'wmv',
        'video/x-ms-asf'                                                            => 'wmv',
        'font/woff'                                                                 => 'woff',
        'font/woff2'                                                                => 'woff2',
        'application/xhtml+xml'                                                     => 'xhtml',
        'application/excel'                                                         => 'xl',
        'application/msexcel'                                                       => 'xls',
        'application/x-msexcel'                                                     => 'xls',
        'application/x-ms-excel'                                                    => 'xls',
        'application/x-excel'                                                       => 'xls',
        'application/x-dos_ms_excel'                                                => 'xls',
        'application/xls'                                                           => 'xls',
        'application/x-xls'                                                         => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
        'application/vnd.ms-excel'                                                  => 'xlsx',
        'application/xml'                                                           => 'xml',
        'text/xml'                                                                  => 'xml',
        'text/xsl'                                                                  => 'xsl',
        'application/xspf+xml'                                                      => 'xspf',
        'application/x-compress'                                                    => 'z',
        'application/x-zip'                                                         => 'zip',
        'application/zip'                                                           => 'zip',
        'application/x-zip-compressed'                                              => 'zip',
        'application/s-compressed'                                                  => 'zip',
        'multipart/x-zip'                                                           => 'zip',
        'text/x-scriptzsh'                                                          => 'zsh',
    ];

    return isset($mimeMap[$mimeType]) ? $mimeMap[$mimeType] : false;
}
