<?php

namespace App\Form;

use App\Entity\Event;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\DateTime as AssertDateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $now = new DateTime();

        $builder
            ->add('title')
            ->add('description')
            ->add('dateTime', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide.',
                    ]),
                    new Type([
                        'type' => '\DateTimeInterface',
                        'message' => 'La valeur doit être une date valide.',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => $now,
                        'message' => 'La date et l\'heure doivent être ultérieures à maintenant.',
                    ]),
                ],
            ])
            ->add('maxParticipants')
            ->add('isPublic')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}