<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    private $fileName;

    public function upload(UploadedFile $file)
    {
        $file->move($this->getTargetDirectory(), $this->fileName);
    }

    public function genFileName(UploadedFile $file)
    {
        $this->fileName = md5(uniqid()).'.'.$file->guessExtension();

        return $this->fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }
}
