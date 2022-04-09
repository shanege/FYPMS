<?php
require_once 'adapters/FlySystemAdapter.php';
require_once 'includes/functions-inc.php';

use \League\Flysystem\FilesystemException;
use \League\Flysystem\UnableToRetrieveMetadata;
use \League\Flysystem\FileAttributes;
use \League\Flysystem\DirectoryAttributes;
use \League\Flysystem\StorageAttributes;
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-auto">
            <a class="btn" href="supervisorslist.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="bi bi-search card-img-top " style="font-size: 8rem;"></i>
                    <div class="card-body">
                        <p class="card-text">Find a supervisor</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="#" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="bi bi-card-heading card-img-top " style="font-size: 8rem;"></i>
                    <div class="card-body">
                        <p class="card-text"> Manage FYP tasks </p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="#" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="bi bi-pen card-img-top " style="font-size: 8rem;"></i>
                    <div class="card-body">
                        <p class="card-text"> Edit FYP details </p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- <?php
        try {
            $listing = $filesystem->listContents('test/', false);

            foreach ($listing as $item) {
                $path = $item->path();

                if ($item instanceof FileAttributes) {
                    // handle the file
                    try {
                        $fileName = explode("/", $path);
                        echo '<div><a href="#" onclick="download();">' . $fileName[1] . '</a></div>';
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
        ?> -->
</body>

</html>
<script>
    function download() {
        document.write("called");
        // $.ajax({
        //     url: "includes/download-inc.php",
        //     method: "POST",
        //     data: {filename},
        //     contentType: false,
        //     cache: false,
        //     processData: false,
        //     beforeSend: function() {

        //     },
        //     success: function(data) {

        //     }
        // })
    }
</script>