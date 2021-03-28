<?php

namespace App\Form;

use App\Entity\Gallery;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('content', CKEditorType::class, [
                'required' => true,
                'label' => 'Contenu :'
            ])
            ->add('status', ChoiceType::class, [
                'required' => true,
                'label' => 'Status',
                'choices' => [
                    'Publié' => 'P',
                    'Non Publié' => 'NP',
                ],
                'attr' => ['class' => 'form-control my-2']
            ])
            ->add('publier', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success mr-2']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
        ]);
    }
}
