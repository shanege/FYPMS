<?php
require_once '../vendor/autoload.php';

$options = [
    'credentials' => array(
        'key' => 'AKIAWUPPR5I4NZT4JIDA',
        'secret' => '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'
    ),
    'region' => 'us-west-1',
    'version' => 'latest'
];

$client = new Aws\S3\S3Client($options);

$buckets = $client->listBuckets();
// foreach ($buckets['Buckets'] as $bucket) {
//     echo $bucket['Name'] . "\n";
// }

// The internal adapter
$adapter = new League\Flysystem\AwsS3V3\AwsS3V3Adapter(
    $client,
    'fypms' // bucket name
);

// The FilesystemOperator
$filesystem = new League\Flysystem\Filesystem($adapter);
