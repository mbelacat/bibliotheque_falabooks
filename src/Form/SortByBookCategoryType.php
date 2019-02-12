<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SortByBookCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->setAction($this->generateUrl('book_index'))
        // ->setMethod('GET')
        ->add('name',EntityType::class, [
          'class' => Category::class,
          'label'  => false,

          ])
        ->add("Tier", SubmitType::class, [
          'attr' => ['class' => 'btn btn-success ']
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          // 'data_class' => Category::class,
          "csrf_protection" => false,
          'attr' => ['class' => 'form-inline my-3']
        ]);
    }

    public function getBlockPrefix()
    {
      return '';
    }
}
