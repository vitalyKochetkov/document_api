<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttachmentRepository")
 */
class Attachment implements ResponsibleInterface
{
    use HasWebPath;
    use HasFile;
    
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
     * @var Document
     * 
     * @ORM\OneToOne(targetEntity="Document", inversedBy="attachment")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $document;
    
    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="AttachmentImage", mappedBy="attachment")
     */
    protected $images;

    /**
     * Constructor
     */
    public function __construct() 
    {
        $this->images = new ArrayCollection();
    }

    /**
     * Get ID
     * @Groups({"attachment"})
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get filename
     * @Groups({"attachment"})
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set file name
     *
     * @param string $name
     * @return \App\Entity\Attachment
     */
    public function setName(string $name) : Attachment
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Get document
     * @return \App\Entity\Document
     */
    public function getDocument() : Document
    {
        return $this->document;
    }
    
    /**
     * Set Document
     *
     * @param \App\Entity\Document $document
     * @return \App\Entity\Attachment
     */
    public function setDocument(Document $document) : Attachment
    {
        $this->document = $document;
        
        return $this;
    }
    
    /**
     * Get images
     *
     * @return Collection|AttachmentImage[]
     */
    public function getImages() : Collection
    {
        return $this->images;
    }
    
}
