<?php

namespace App\Form;

use App\Entity\TeamMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class TeamMemberType extends AbstractType
{
    private const NO_FIRST_NAME_MESSAGE = 'Enter team member first name.';
    private const NO_LAST_NAME_MESSAGE = 'Enter team member last name.';
    private const NO_EMAIL_MESSAGE = 'Enter team member email address.';
    private const INVALID_EMAIL_MESSAGE = 'Enter valid email address.';
    private const INVALID_BIRTH_DATE_MESSAGE = 'Invalid birth date.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(null, self::NO_FIRST_NAME_MESSAGE),
                ],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank(null, self::NO_LAST_NAME_MESSAGE)
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(null, self::NO_EMAIL_MESSAGE),
                    new Email(null, self::INVALID_EMAIL_MESSAGE)
                ]
            ])
            ->add('birthDate', DateType::class, [
                'invalid_message' => self::INVALID_BIRTH_DATE_MESSAGE,
                'widget' => 'single_text'
            ])
            ->add('Hire', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeamMember::class,
        ]);
    }
}
