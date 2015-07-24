<?php
/**
 * Albums controller.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Controller;

use Model\CommentsModel;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommentsController.
 *
 * @category Epi
 * @package Controller
 * @implements ControllerProviderInterface
 *
 */
class CommentsController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function connect(Application $app)
    {
        $commentsController = $app['controllers_factory'];
        $commentsController->get('/', array($this, 'indexAction'))->bind('comments_index');
        $commentsController->get('/index', array($this, 'indexAction'));
        $commentsController->get('/index/', array($this, 'indexAction'));
        $commentsController->get('/view/{id}', array($this, 'viewAction'))->bind('comments_view');
        $commentsController->get('/view/{id}/', array($this, 'viewAction'));
        return $commentsController;
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
        $view = array();
        $commentsModel = new CommentsModel($app);
        $view['comments'] = $commentsModel->getAll();
        return $app['twig']->render('comments/index.twig', $view);
    }

    /**
     * View action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
//    public function viewAction(Application $app, Request $request)
//    {
//        $view = array();
//        $id = (int)$request->get('id', null);
//        return $app['twig']->render('comments/view.twig', $view);
//    }

}