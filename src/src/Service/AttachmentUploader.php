<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Upload attachments
 *
 * @author vitaly
 */
class AttachmentUploader 
{
    const TRANSLITERATOR = "Any-Latin; Latin-ASCII; [^A-Za-z0-9] remove; Lower();";

    /**
     *
     * @var string
     */
    private $rootDir;
    
    /**
     *
     * @var string
     */
    private $uploadsDir;

    /**
     * Constructor
     * @param string $targetDirectory
     */
    public function __construct(string $rootDir, string $uploadsDir) 
    {
        $this->rootDir = $rootDir;
        $this->uploadsDir = $uploadsDir;
    }
    
    public function upload(UploadedFile $attachment) : string
    {
        $originalFilename = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = static::transliterateFilename($originalFilename);
        $attachmentFilename = static::createNewFilename($safeFilename, $attachment);
        
        try {
            $attachment->move($this->getTargetDirectory(), $attachmentFilename);
        } catch (FileException $exc) {
            // Log error
        }
        
        return $attachmentFilename;
    }
    
    /**
     * Remove attachment file
     *
     * @param string $fileName
     * @return void
     */
    public function remove(string $fileName) : void
    {
        unlink($this->getTargetDirectory() . '/' . $fileName);
    }

    /**
     * Get target directory
     *
     * @return string
     */
    public function getTargetDirectory() : string
    {
        return $this->rootDir.'/'.$this->uploadsDir;
    }
    
    /**
     * Get root dir
     *
     * @return string
     */
    public function getRootDir() : string
    {
        return $this->rootDir;
    }
    
    /**
     * Get uploads dir
     *
     * @return string
     */
    public function getUploadsDir() : string
    {
        return $this->uploadsDir;
    }

    /**
     * Transliterate an attachment name to safe name
     *
     * @param string $attachmentName
     * @return string
     */
    protected static function transliterateFilename(string $attachmentFileName) : string
    {
        return transliterator_transliterate(static::TRANSLITERATOR, $attachmentFileName);
    }

    /**
     * Create unique file name
     * @param string $filename
     * @return string
     */
    protected static function createNewFilename(string $filename, UploadedFile $attachment) : string
    {
        return $filename.".".uniqid().".".$attachment->guessExtension();
    }
}
