<?php

namespace App\EventListener;

use App\Entity\Attachment;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use App\Service\AttachmentUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Document;
use App\Service\AttachmentHandler;
use App\Entity\AttachmentImage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Entity\ResponsibleInterface;
use Doctrine\Common\Collections\Collection;

/**
 * 
 *
 * @author vitaly
 */
class AttachmentUploadListener implements EventSubscriber
{
    /**
     * @var AttachmentUploader 
     */
    private $uploader;
    
    /**
     *
     * @var AttachmentHandler 
     */
    private $attachmentHandler;


    /**
     * Constructor
     * 
     * @param AttachmentUploader $uploader
     * @param RouterInterface $router
     */
    public function __construct(AttachmentUploader $uploader, AttachmentHandler $attachmentHandler) 
    {
        $this->uploader = $uploader;
        $this->attachmentHandler = $attachmentHandler;
    }

    /**
     * @see EventSubscriber
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postLoad,
            Events::preRemove,
            Events::postPersist
        ];
    }

    /**
     * Executes before attachment persists     * 
     * 
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args) : void
    {
        $entity = $args->getEntity();
        
        if (!$entity instanceof Attachment)
        {
            return;
        }
        
        $this->uploadFile($entity);
    }
    
    /**
     * Executes after attachment load
     * 
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function postLoad(LifecycleEventArgs $args) : void
    {
        $entity = $args->getEntity();
        
        if (!$entity instanceof ResponsibleInterface)
        {
            return;
        }
        
        $this->setWebPath($entity); 
    }
    
    /**
     * Executes before document removes
     *
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function preRemove(LifecycleEventArgs $args) : void
    {
        $entity = $args->getEntity();
        
        if ($entity instanceof Document)
        {
            $attachment = $entity->getAttachment();
            $this->removeFile($attachment);
            
            $images = $attachment->getImages();
            $this->removeImages($images);
        }
    }
    
    /**
     * Executes after attachment persists
     * 
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function postPersist(LifecycleEventArgs $args) : void
    {
        $entity = $args->getEntity();
        
        $manager = $args->getObjectManager();
        
        if (!$entity instanceof Attachment) 
        {
            return;
        }
        
        $images = $this->getAttachmentImages($entity);
        
        foreach ($images as $image) {
            $attachmentImage = new AttachmentImage();
            $attachmentImage->setName($image);
            $attachmentImage->setAttachment($entity);
            $manager->persist($attachmentImage);
        }
        
        $manager->flush();
        
        $this->setWebPath($entity); 
    }

        /**
     * Upload attachment file
     * 
     * @param Attachment $attachment
     * @return void
     */
    private function uploadFile(Attachment $attachment) : void
    {
        $file = $attachment->getFile();
        
        if ($file instanceof UploadedFile)
        {
            $attachmentName = $this->uploader->upload($file);
            $attachment->setName($attachmentName)->setFile(null);
        }        
    }
    
    /**
     * Remove images
     *
     * @param Collection $images
     */
    private function removeImages(Collection $images) : void
    {
        foreach ($images as $image) {
            /** @var \App\Entity\AttachmentImage $image **/
            $this->attachmentHandler->removeThumbnail($image->getName());
        }
    }

    /**
     * Delete attachment file
     *
     * @param Attachment $attachment
     * @return void
     */
    private function removeFile(Attachment $attachment) : void
    {
        $this->uploader->remove($attachment->getName());
    }

    /**
     * Set attachment web path
     *
     * @param Attachment $attachment
     * @return void
     */
    private function setWebPath(ResponsibleInterface $entity) : void
    {
        $uploadsDir = $this->uploader->getUploadsDir();
        
        if ($entity instanceof AttachmentImage)
        {
            $uploadsDir .= '/'.$this->attachmentHandler->getUploadsDirectory();
        }
        
        $webPath = '/' . $uploadsDir . '/' . $entity->getName();
        $entity->setWebPath($webPath);
    }

    /**
     * Handle attachment
     *
     * @param Attachment $attachment
     * @return array
     */
    private function getAttachmentImages(Attachment $attachment) : array
    {
        $fileName = $attachment->getName();
        
        try {
            $images = $this->attachmentHandler->handleAttachment($fileName);
        } catch (ProcessFailedException $exc) {
            //Log error
        }
        
        return $images;
    }
}
