<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Liip\ImagineBundle\Model\Binary;

/**
 * AttachmentHandler parses PDF file and create thumbnails
 *
 * @author vitaly
 */
class AttachmentHandler 
{
    /**
     *
     * @var string
     */
    private $targetDirectory;
    
    /**
     *
     * @var string
     */
    private $uploadsDirectory;

    /**
     *
     * @var string
     */
    private $attachmentDirectory;

    /**
     *
     * @var Filesystem 
     */
    private $filesystem;
    
    /**
     *
     * @var string
     */
    private $tempDirectory;

    /**
     *
     * @var FilterManager 
     */
    private $filterManager;

    /**
     * Constructor
     *
     * @param string $attachmentDirectory
     * @param string $targetDirectory
     * @param FilterManager $filterManager
     */
    public function __construct(string $attachmentDirectory, string $uploadsDirectory, FilterManager $filterManager) 
    {
        $this->attachmentDirectory = $attachmentDirectory . '/' . $this->targetDirectory;
        $this->uploadsDirectory = $uploadsDirectory;
        $this->targetDirectory = $attachmentDirectory . '/' . $uploadsDirectory;
        $this->filesystem = new Filesystem();
        $this->filterManager = $filterManager;
        $this->tempDirectory = sys_get_temp_dir().'/tmp_' . random_int(0, 1000);
    }
    
    /**
     * Parse images for attachment. Uses system component "pdfimages" for parsing images in PDF file
     * After parsing saves files in temp directory
     *
     * @param string $fileName
     * @return Process
     */
    private function parseImages(string $fileName) : Process
    {
        try {
            $this->filesystem->mkdir($this->tempDirectory);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }
        
        $attachmentPath = $this->attachmentDirectory . '/' . $fileName;
        
        $process = Process::fromShellCommandline('pdfimages -png -q "$attachmentPath" image', $this->tempDirectory);
        $process->run(null, [
            'attachmentPath' => $attachmentPath
        ]);
        
        return $process;
    }
    
    /**
     * Iterator for images in temp directory
     */
    private function getImages()
    {
        $finder = new Finder();
        $finder->files()->in($this->tempDirectory);
        
        if (!$finder->hasResults()) 
        {
            return;
        }
        
        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            yield $absoluteFilePath;
            unset($file);
        }
    }

    /**
     * Returns array of thumbnails names. When thumbnails created remove temp directory
     *
     * @return array
     */
    private function createThumbnails() : array
    {
         $images = [];
         foreach ($this->getImages() as $image) 
         {
            $file = new File($image);
            $fileName = $this->createThumbnail($file);
            
            $images[] = $fileName;
            unset($file);
         }
         
         try {
            $this->filesystem->remove($this->tempDirectory);
        } catch (IOExceptionInterface $exception) {
            //Log error
        }
         
         return $images;
    }

    /**
     * Create image thumbnail. Returns thumbnail file name
     *
     * @param File $file
     * @return string
     */
    private function createThumbnail(File $file) : string
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $contents = file_get_contents($file);

        $binary = new Binary(
            $contents,
            $file->getMimeType(),
            $file->guessExtension()
        );

        $response = $this->filterManager->applyFilter($binary, 'attachment_thumb');
        $thumb = $response->getContent();                               
        $f = fopen($this->targetDirectory .'/'. $fileName, 'w');        
        fwrite($f, $thumb);                                            
        fclose($f); 
        
        return $fileName;
    }

    /**
     * Parse attachment's images and create thumbnails
     * 
     * @param string $fileName
     * @return array
     * @throws ProcessFailedException
     */
    public function handleAttachment(string $fileName) : array
    {
        $process = $this->parseImages($fileName);
        
        if (!$process->isSuccessful()) 
        {
           throw new ProcessFailedException($process); 
        }
        
        return $this->createThumbnails();
    }
    
    /**
     * Remove thumbnail
     * @param string $name
     * @return void
     */
    public function removeThumbnail(string $name) : void
    {
        unlink($this->targetDirectory . '/' . $name);
    }

    /**
     * Get target directory
     *
     * @return string
     */
    public function getTargetDirectory() : string
    {
        return $this->targetDirectory;
    }
    
    /**
     * Get uploads directory
     * @return string
     */
    public function getUploadsDirectory() : string
    {
        return $this->uploadsDirectory;;
    }
    
}
