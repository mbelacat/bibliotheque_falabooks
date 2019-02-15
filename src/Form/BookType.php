<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\library;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, [
              'label' => 'Titre',
            ])
            ->add('author',TextType::class, [
              'label' => 'Auteur',
            ])
            ->add('editor',TextType::class, [
              'label' => 'Editer',
            ])
            ->add('summary',TextareaType::class, [
              'label' => 'Résumé',
            ])
            ->add('releaseDate', DateType::class, [
              "widget" => "single_text",
              'label' => 'Date de parution',
            ])
            ->add('nbPage', IntegerType::class, [
              'label' => 'Nombre de page',
            ])
            ->add('available', HiddenType::class, [
              'data'    => 'true',
            ])
            ->add('category',null, [
              'label' => 'La catégorie',
            ])
            ->add('library',null, [
              'label' => 'Dans quelle librairie?',
            ])
            ->add("Enregistrer", SubmitType::class, [
              'attr' => ['class' => 'btn btn-success']
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'attr'=> ['class' => 'w-75 '],
        ]);
    }
}
