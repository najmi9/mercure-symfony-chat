<?php

declare(strict_types=1);

namespace App\Form\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContext;

class PasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'profile.password.old.help',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ],
                'label' => 'profile.password.old.label',
            ])
            ->add('newPassword', PasswordType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'profile.password.new.help',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 1000]),
                ],
                'label' => 'profile.password.new.label',
            ])
            ->add('confirmPassword', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'profile.password.confirm.help',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Callback(['callback' => function ($value, ExecutionContext $ec): void {
                        if ($ec->getRoot()['newPassword']->getViewData() !== $value) {
                            $ec->addViolation('profile.password.confirm.msg');
                        }
                    }]),
                ],
                'label' => 'profile.password.confirm.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
        ]);
    }
}
