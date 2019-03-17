<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader
 * @package App\Service
 */
class FileService
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * FileUploader constructor.
     *
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Upload file
     *
     * @param UploadedFile $file
     *
     * @return array
     */
    public function upload(UploadedFile $file): ?array
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            return ['state' => 'error', 'message' => $e->getMessage(), 'filename' => null];
        }

        return ['state' => 'success', 'message' => 'file transfered', 'filename' => $this->targetDirectory . '/' . $fileName];
    }

    /**
     * Get target repository for file uploading
     *
     * @return string
     */
    public function getTargetDirectory(): ?string
    {
        return $this->targetDirectory;
    }
}
