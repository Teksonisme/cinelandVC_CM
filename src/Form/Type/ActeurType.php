<?php

namespace App\Form\Type;

use App\Entity\Acteur;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;

class ActeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nomPrenom', TextType::class)
            ->add(
                'dateNaissance',
                DateType::class,
                [
                    'years' => range(1900, 2020),
                    'format' => 'dd-MM-yyyy'
                ]
            )
            ->add('nationalite', TextType::class);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Acteur::class,
        ));
    }
}
