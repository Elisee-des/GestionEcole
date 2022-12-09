<?php

namespace App\Form\Abscence;

use App\Entity\Abscence;
use App\Entity\Eleve;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditerAbscenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('heurAbscence', NumberType::class, [
            "label"=>"1h d'abscence correspond a 1"
        ])
        // ->add('eleve', EntityType::class, [
        //     "class" => Eleve::class,
        //     "label" => "Choisir l'eleve"
        // ])
        ->add('Modifier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abscence::class,
        ]);
    }
}
