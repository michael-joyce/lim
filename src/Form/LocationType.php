<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Location form.
 */
class LocationType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $location = $options['location'];

        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('geonameId', null, [
            'label' => 'Geoname Id',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('latitude', NumberType::class, [
            'label' => 'Latitude',
            'html5' => true,
            'input' => 'number',
            'scale' => 8,
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('longitude', NumberType::class, [
            'label' => 'Longitude',
            'html5' => true,
            'input' => 'number',
            'scale' => 8,
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('fclass', TextType::class, [
            'label' => 'Fclass',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('fcode', TextType::class, [
            'label' => 'Fcode',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('country', TextType::class, [
            'label' => 'Country',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('links', CollectionType::class, [
            'label' => 'Links',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => LinkType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => false,
            'attr' => [
                'class' => 'collection collection-complex',
                'help_block' => '',
            ],
            'mapped' => false,
            'data' => $location->getLinks(),
        ]);
        $builder->add('references', CollectionType::class, [
            'label' => 'References',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => ReferenceType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => false,
            'attr' => [
                'class' => 'collection collection-complex',
                'help_block' => '',
            ],
            'mapped' => false,
            'data' => $location->getReferences(),
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
            'data_class' => Location::class,
        ]);
        $resolver->setRequired([
            Location::class => 'location',
        ]);
    }
}
