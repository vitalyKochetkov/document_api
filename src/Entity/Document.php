<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document implements NamedInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $name;
    
    /**
     *
     * @var Attachment
     * 
     * @ORM\OneToOne(targetEntity="Attachment", mappedBy="document")
     */
    protected $attachment = null;

    /**
     * Get ID
     * @Groups({"document"})
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * Get name
     * @Groups({"document"})
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Set name
     * 
     * @param string $name
     * @return \App\Entity\Document
     */
    public function setName(string $name) : Document
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Get attachment
     * 
     * @return \App\Entity\Attachment
     */
    public function getAttachment() : Attachment
    {
        return $this->attachment;
    }
    
    /**
     * Set attachment
     *
     * @param \App\Entity\Attachment|null $attacment
     * @return \App\Entity\Document
     */
    public function setAttachment(Attachment $attacment = null) : Document
    {
        $this->attachment = $attacment->setDocument($this);
        
        return $this;
    }
}
