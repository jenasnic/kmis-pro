<?php

namespace App\Form\Quote;

use App\Entity\Quote\OrganizationType;
use App\Entity\Quote\Quote;
use App\Entity\Quote\ServiceType;
use App\Enum\DurationEnum;
use App\Form\Type\AddressType;
use App\Form\Type\EnumType;
use App\Form\Type\GoogleCaptchaType;
use App\Form\Type\NumberType;
use App\Repository\Quote\OrganizationTypeRepository;
use App\Repository\Quote\ServiceTypeRepository;
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
            ->add('organizationType', EntityType::class, [
                'class' => OrganizationType::class,
                'choice_label' => 'label',
                'query_builder' => function (OrganizationTypeRepository $organizationTypeRepository) {
                    return $organizationTypeRepository->createQueryBuilder('organization_type')->orderBy('organization_type.rank');
                },
            ])
            ->add('otherOrganizationType', TextType::class)
            ->add('serviceType', EntityType::class, [
                'class' => ServiceType::class,
                'choice_label' => 'label',
                'query_builder' => function (ServiceTypeRepository $serviceTypeRepository) {
                    return $serviceTypeRepository->createQueryBuilder('service_type')->orderBy('service_type.rank');
                },
            ])
            ->add('otherServiceType', TextType::class)
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
