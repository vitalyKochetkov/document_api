<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;

/**
 *
 * @author vitaly
 */
trait HasFile 
{
    /**
     *
     * @var type 
     */
    private $file = null;
    
    /**
     * Set file
     * @param File $file
     * @return \self
     */
    public function setFile(File $file = null) : self
    {
        $this->file = $file;
        
        return $this;
    }
    
    /**
     * Get file
     * @return File|null
     */
    public function getFile() : ?File
    {
        return $this->file;
    }
    
    
}
