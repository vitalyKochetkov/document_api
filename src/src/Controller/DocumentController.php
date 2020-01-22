<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Document;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Document controller 
 * @Route("/")
 */
class DocumentController extends AbstractController
{
    /**
     * Returns list of documents
     * 
     * @Route("/documents", name="documents_list", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $pageSize = $this->getParameter('document_page_size', 10);
        
        $documents = $this->getDoctrine()->getRepository(Document::class)->findAllByPage($page, $pageSize);
        
        return $this->json($documents, 200, [], ['groups' => ["document"]]);
    }
    
    /**
     * Returns document by id
     * 
     * @Route(
     *  "/documents/{id}", 
     *  name="documents_show", 
     *  methods={"GET", "HEAD"}, 
     *  requirements={"id"="\d+"}
     * )
     * @param int $id
     */
    public function show(int $id): Response
    {
        $document = $this->getDoctrine()
                ->getRepository(Document::class)
                ->find($id);
        
        return $this->json($document, 200, [], ['groups' => ["document"]]);
    }
    
    /**
     * @Route(
     *  "/documents", 
     *  name = "documents_create", 
     *  methods={"POST"}
     * )
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $data =  $request->request->all();
        $document = new Document();    
        if (!empty($data['name']))
        {
            $document->setName($data['name']);
        }
        
        $errors = $validator->validate($document);
        
        if (count($errors) > 0) {
            $message = [];
            foreach ($errors as $key => $error) {
                $message[$key]['property'] = $error->getPropertyPath();
                $message[$key]['message'] = $error->getMessage();
            }

            return $this->json($message);
        }
        
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($document);
        $em->flush();
        
        return $this->json($document, 200, [], ['groups' => ["document"]]);
    }
    
    /**
     * @Route(
     *  "/documents/{id}", 
     *  name="documents_delete", 
     *  methods={"DELETE"},
     *  requirements={"id"="\d+"}
     * )
     * @param int $id
     */
    public function delete(int $id): Response
    {
        
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository(Document::class)->find($id);
        
        if ($document) 
        {
            $em->remove($document);
            $em->flush();
            
            return $this->json(["message" => "document {$id} has been deleted."]);
        }
        
        return $this->json(["message" => "not found"]);
    }
}
