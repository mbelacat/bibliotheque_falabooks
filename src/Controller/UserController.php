<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


 /**
  * Require ROLE_ADMIN for *every* controller method in this class.
  * @IsGranted("ROLE_ADMIN")
  *
  */
class UserController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home()
    {
        return $this->render('index.html.twig');
    }


    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        // look for *all* User objects
        $users = $repository->findAll();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',"users"=> $users, "current_menu" => "user"
        ]);
    }

    /**
     * @Route("/user/{id}", name="user", requirements={"id"="\d+"})
     */
    public function single($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        // look for User objects by Id
        $user = $repository->findBorrowBookByUser($id);
        dump($user);
        return $this->render('user/single.html.twig', ["user"=> $user, "current_menu" => "user"
        ]);
    }

    /**
     * @Route("/users/new", name="user_new")
     */
    public function new()
    {
        $user = new User();
        $user->setLastname('Catteloin')
        ->setFirstname('Mbela')
        ->setEmail('mbela@hotmail.fr')
        ->setLogin('mbela')
        ->setPassword('$2y$12$mjze21P5MgF5PfRB8vYfD.sCmwWVqrFBtcEdqDO8YDnlA4DxS1vXC')
        ->setRoles(["ROLE_ADMIN"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        // faire un redirect()
        return $this->render('user/index.html.twig', ["users"=> $user
        ]);
    }
}
