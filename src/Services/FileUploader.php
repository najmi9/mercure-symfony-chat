<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Upload Pictures.
 */
class FileUploader
{
    const USERS_FOLDER = '/public/uploads/users';
    private SluggerInterface $slugger;
    // return the root dirctory
    private string $projectDir;

    public function __construct(SluggerInterface $slugger, string $projectDir)
    {
        $this->slugger = $slugger;
        $this->projectDir = $projectDir;
    }

    public function uploadUserImage(UploadedFile $file)
    {
        $newFilename = $this->safeName($file);
        $path = $this->projectDir.self::USERS_FOLDER;

        return $this->uploadImage($file, $path, $newFilename);
    }

    public function deleteUserImage(string $filename): void
    {
        $path = $this->projectDir.self::USERS_FOLDER;
        $this->deleteImage($path.$filename);
    }

    private function uploadImage(UploadedFile $file, string $path, string $fileName): string
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        try {
            $file->move($path, $fileName);
        } catch (FileException $e) {
            throw new FileException($e->getMessage());
        }

        return $fileName;
    }

    private function deleteImage(string $image): void
    {
        if (file_exists($image)) {
            unlink($image);
        }
    }

    private function safeName(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);

        return $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    }
}
