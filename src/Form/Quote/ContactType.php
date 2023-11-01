<?php

namespace App\Form\Quote;

use App\Entity\Quote\Contact;
use App\Enum\GenderEnum;
use App\Form\Type\EnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('gender', EnumType::class, [
                'enum' => GenderEnum::class,
                'expanded' => true,
            ])
            ->add('phone', TextType::class)
            ->add('email', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'label_format' => 'form.contact.%name%',
        ]);
    }
}
