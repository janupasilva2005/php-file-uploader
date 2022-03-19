<?php

include './class/fileUploader.php';

$fileUpload = new FileUploader($_FILES['image'], 'images/', 5000000);
$fileUpload->setKey('thisismysecretkey');
$fileUpload->store();