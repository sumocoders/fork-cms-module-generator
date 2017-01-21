<?php

namespace ModuleGenerator\PhpGenerator\Constant;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ConstantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ]
        )->add(
            'value',
            $options['type_class_name'],
            [
                'label' => $options['value_label'],
                'required' => true,
                'constraints' => $options['value_constraints'],
            ]
        )->addModelTransformer(
            new CallbackTransformer(
                function (Constant $constant = null) {
                    return new ConstantDataTransferObject($constant);
                },
                function (ConstantDataTransferObject $constantDataTransferObject) {
                    return Constant::fromDataTransferObject($constantDataTransferObject);
                }
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ConstantDataTransferObject::class,
                'label' => 'Constant',
                'required' => true,
                'value_label' => 'Value',
                'value_constraints' => [
                    new NotBlank(),
                ],
            ]
        );
    }
}
