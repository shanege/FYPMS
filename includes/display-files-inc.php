<?php
require_once '../adapters/FlySystemAdapter.php';
require_once 'functions-inc.php';

use \League\Flysystem\FilesystemException;
use \League\Flysystem\UnableToRetrieveMetadata;
use \League\Flysystem\FileAttributes;
use \League\Flysystem\DirectoryAttributes;

$folder = $_POST['folder'];
$style = $_POST['style'];

if ($folder == "") {
    echo "None";
} else {
    try {
        $listing = $filesystem->listContents($folder, false);

        foreach ($listing as $item) {
            $path = $item->path();

            if ($item instanceof FileAttributes) {
                // handle the file
                try {
                    if ($style == "normal") {
                        echo '<a href="includes/download-inc.php?file=' . $path . '" target="_blank">' . basename($path) . '</a>';
                    } else if ($style == "group") {
                        echo '<a class="list-group-item list-group-item-action" href="includes/download-inc.php?file=' . $path . '" target="_blank">' . basename($path) . '</a>';
                    }
                } catch (FilesystemException | UnableToRetrieveMetadata $exception) {
                    echo "couldnt retrieve file metadata";
                }
            } elseif ($item instanceof DirectoryAttributes) {
                // handle the directory
            }
        }
    } catch (FilesystemException $exception) {
        // handle the error
        echo "folder does not exist";
    }
}
