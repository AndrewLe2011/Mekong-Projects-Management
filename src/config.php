<?php

// Check session status and start session
if(empty(session_id())) session_start();

# Includes the autoloader for libraries installed with composer
require __DIR__ . '/vendor/autoload.php';

# Imports the Google Cloud client libraries
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\BigQuery\BigQueryClient;

# My Google Cloud Platform project ID
$projectId = 'asm1cc21b';

# Instantiates a client
$storage = new StorageClient([
    'projectId' => $projectId
]);
# Request stream wrapper for easy fie writing operations
$storage->registerStreamWrapper();

# Instantiates a client
$bigQuery = new BigQueryClient([
    'projectId' => $projectId,
]);

# delete function to delete object on google bucket if needed
function delete_object($bucketName, $objectName)
{
    $storage = new StorageClient();
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->object($objectName);
    $object->delete();
}
?>