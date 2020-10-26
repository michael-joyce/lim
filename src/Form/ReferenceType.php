<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Reference;
use App\Entity\ReferenceRole;
use App\Entity\Work;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Reference form.
 */
class ReferenceType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('referenceRole', Select2EntityType::class, [
            'label' => 'Reference Role',
            'class' => ReferenceRole::class,
            'remote_route' => 'reference_role_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('citation', TextareaType::class, [
            'label' => 'Citation',
            'required' => true,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce',
            ],
        ]);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Reference::class,
        ]);
    }
}
