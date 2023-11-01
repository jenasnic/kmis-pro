<?php

namespace App\Form\Type;

use App\ValueObject\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType implements DataMapperInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street', TextType::class, [
                'attr' => [
                    'maxlength' => 100,
                    'data-localization-search' => true,
                    'data-localization-street' => true,
                    'autocomplete' => 'nope',
                ],
                'block_prefix' => 'address_street',
            ])
            ->add('zipCode', TextType::class, [
                'attr' => [
                    'maxlength' => 100,
                    'data-localization-zipcode' => true,
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'maxlength' => 100,
                    'data-localization-city' => true,
                ],
            ])
            ->setDataMapper($this)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'label_format' => 'form.address.%name%',
            'empty_data' => null,
            'error_bubbling' => false,
        ]);
    }

    public function mapDataToForms($viewData, \Traversable $forms): void
    {
        if (null === $viewData) {
            return;
        }

        if (!$viewData instanceof Address) {
            throw new Exception\UnexpectedTypeException($viewData, Address::class);
        }

        $forms = iterator_to_array($forms);

        // initialize form field values
        $forms['street']->setData($viewData->getStreet());
        $forms['zipCode']->setData($viewData->getZipCode());
        $forms['city']->setData($viewData->getCity());
    }

    public function mapFormsToData(\Traversable $forms, &$viewData): void
    {
        /** @var array{street: FormInterface, zipCode: FormInterface, city: FormInterface} $formArray */
        $formArray = iterator_to_array($forms);

        try {
            /** @var string $street */
            $street = $formArray['street']->getData();
            /** @var string $zipCode */
            $zipCode = $formArray['zipCode']->getData();
            /** @var string $city */
            $city = $formArray['city']->getData();

            $viewData = new Address($street, $zipCode, $city, null, null, 'FR');
        } catch (\Exception $e) {
            throw new TransformationFailedException('Unable to map data for address', 0, $e);
        }
    }
}
