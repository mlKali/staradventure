<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UpdateType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('articleName', HiddenType::class)
            ->add('articlePage', HiddenType::class)
            ->add('articleBody', CKEditorType::class)
            ->add('articleID', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
