<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;

class FileUploader
{
private $fileSystem;

    public function __construct(
        FilesystemOperator $defaultStorage
    )
    {
        $this->fileSystem = $defaultStorage;
    }

    public function uploadBase64File( string $base64File) : string
    {
        $extension = explode('/', mime_content_type($base64File))[1];
        $data = explode(",", $base64File);
        $filename = sprintf('%s.%s', uniqid('book_', true), $extension);
        $this->fileSystem->write($filename, base64_decode($data[1]));
        return $filename;
    }
}

