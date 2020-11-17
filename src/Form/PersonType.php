<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Location;
use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Person form.
 */
class PersonType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $person = $options['person'];
        $builder->add('fullName', TextType::class, [
            'label' => 'Full Name',
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('sortableName', TextType::class, [
            'label' => 'Sortable Name',
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('gender', ChoiceType::class, [
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Unknown' => Person::UNKNOWN,
            ],
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('titles', CollectionType::class, [
            'label' => 'Titles',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => TitleType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => false,
            'attr' => [
                'class' => 'collection collection-complex',
                'help_block' => '',
            ],
            'data' => $person->getTitles(),
        ]);
        $builder->add('occupations', CollectionType::class, [
            'label' => 'Occupations',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => OccupationType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => false,
            'attr' => [
                'class' => 'collection collection-complex',
                'help_block' => '',
            ],
            'data' => $person->getOccupations(),
        ]);
        $builder->add('biography', TextareaType::class, [
            'label' => 'Biography',
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce',
            ],
        ]);

        $builder->add('birthYear', TextType::class, [
            'label' => 'Birth Year',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);

        $builder->add('deathYear', TextType::class, [
            'label' => 'Death Year',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);

        $builder->add('birthPlace', Select2EntityType::class, [
            'label' => 'Birth Place',
            'class' => Location::class,
            'remote_route' => 'location_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'location_new_popup',
                'add_label' => 'Add Location',
            ],
        ]);

        $builder->add('deathPlace', Select2EntityType::class, [
            'label' => 'Death Place',
            'class' => Location::class,
            'remote_route' => 'location_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'location_new_popup',
                'add_label' => 'Add Location',
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
            'data' => $person->getLinks(),
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
            'data' => $person->getReferences(),
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
            'data_class' => Person::class,
        ]);
        $resolver->setRequired([
            Person::class => 'person',
        ]);
    }
}
