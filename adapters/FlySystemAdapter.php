<?php
require_once '../vendor/autoload.php';

$options = [
    'credentials' => array(
        'key' => getenv('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('AWS_SECRET_ACCESS_KEY')
    ),
    'region' => 'us-west-1',
    'version' => 'latest'
];

$client = new Aws\S3\S3Client($options);

$buckets = $client->listBuckets();

// The internal adapter
$adapter = new League\Flysystem\AwsS3V3\AwsS3V3Adapter(
    $client,
    'fypms' // bucket name
);

// The FilesystemOperator
$filesystem = new League\Flysystem\Filesystem($adapter);
