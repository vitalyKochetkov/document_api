<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\AttachmentImage;
use App\Entity\Document;

/**
 * @Route("/documents/{documentId}/attachment", requirements={"documentId"="\d+"})
 */
class AttachmentImageController extends AbstractController
{
    /**
     * @Route("/previews", name="attachment_image_list")
     * @param int $documentId
     * @return Response
     */
    public function index(int $documentId) : Response
    {
        $document = $this->getDoctrine()->getRepository(Document::class)->find($documentId);
        
        if (!$document) 
        {
            return $this->json(['message' => 'Document does not exists']);
        }
        
        $attachment = $document->getAttachment();
        $images = $attachment->getImages();
        
        return $this->json($images, 200, [], ['groups' => ['image']]);
    }

    /**
     * @Route("/previews/{imageId}", name="attachment_image_show", requirements={"documentId"="\d+"})
     * @param int $documentId
     * @param int $imageId
     * @return Response
     */
    public function show(int $documentId, int $imageId) : Response
    {
        $image = $this->getDoctrine()->getRepository(AttachmentImage::class)->find($imageId);

        return $this->json($image, 200, [], ['groups' => ['image']]);
    }
}
