<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat',ChoiceType::class,[
                'choices'=>$this->getChoices()
            ])
            ->add('city')
            ->add('town')
            ->add('district')
            ->add('address')
            ->add('postal_code')
            ->add('property_type', ChoiceType::class,['choices'=>$this->getProperty()])
            ->add('sold_or_rented')
            ->add('agences', EntityType::class, [
                'required' => true,
                'class' => Agence::class,
                'choice_label' => 'title'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }

    private function getChoices()
    {
        $choices = Property::HEAT;
        $output = [];
        foreach ($choices as $k => $v){
            $output[$v]=$k;
        }
        return $output;
    }

    private function getProperty()
    {
        $choices = Property::TYPEPROPERTY;
        $output = [];
        foreach ($choices as $k => $v){
            $output[$v]=$k;
        }
        return $output;
    }
}
