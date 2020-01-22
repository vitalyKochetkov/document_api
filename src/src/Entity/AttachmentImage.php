<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttachmentImageRepository")
 */
class AttachmentImage implements ResponsibleInterface
{
    use HasWebPath;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var Attachment
     * 
     * @ORM\ManyToOne(targetEntity="Attachment", inversedBy="images")
     * @ORM\JoinColumn(name="attachment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $attachment;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * Get ID
     * @Groups({"image"})
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @param \App\Entity\Attachment $attachment
     * @return \App\Entity\AttachmentImage
     */
    public function setAttachment(Attachment $attachment) : AttachmentImage
    {
        $this->attachment = $attachment;
        
        return $this;
    }

    /**
     * Get name
     * @Groups({"image"})
     * @return string|null
     */
    public function getName() : string
    {
        return $this->name;
    }
    
    /**
     * Set name
     *
     * @param string $path
     * @return \App\Entity\AttachmentImage
     */
    public function setName(string $name) : AttachmentImage
    {
        $this->name = $name;
        
        return $this;
    }
    
}
