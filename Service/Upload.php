<?php namespace Service;

class Upload{

    protected $file;
    protected $uploadDir;

    public $errorMsg;
    public $uploadStatus;
    public $fileName;
    public $resultUpload;
    public $headerUpload;

    public function __construct()
    {
        $this->uploadDir = __DIR__.'/../csv';
    }

    public function doUpload()
    {
        $results = $this->upload();

        if( $results->resultUpload[0]->error != '')
        {
            $this->errorMsg    = $results->resultUpload[0]->error;
            $this->uploadStatus = false;
        }
        else
        {
            $this->uploadStatus = true;
            $this->fileName     = $results->resultUpload[0]->name;
        }

        return $this;
    }

    public function upload(){

        // Simple validation (max file size 2MB and only two allowed mime types)
        $validator = new \FileUpload\Validator\Simple(1024 * 1024 * 2, array(
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'text/csv',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ));

        // Simple path resolver, where uploads will be put
        $pathresolver = new \FileUpload\PathResolver\Simple($this->uploadDir);

        // The machine's filesystem
        $filesystem = new \FileUpload\FileSystem\Simple();

        // FileUploader itself
        $fileupload = new \FileUpload\FileUpload($_FILES['file'], $_SERVER);

        $filenamegenerator = new \FileUpload\FileNameGenerator\Random();
        $fileupload->setFileNameGenerator($filenamegenerator);
        // Adding it all together. Note that you can use multiple validators or none at all
        $fileupload->setPathResolver($pathresolver);
        $fileupload->setFileSystem($filesystem);
        $fileupload->addValidator($validator);

        // Doing the deed
        list($files, $headers) = $fileupload->processAll();

        $this->resultUpload = $files;
        $this->headerUpload = $headers;

        return $this;
    }

}