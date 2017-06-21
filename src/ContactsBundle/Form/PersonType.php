<?php

namespace ContactsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstName", "text")
            ->add("lastName", "text")
            ->add("description", "textarea")
            ->add("groups", "entity", array(
                'class' => 'ContactsBundle:ContactsGroup',
                'choice_label' => 'name',
                'multiple' => 'true',
                'by_reference' => false
            ))
            ->add("save", "submit", array("label"=>"Dodaj osobę"))
            ->getForm();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array("data_class" => "ContactsBundle\Entity\Person",));
    }

}
