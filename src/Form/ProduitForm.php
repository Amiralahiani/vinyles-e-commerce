<?php

namespace App\Form;

use App\Repository\CategoryRepository;
use App\Repository\ArtistRepository;

use App\Entity\Category;
use App\Entity\Produit;
use App\Entity\Artist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class ProduitForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'scale' => 2,
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Stock',
            ])
            ->add('category_name', TextType::class, [
                'mapped' => false,
                'label' => 'Catégorie (nouvelle ou existante)',
            ])
            ->add('artist_name', TextType::class, [
                'mapped' => false,
                'label' => 'Artiste (nouveau ou existant)',
            ])
            ->add('image', FileType::class, [
                'label' => 'Image du vinyle',
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
