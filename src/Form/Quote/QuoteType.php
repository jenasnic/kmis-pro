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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
            ->add('duration', EnumType::class, [
                'enum' => DurationEnum::class,
                'placeholder' => '',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('comment', TextareaType::class, ['required' => false])
            ->add('location', AddressType::class, ['label' => false])
            ->add('organization', EntityType::class, [
                'class' => Organization::class,
                'choice_label' => 'label',
                'query_builder' => function (OrganizationRepository $organizationRepository) {
                    return $organizationRepository->createQueryBuilder('organization')->orderBy('organization.rank');
                },
                'placeholder' => '',
                'empty_data' => null,
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'label',
                'query_builder' => function (ServiceRepository $serviceRepository) {
                    return $serviceRepository->createQueryBuilder('service_type')->orderBy('service_type.rank');
                },
                'placeholder' => '',
                'empty_data' => null,
            ])
            ->add('contact', ContactType::class, ['label' => false])
        ;

        if ($options['with-captcha']) {
            $builder->add('captcha', GoogleCaptchaType::class);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Quote|null $data */
            $data = $event->getData();

            $this->toggleOrganizationNote($event->getForm(), $data?->getOrganization()?->isWithNote());
            $this->toggleServiceNote($event->getForm(), $data?->getService()?->isWithNote());
        });

        $builder->get('organization')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                if (null === $form->getParent()) {
                    throw new \LogicException('invalid parent');
                }

                /** @var Organization|null $data */
                $data = $form->getData();
                $this->toggleOrganizationNote($form->getParent(), $data?->isWithNote());
            }
        );

        $builder->get('service')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                if (null === $form->getParent()) {
                    throw new \LogicException('invalid parent');
                }

                /** @var Service|null $data */
                $data = $form->getData();
                $this->toggleServiceNote($form->getParent(), $data?->isWithNote());
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('with-captcha');
        $resolver->setAllowedTypes('with-captcha', 'bool');
        $resolver->setDefaults([
            'with-captcha' => true,
            'data_class' => Quote::class,
            'label_format' => 'form.quote.%name%',
        ]);
    }

    private function toggleOrganizationNote(FormInterface $form, ?bool $state): void
    {
        if (!$state) {
            $form->remove('organizationNote');

            return;
        }

        $form->add('organizationNote', TextType::class);
    }

    private function toggleServiceNote(FormInterface $form, ?bool $state): void
    {
        if (!$state) {
            $form->remove('serviceNote');

            return;
        }

        $form->add('serviceNote', TextType::class);
    }
}
