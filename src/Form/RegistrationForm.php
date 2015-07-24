<?php
/**
 * Registration form.
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
 * Class RegistrationForm.
 *
 * @category Epi
 * @package Form
 * @extends AbstractType
 * @use Symfony\Component\Form\AbstractType
 * @use Symfony\Component\Form\FormBuilderInterface
 * @use Symfony\Component\OptionsResolver\OptionsResolverInterface
 * @use Symfony\Component\Validator\Constraints as Assert
 */
class RegistrationForm extends AbstractType
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
            'login',
            'text',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 6)
                        ))
        ))
            ->add(
                'password',
                'password',
                array(
                    'label' => 'Password'))
            ->add(
                'email',
                'text',
                array(
                'constraints' =>
                    new Assert\Email()
            ))
            ->add('gender', 'choice', array(
                'choices' => array(1 => 'male', 2 => 'female'),
                'expanded' => true,
                'constraints' => new Assert\Choice(array(1, 2)),
                'required'    => false,
                'empty_value' => 'Choose your gender',
                'empty_data'  => null
            ))

    }

    /**
     * Gets form name.????????????????????????????????????????????
     *
     * @access public
     *
     * @return string
     */
    public function getName()
    {
        return 'registrationForm';
    }
}