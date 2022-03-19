<?php

/**
 * FileUploader class
 * 
 * This will handle the file uploads easily
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

		$name = $this->file['name'];
		$temp_name = $this->file['tmp_name'];
		$size = $this->file['size'];
		$extension = explode('.', $this->file['name'])[1];

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
			$file_name = $this->key . '-' . $randomKey . '-' . $name;

			// Storing the actuall file
			move_uploaded_file($temp_name, $this->path . $file_name);

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
	 * */
	public function makeRandomKey()
	{
		return rand(1, 99999);
	}

}