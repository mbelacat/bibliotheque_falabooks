<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\BookType;
use App\Form\EmpruntType;
use App\Form\SortByBookCategoryType;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/books")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="book_index", methods={"GET"})
     */

    public function index(BookRepository $bookRepository, Request $request): Response
    {
        $formBorrow = $this->createForm(EmpruntType::class); //creation du formulaire d'action d'emprunt
        $form = $this->createForm(SortByBookCategoryType::class);
        $form->handleRequest($request);
        $category = $form->getData();
        dump($category);
        // if ($form->isSubmitted() && $form->isValid()) {
        //   return $this->render('book/index.html.twig', [
        //       'books' => $bookRepository->findByCategory($category),
        //       "current_menu" => "pret",
        //       'form' => $form->createView(),
        //   ]);
        // }

            return $this->render('book/index.html.twig', [
            'formBorrow' => $formBorrow->createView(),
            'books' => $bookRepository->findAll(),
            "current_menu" => "pret",
            'form' => $form->createView(),
            'category' => $category,
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
       //var_dump($request->query);
       $form = $this->createForm(EmpruntType::class);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
         $data = $form->getData();
         //var_dump($data);
         $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["login" => $data["login"]]);
         //var_dump($user);
         if(!$user) {
           $this->addFlash("danger", "Ce N° d'identification n'est pas valide");
         }
         else {
           $book->setBorrower($user);
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($book);
           $entityManager->flush();
           $this->addFlash("success", "Le livre est emprunté");
         }
       }

       return $this->redirectToRoute('book_index');
    }
}
