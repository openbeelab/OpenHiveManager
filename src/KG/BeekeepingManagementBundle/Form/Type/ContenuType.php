<?php

namespace KG\BeekeepingManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContenuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cadres', 'collection', array('type' => new CadreType()))
                ->add('hausses', 'collection', array(
                            'type'  => new HausseType(),
                            'label' => false
                        )); 
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KG\BeekeepingManagementBundle\Entity\Ruche'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kg_beekeepingmanagementbundle_contenu';
    }
}