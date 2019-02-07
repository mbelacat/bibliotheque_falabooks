<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

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
            'controller_name' => 'UserController',"users"=> $users
        ]);
    }

    /**
     * @Route("/user/{id}", name="user", requirements={"id"="\d+"})
     */
    public function single($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        // look for User objects by Id
        $user = $repository->find($id);
        return $this->render('user/single.html.twig', ["user"=> $user
        ]);
    }

    /**
     * @Route("/users/new", name="user_new")
     */
    public function new()
    {
        $user = new User();
        $user->setLastname('Mbemba')
        ->setFirstname('Mbela')
        ->setEmail('mbela.mbemba@gmail.com')
        ->setLogin('mbela');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->render('user/index.html.twig', ["users"=> $user
        ]);
    }
}
