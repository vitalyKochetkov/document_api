<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Document;

class DocumentFixtures extends Fixture
{
    public const DOCUMENT_COUNTER = 20;
    public const DOCUMENT_REFERNCE = 'document_';

    /**
     * Load document fixtures
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager) : void
    {
    
        // create dummy documents
        for($i = 0; $i < static::DOCUMENT_COUNTER; $i++)
        {
            $document = new Document();
            $document->setName('document ' . $i);
            $manager->persist($document);
            
            $this->addReference(static::DOCUMENT_REFERNCE.$i, $document);
        }

        $manager->flush();
    }
}
