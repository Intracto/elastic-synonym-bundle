<?php

namespace Intracto\ElasticSynonymBundle\Form;

use Intracto\ElasticSynonym\Model\Synonym;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SynonymType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('wordListLeft', TextType::class, ['label' => false])
            ->add('wordListRight', TextType::class, ['label' => false])
        ;

        $transformer = new CallbackTransformer(
            static function (?array $tags) {
                return null === $tags ? '' : implode(',', $tags);
            },
            static function (?string $tags) {
                return null === $tags ? [] : explode(',', $tags);
            }
        );


        $builder->get('wordListLeft')->addModelTransformer($transformer);
        $builder->get('wordListRight')->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Synonym::class,
        ]);
    }
}