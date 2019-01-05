<?php

namespace OfferBundle\Form;

use Doctrine\DBAL\Types\FloatType;
use OfferBundle\Entity\Category;
use OfferBundle\Entity\City;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('isNew', CheckboxType::class)
            ->add('freeShipping', CheckboxType::class)
            ->add('price', NumberType::class, [
                'attr' => array('class'=>'form-control')
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'attr' => array('onchange'=>'getCity()', 'data-onload'=>'getCity()', 'class'=>'form-control'),
                'placeholder' => ''])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => array('class'=>'form-control'),
                'placeholder' => '']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OfferBundle\Entity\Article',
            'allow_extra_fields' => 'true'
        ));
    }
}

