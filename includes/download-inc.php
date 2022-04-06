<?php
$response = $filesystem->readStream($path);
$fileSize = $filesystem->fileSize($path);
$mimeType = $filesystem->mimeType($path);
$fileExtension = mimeToExtension($mimeType);

header("Pragma: public");
header("Expires: -1");
header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");

header("Content-Transfer-Encoding: binary");
header("Content-Type:application/" . $fileExtension);
header('Content-Disposition: attachment; filename="' . $fileName[1] . '"');
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header('Content-Length: ' . $fileSize);
header('Accept-Ranges: bytes');
fpassthru($response);

exit;
