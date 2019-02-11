<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SortByBookCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->setAction($this->generateUrl('book_index'))
        // ->setMethod('GET')
        ->add('name',EntityType::class, [
          'class' => Category::class,
          'choice_label' => 'name',
          'choice_value' => 'id',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => Category::class,
          'method'=> 'get',
          "csrf_protection" => false,
        ]);
    }

    public function getBlockPrefix()
    {
      return '';
    }
}
