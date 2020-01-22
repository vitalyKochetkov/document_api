<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\AttachmentImage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AttachmentImageFixtures extends Fixture implements DependentFixtureInterface
{
    const IMAGE_COUNTER = 5;
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < DocumentFixtures::DOCUMENT_COUNTER; $i++)
        {
            for ($j = 0; $j < static::IMAGE_COUNTER; $j++)
            {
                $image = new AttachmentImage();
                $image->setName('path '.$j);
                $image->setAttachment($this->getReference(AttachmentFixtures::ATTACHMENT_REFERNCE.$i));
                
                $manager->persist($image);
            }
        }
        
        $manager->flush();
    }
    
    /**
     * @see DependentFixtureInterface
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            AttachmentFixtures::class
        ];
    }
}
