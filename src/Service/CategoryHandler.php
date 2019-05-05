<?php

namespace App\Service;

use App\Entity\Category;
use Symfony\Component\Form\Form ;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class CategoryHandler
{
  protected $form;
  protected $request;
  protected $em;

  public function __construct(Form $form, Request $request, EntityManagerInterface $em)
  {
    $this->form = $form;
    $this->request = $request;
    $this->em = $em;
  }

  public function process()
  {
    $this->form->handleRequest($this->request);

    if($this->form->isSubmitted() && $this->form->isValid() && $this->request->isMethod('POST')){
      $this->onSuccess();
      return true;
    }
    return false;
  }

  public function getForm()
  {
    return $this->form;
  }

  public function getAll()
  {
    return $this->em->getRepository(Category::class)->findAll();
  }

  public function onSuccess()
  {
    $this->em->persist($this->form->getData());
    $this->em->flush();
  }
}

?>
