<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('last_call_date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => Company::STATUS_ACTIVE,
                    'Closed' => Company::STATUS_CLOSED,
                    'Not started' => Company::STATUS_NOT_STARTED
                ],
            ])
            ->add('description', TextareaType::class)
            ->add('street')
            ->add('zip_code')
            ->add('city')
            ->add('phone')
            ->add('website')
            ->add('submit', SubmitType::class, ["label" => "Wyślij"]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
