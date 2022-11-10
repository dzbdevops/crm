<?php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Company;
use App\Entity\Customer;


class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('next_contact', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('company', EntityType::class, ["class" => Company::class, "choice_label" => "name"])
            ->add('customer', EntityType::class, ["class" => Customer::class, "choice_label" => "email", 'multiple' => true])
            ->add('submit', SubmitType::class, ["label" => "Wyślij"]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
