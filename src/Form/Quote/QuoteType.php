<?php

namespace App\Form\Quote;

use App\Entity\Quote\Organization;
use App\Entity\Quote\Quote;
use App\Entity\Quote\Service;
use App\Enum\DurationEnum;
use App\Form\Type\AddressType;
use App\Form\Type\EnumType;
use App\Form\Type\GoogleCaptchaType;
use App\Form\Type\NumberType;
use App\Repository\Quote\OrganizationRepository;
use App\Repository\Quote\ServiceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('peopleCount', NumberType::class, [
                'min' => 1,
                'max' => 99,
                'scale' => 0,
            ])
            ->add('duration', EnumType::class, ['enum' => DurationEnum::class])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('comment', TextareaType::class)
            ->add('location', AddressType::class, ['label' => false])
            ->add('organization', EntityType::class, [
                'class' => Organization::class,
                'choice_label' => 'label',
                'query_builder' => function (OrganizationRepository $organizationRepository) {
                    return $organizationRepository->createQueryBuilder('organization')->orderBy('organization.rank');
                },
            ])
            ->add('organizationNote', TextType::class)
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'label',
                'query_builder' => function (ServiceRepository $serviceRepository) {
                    return $serviceRepository->createQueryBuilder('service_type')->orderBy('service_type.rank');
                },
            ])
            ->add('serviceNote', TextType::class)
            ->add('contact', ContactType::class, ['label' => false])
            ->add('captcha', GoogleCaptchaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
            'label_format' => 'form.quote.%name%',
        ]);
    }
}
