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
interface NamedInterface 
{
    /**
     * Get name
     *
     * @return string
     */
    public function getName() : string;
    
    /**
     * Set name
     *
     * @param string $name
     * @return \self
     */
    public function setName(string $name) : self;
}
