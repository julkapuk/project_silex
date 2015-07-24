<?php
/**
 * Registration controller.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Model\RegistrationModel;
use Form\RegistrationForm;

/**
 * Class RegistrationController.
 *
 * @category Epi
 * @package Controller
 * @implements ControllerProviderInterface
 *
 */
class RegistrationController implements ControllerProviderInterface
{

    /**
     * Data for view.
     *
     * @access protected
     * @var array $view
     */
    protected $view = array();

    /**
     * Routing settings.
     *
     * @access public
     *
     *@param Silex\Application $app Silex application
     *
     *@return RegistrationController Result
     */
    public function connect(Application $app)
    {
        $registrationController = $app['controllers_factory'];
        $registrationController->match('/', array($this, 'registrationAction'))
            ->bind('registration');
        $registrationController->match('/index', array($this, 'registrationAction'));
        $registrationController->match('/index/', array($this, 'registrationAction'));
        return $registrationController;
    }
    /**
     * Index action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function indexAction(Application $app, Request $request)
    {
        $user = array(
            'login' => $app['session']->get('_security.last_username')
        );

        $form = $app['form.factory']->createBuilder(new RegistrationForm(), $user)
            ->getForm();

        $this->view = array(
            'form' => $form->createView(),
            'error' => $app['security.last_error']($request)
        );

        return $app['twig']->render('registration/index.twig', $this->view);
    }
}