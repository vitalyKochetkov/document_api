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
interface HasWebPathInterface 
{
    /**
     * Set web path
     * 
     * @param string $webpath
     * @return \self
     */
    public function setWebPath(string $webPath) : self;
    
    /**
     * Get web path
     * 
     * @return string
     */
    public function getWebPath() : string;
}
