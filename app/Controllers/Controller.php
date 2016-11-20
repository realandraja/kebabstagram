<?php


namespace App\Controllers;


class Controller

{

	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function __get($property)
	{
		if($this->container->{$property})
		{
			return $this->container->{$property} ;
		}

	}

	public function upload($input_name,$upload_dir,$max_size,$allowed_types)
    {
        $storage = new \Upload\Storage\FileSystem($upload_dir);
        $file = new \Upload\File($input_name, $storage);

        // Generate unique file name
        $new_filename = uniqid();
        $file->setName($new_filename);
      
        $file->addValidations(array(
            // Ensure file is of desired type
            new \Upload\Validation\Mimetype($allowed_types),
            // Fix the max file size
            new \Upload\Validation\Size($max_size)
        ));

        try {

            $file->upload();
        }
        catch (\Exception $e) {
            $errors = $file->getErrors();
        }


        // Access data about the file that has been uploaded
        $data = array(
            'name'       => $file->getNameWithExtension(),
            'extension'  => $file->getExtension(),
            'mime'       => $file->getMimetype(),
            'size'       => $file->getSize(),
            'md5'        => $file->getMd5(),
            'dimensions' => $file->getDimensions(),
            'error' => $file->getErrors()
        );



    return $data;
        
    }
}