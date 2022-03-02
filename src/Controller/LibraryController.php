<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

class LibraryController extends AbstractController
{
    /**
     * @Route("/books", name="book_get")
     */
    public function list(Request $request, BookRepository $bookRepository)
    {

        $response = new JsonResponse();
        $books = $bookRepository->findAll();
        $booksAsArray = [];
        foreach ($books as $book ) {
            $booksAsArray[]=[
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'image' => $book->getImage()
            ];
        }
        $response->setData([
            'success' => true,
            'data' => $booksAsArray
        ]);
        return $response;
    }
    /**
     * @Route("/book/create", name="create_book")
     */
    public function createBook(Request $request, EntityManagerInterface $em)
    {
        $book = new Book;
        $title = $request->get('title', null);
        $response = new JsonResponse();

        if ($title == null) {
            $response->setData([
                'success' => false,
                'error' => 'Title cannot be null',
                'data' => null
            ]);
            return $response;
        }
        $book->setTitle($title);
        $em->persist($book);
        $em->flush();

        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id' => $book->getId(),
                    'title' => $book->getTitle()
                ]
            ]
        ]);
        return $response;
    }
}
