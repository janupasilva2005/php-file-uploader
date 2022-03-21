# PHP-File-Uploader

## This is a helper library for uploading files easily with php

## How to use,

### 1. First include the class in your file
```php
	include './fileUploader.php'; // This depends on your project file tree
```

### 2. Create a new instant with the data
```php
	$fu = new FileUploader($_FILES['image'], './images', 5000000);
```

### 3. Set the private key
```php
	$fu->setKey('janupa') // Use something specific to a user
```

### 4. Store the file
```php
	$isUploaded = $fu->store();
```

### 5. Validate the upload
```php
	if($isUploaded['status'] == 1) {
		// valid upload
		$file_name = $isUploaded['data'];
	}
	else {
		// not valid upload
		$errors = $isUploaded['data'];
	}
```