<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Document;
use App\Entity\Attachment;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AttachmentUploader;

/**
 * @Route("/documents/{documentId}", requirements={"documentId"="\d+"})
 */
class AttachmentController extends AbstractController
{
    /**
     * @Route("/attachment", methods={"GET", "HEAD"}, name="attachment_show")
     * @param int $documentId
     */
    public function show(int $documentId) : Response
    {
        $attachment = $this->getDoctrine()->getRepository(Attachment::class)->findOneBy(['document' => $documentId]);
        
        if (!$attachment) {
            return $this->json(['message' => sprintf("Attachment not found")]);
        }
        
        return $this->json($attachment, 200, [], ['groups' => ["attachment"]]);
    }
    
    /**
     * @Route("/attachment", methods={"POST"}, name="attachment_create")
     * @param int $documentId
     */
    public function create(Request $request, int $documentId, AttachmentUploader $uploadred) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository(Document::class)->find($documentId);
        
        if ($document->getAttachment())
        {
            return $this->json(['message' => sprintf("Attachment already exists")]);
        }
        
        $attachment = new Attachment();
        $data = $request->files->all();
        
        if (empty($data)) 
        {
            return $this->json(['message' => sprintf("Ivalid data")]);
        }
        
        $file = reset($data);
            
        $attachment->setFile($file);
        $attachment->setDocument($document);

        return $this->json($attachment, 200, [], ['groups' => ["attachment"]]);
    }
}
