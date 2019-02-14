<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\BookType;
use App\Form\EmpruntType;
use App\Form\SortByBookCategoryType;
use App\Form\SearchBookType;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 * @Route("/books")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="book_index", methods={"GET", "POST"})
     */

    public function index(BookRepository $bookRepository, Request $request): Response
    {
        $formBorrow = $this->createForm(EmpruntType::class); //creation du formulaire d'action d'emprunt
        $form = $this->createForm(SortByBookCategoryType::class);
        $form->handleRequest($request);
        $category = null;
         if ($form->isSubmitted() && $form->isValid()) {
             $category = $form->getData()["name"];
          }
          $books = $this->getDoctrine()->getRepository(Book::class)->findByCategory($this->getUser()->getLibrary(), $category );
        return $this->render('book/index.html.twig', [
            'books' => $books,
            "current_menu" => "pret",
            'form' => $form->createView(),
            'formBorrow' => $formBorrow->createView(),
            ]);
    }


    /**
     * @Route("/search", name="book_search", methods={"GET", "POST"})
     */
    public function searchBook(BookRepository $bookRepository, Request $request): Response
    {
      $formSearch = $this->createForm(SearchBookType::class);
      $formSearch->handleRequest($request);
      $books = null;
      if ($formSearch->isSubmitted() && $formSearch->isValid()) {
           $title = $formSearch->getData()["title"];
           $books = $this->getDoctrine()->getRepository(Book::class)->findBy(['title' => $title ]);
        }
      return $this->render('book/search.html.twig', [
          'books' => $books,
          "current_menu" => "pret",
          'formSearch' => $formSearch->createView(),
          ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
            'current_menu' => 'user',
        ]);
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
            "current_menu" => "pret",
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('book_index', [
                'id' => $book->getId(),
            ]);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }


    /**
     * @Route("/emprunter/{id}", name="book_borrow", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function emprunt($id, Request $request): Response
    {
      $book = $this->getDoctrine()->getRepository(Book::class)->findBookAndUser($id);
       if(!$book) {
         throw $this->createNotFoundException("Ce livre n'existe pas");
       }
       $form = $this->createForm(EmpruntType::class);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
         $data = $form->getData();
         $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["login" => $data["login"]]);
         if(!$user) {
           $this->addFlash("danger", "Ce N° d'identification n'est pas valide");
         }
         else {
           $book->setBorrower($user);
           $book->setBorrowingDate(new \DateTime('@'.strtotime('now')));
           $book->setReturnDate(null);
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($book);
           $entityManager->flush();
           $this->addFlash("success", "Le livre est emprunté");
         }
       }

       return $this->redirectToRoute('book_index');
    }

    /**
     * @Route("/return/{id}", name="book_return", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function return($id, Request $request): Response
    {
      $book = $this->getDoctrine()->getRepository(Book::class)->findBookAndUser($id);
      if($book->getAvailable() === false)
      {
        $book->setBorrower(null);
        $book->setReturnDate(new \DateTime('@'.strtotime('now')));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($book);
        $entityManager->flush();
        $this->addFlash("success", "Le livre est à nouveau disponible");
      }else{
        $this->addFlash("danger", "Ce livre n'a pas été emprunté");
      }
      return $this->redirectToRoute('book_index');
    }

}
