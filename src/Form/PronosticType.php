<?php

namespace App\Form;

use App\Entity\Pronostic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PronosticType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teamA')
            ->add('teamB')
            ->add('winner')
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
