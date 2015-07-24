<?php
/**
 * Photo form.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AlbumForm.
 *
 * @category Epi
 * @package Form
 * @extends AbstractType
 * @use Symfony\Component\Form\AbstractType
 * @use Symfony\Component\Form\FormBuilderInterface
 * @use Symfony\Component\OptionsResolver\OptionsResolverInterface
 * @use Symfony\Component\Validator\Constraints as Assert
 */
class AddImageForm extends AbstractType
{
    /**
     * Form builder.
     *
     * @access public
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return  $builder->add(
            'image',
            'file',
            array(
                'label' => 'Choose file',
                'constraints' => array(
                    new Assert\Image())
            )
        )
            ->add(
                'title',
                'text',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array( 'max' => 45))
                    )
                )
            )
            ->add(
                'description',
                'textarea',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array( 'max' => 2000))
                    )
                )
            )
//            ->add(
//                'data',
//                'text',
//                array(
//                    'constraints' => array(
//                        new Assert\NotBlank(),
//                        new Assert\Length(array('min' => 1, 'max' => 200))
//                    )
//                )
//            )
            ->add(
                'license',
                'choice',
                array(
                    'choices'=>array(1=>'reuse', 2=>'reuse with modification', 3=>'noncommercial reuse', 4=>'noncommercial reuse with modification'),
                    'expanded' => true,
//                    'constraints' => new Assert\Choice(array(1-4)),
                    )
            );
    }

    /**
     * Gets form name.
     *
     * @access public
     *
     * @return string
     */
    public function getName()
    {
        return 'addimageForm';
    }
}