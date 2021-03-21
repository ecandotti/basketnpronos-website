<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Pronostic;
use App\Entity\Result;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PronosticType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre : ',
                'required' => true,
                'attr' => ['class' => 'form-control my-2']
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu : ',
                'required' => true
            ])
            ->add('result', ChoiceType::class, [
                'required' => true,
                'label' => 'Status',
                'choices' => [
                    'Non défini' => 'ND',
                    'Gagné' => 'G',
                    'Perdu' => 'P',
                ],
                'attr' => ['class' => 'form-control my-2']
            ])
            ->add('category', ChoiceType::class, [
                'required' => true,
                'label' => 'Status',
                'choices' => [
                    'Fiable' => 'F',
                    'Fun' => 'NF',
                ],
                'attr' => ['class' => 'form-control my-2']
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'btn btn-success mr-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pronostic::class,
        ]);
    }
}
