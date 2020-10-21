<?php

namespace App\Form;

use App\Entity\Person;
use App\Entity\CircaDate;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Person form.
 */
class PersonType extends AbstractType {

    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
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
        $builder->add('gender', TextType::class, [
            'label' => 'Gender',
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('biography', TextareaType::class, [
            'label' => 'Biography',
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce',
            ],
        ]);

        $builder->add('birthYear', Select2EntityType::class, [
            'label' => 'CircaDate',
            'class' => CircaDate::class,
            'remote_route' => 'circa_date_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'circa_date_new_popup',
                'add_label' => 'Add CircaDate',
            ],
        ]);

        $builder->add('deathYear', Select2EntityType::class, [
            'label' => 'CircaDate',
            'class' => CircaDate::class,
            'remote_route' => 'circa_date_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'circa_date_new_popup',
                'add_label' => 'Add CircaDate',
            ],
        ]);

        $builder->add('birthPlace', Select2EntityType::class, [
            'label' => 'Location',
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
            'label' => 'Location',
            'class' => Location::class,
            'remote_route' => 'location_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'location_new_popup',
                'add_label' => 'Add Location',
            ],
        ]);

    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }

}
