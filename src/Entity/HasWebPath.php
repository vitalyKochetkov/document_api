<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

/**
 *
 * @author vitaly
 */
trait HasWebPath 
{
    protected $webPath;
    
    /**
     * Get web path
     * @Groups({"attachment", "image"})
     * @return string
     */
    public function getWebPath() : string
    {
        return $this->webPath;
    }

    public function setWebPath(string $webPath) : self
    {
        $this->webPath = $webPath;
        
        return $this;
    }

}
