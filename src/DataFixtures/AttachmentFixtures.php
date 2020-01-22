<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Attachment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AttachmentFixtures extends Fixture implements DependentFixtureInterface 
{
    public const ATTACHMENT_REFERNCE = 'attachment_';
    
    /**
     * Load attachment fixtures
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager) : void
    {
        // create dummy attachments
        for ($i = 0; $i < DocumentFixtures::DOCUMENT_COUNTER; $i++)
        {
            $attachment = new Attachment();
            $attachment->setFileName('filename ' . $i);
            $attachment->setDocument($this->getReference(DocumentFixtures::DOCUMENT_REFERNCE . $i));
            $manager->persist($attachment);
            
            $this->addReference(static::ATTACHMENT_REFERNCE.$i, $attachment);
        }

        $manager->flush();
    }

    /**
     * @see DependentFixtureInterface
     * @return array
     */
    public function getDependencies() : array
    {
        return [
            DocumentFixtures::class
        ];
    }

}
