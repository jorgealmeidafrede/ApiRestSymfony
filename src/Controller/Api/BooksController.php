<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Type\BookFormType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;

class BooksController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        BookRepository $bookRepository
    ) {
        return $bookRepository->findAll();
    }

    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        EntityManagerInterface $em,
        Request $request,
        FileUploader $fileUploader
    ) {
        $bookDto = new BookDto;
        $form = $this->createForm(BookFormType::class, $bookDto);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $filename = $fileUploader->uploadBase64File($bookDto->base64Image);

            $book = new Book;
            $book->setTitle($bookDto->title);
            $book->setImage($filename);
            $em->persist($book);
            $em->flush();
            return $book;
        }
        return $form;
    }
}
