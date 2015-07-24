<?php
/**
 * Index controller.
 *
 * @link http://epi.uj.edu.pl
 * @author epi(at)uj(dot)edu(dot)pl
 * @copyright EPI 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return IndexController Result
     */
    public function connect(Application $app)
    {
        $indexController = $app['controllers_factory'];
        $indexController->get('/', array($this, 'indexAction'));
        $indexController->get('/index', array($this, 'indexAction'))
            ->bind('index');
        $indexController->get('/index/', array($this, 'indexAction'));
        return $indexController;
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
        return $app['twig']->render('index/index.twig');
    }

}