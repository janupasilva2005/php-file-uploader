<?php

/**
 * FileUploader class
 * 
 * How to use this
 * 
 * 1. include or require the file
 * 2. create a new instance
 * 		
 * 		$fileUploader = new FileUploader($_FILES[<your_file_name>], './images', 5000000);
 * 
 * 4. Make sure that the destination folder exists and writable
 * 
 * 3. set the key
 * 		
 * 	  use something like a username or something specific to a user
 * 		$fileUploader->setKey(<user_name>);
 * 
 * 4. store the file
 * 
 * 		$isUploaded = $fileUploader->store();
 * 
 * 5. Validate the upload
 * 
 * 		if($isUploaded['status'] === 1) {
 * 			// success, save to db
 * 			$fileName = $isUploaded['data'];
 * 		} else {
 * 			// file not uploaded
 * 			$errors = $isUploaded['data'];
 * 		}
 */
class FileUploader
{
	private $file;
	private $path;
	private $size;
	private $extensions;
	private $key;

	/**
	 * Class constructor
	 * 
	 * @param file
	 * @param path
	 * @param size
	 * */
	public function __construct($file, $path, $size, $extensions = ['jpeg', 'jpg', 'png'])
	{
		$this->file = $file;
		$this->path = $path;
		$this->size = $size;
		$this->extensions = $extensions;
	}

	/**
	 * This will reduce storing the same image with the same name
	 * Use something like a username or something uinque to a user
	 * 
	 * @param key
	 * */
	public function setKey($key)
	{
		$this->key = $key;
	}

	/**
	 * Store the file
	 * 
	 * @return array
	 * 
	 * status code
	 * 		1 - successfull upload
	 * 		0 - invalid upload
	 * */
	public function store()
	{

		// All errors belongs to the file upload
		$errors = [];

		// check

		$name = $this->file['name'];
		$temp_name = $this->file['tmp_name'];
		$size = $this->file['size'];
		$extension = explode('.', $this->file['name'])[1];

		/**
		 * Check if the destination folder exists and it is writable
		 * */
		if(!is_dir(__DIR__ . '/' . $this->path) || !is_writable(__DIR__ . '/' . $this->path))
		{
			$errors[] = 'Destination folder does not exists or not writable';
		}

		// Check for file size
		if($this->sizeCheck($size) === false) {
			$errors[] = 'File size cannot be more than ' . $this->size;
		}

		// Extension check for file type
		if($this->extensionCheck($extension) === false) {
			$errors[] = 'File type is invalid';
		}

		/**
		 * If the upload is successfull then return the filename
		 * */
		if(count($errors) === 0) {
			/**
			 * Making a safe file name to avoid same filenames
			 * */
			$randomKey = $this->makeRandomKey();

			/**
			 * If the private key is set it will append to the name,
			 * otherwise it will just ignore
			 * */
			if($this->key)
			{
				$file_name = $this->key . '-' . $randomKey . '-' . $name;
			} else
			{
				$file_name = $randomKey . '-' . $name;
			}

			// Storing the actuall file
			move_uploaded_file($temp_name, $this->path . '/' . $file_name);

			/**
			 * Returning the filename so the user can save it in the database,
			 * or do anything related to that
			 * */
			return array(
				'status' => 1,
				'data' => $file_name
			);
		}
		else {
			return array(
				'status' => 0,
				'data' => $errors
			);
		}
	}

	/**
	 * Check if the size is a valid
	 * 
	 * @param fileSize
	 * */
	public function sizeCheck($fileSize)
	{
		if($fileSize > $this->size)
		{
			return false;
		}

		return true;
	}

	/**
	 * Check if the extension is matched
	 * 
	 * @param extension
	 * */
	public function extensionCheck($extension)
	{
		if(!in_array($extension, $this->extensions))
		{
			return false;
		}
	}

	/**
	 * This will generate a random number to append the filename
	 * 
	 * @return integer
	 * */
	public function makeRandomKey()
	{
		return rand(1, 99999);
	}

}