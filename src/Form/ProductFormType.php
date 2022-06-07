<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter product name',
                    'class' => 'input payment-type authcode w-full border mt-2',
                ],                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('price', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter price',
                    'class' => 'input payment-type authcode w-full border mt-2',
                ],                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('description', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter description',
                    'class' => 'input payment-type authcode w-full border mt-2 mb-2',
                ],                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('category')
            ->add('Submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'product_item',

        ]);
    }
}
