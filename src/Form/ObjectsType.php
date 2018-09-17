<?php

namespace App\Form;

use App\Entity\Objects;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ObjectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageURL')
            ->add('title')
            ->add('description')
            ->add('categorie', ChoiceType::class, array(
                'choices' => array(
                    'Autres' => 'Autres',
                    'Vestes' => 'Vetements',
                    'Pantalons' => 'Vetements',
                    'Chaussures' => 'Vetements',

                    'Outils' => 'Jardin',

                    'Chiens' => 'Animaux',
                    'Chats' => 'Animaux',

                    'Electromenager' => 'Maison',
            )))

            // ->add('received')
            // ->add('giver')
            // ->add('pretenders')
            // ->add('taker')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Objects::class,
        ]);
    }
}
