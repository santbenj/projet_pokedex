<?php
namespace App\Form;

use App\Entity\Pokemon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control']
            ])
            ->add('poids', NumberType::class, [
                'label' => 'Poids (hectogrammes)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('taille', NumberType::class, [
                'label' => 'Taille (décimètres)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('type1', TextType::class, [
                'label' => 'Type 1',
                'attr' => ['class' => 'form-control']
            ])
            ->add('type2', TextType::class, [
                'label' => 'Type 2',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('image', TextType::class, [
                'label' => 'URL de l\'image',
                'attr' => ['class' => 'form-control']
            ])
            ->add('statistique', StatistiqueType::class, [
                'label' => 'Statistiques'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}
