<?php

namespace App\Form;

use App\Entity\Comment;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'required' => true,
                'label' => ' ',
                'attr' => ['class' => 'form-control']
            ])
            ->add('captcha', CaptchaType::class, [
                'label' => 'Captcha*',
                'attr' => ['class' => 'ml-2']
            ])
            ->add('poster', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success mb-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
