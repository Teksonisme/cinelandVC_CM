<?php

namespace App\Form\Type;

use App\Entity\Film;
use App\Entity\Genre;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FilmType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class)
            ->add('duree', IntegerType::class)
            ->add(
                'dateSortie',
                DateType::class,
                [
                    'years' => range(1900, 2020),
                    'format' => 'dd-MM-yyyy'
                ]
            )
            ->add('note', IntegerType::class)
            ->add('ageMinimal', IntegerType::class)
            ->add(
                'genre',
                EntityType::class,
                ['class' => Genre::class]
            )
            // # IL FAUT DONNER UN CHOIX MULTIPLE
            // ->add(
            //     'acteurs',
            //     CollectionType::class,
            //     ['entry_type' => ActeurFormType::class]
            // );
            ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Film::class,
        ));
    }
}
