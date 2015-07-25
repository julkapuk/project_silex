<?php
/**
 * Albums controller.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Model\ImagesModel;
use Form\ImageForm;
use Form\AddImageForm;

/**
 * Class ImagesController.
 *
 * @category Epi
 * @package Controller
 * @implements ControllerProviderInterface
 *
 */
class ImagesController implements ControllerProviderInterface
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
     *@return ImagesController Result
     */
    public function connect(Application $app)
    {
        $imagesController = $app['controllers_factory'];

        $imagesController->match('/', array($this, 'indexAction'))
            ->bind('images');
        $imagesController->get('/index', array($this, 'indexAction'));
        $imagesController->get('/index/', array($this, 'indexAction'));

        $imagesController->get('/{page}', array($this, 'indexAction'))
            ->value('page', 1)->bind('images_index');

        $imagesController->get('/view/{id}/', array($this, 'viewAction'));
        $imagesController->get('/view/{id}', array($this, 'viewAction'))
            ->bind('image_view');

        $imagesController->post('/u/add', array($this, 'addAction'));
        $imagesController->match('/u/add', array($this, 'addAction'))
            ->bind('image_add');
        $imagesController->match('/u/add/', array($this, 'addAction'));

//        $imagesController->post('u/{id}/edit/{id}', array($this, 'editAction'));
//        $imagesController->match('u/{id}/edit/{id}', array($this, 'editAction'))
//            ->bind('image_edit');
//        $imagesController->match('u/{id}/edit/{id}/', array($this, 'editAction'));

//        $imagesController->post('/delete/{id}', array($this, 'deleteAction'));
//        $imagesController->match('/delete/{id}', array($this, 'deleteAction'))
//            ->bind('albums_delete');
//        $imagesController->match('/delete/{id}/', array($this, 'deleteAction'));
        $imagesController->match('/image_photography/', array($this, 'photographyAction'));
        $imagesController->match('/image_photography', array($this, 'photographyAction'))
            ->bind('image_photography');

        $imagesController->match('/image_design/', array($this, 'designAction'));
        $imagesController->match('/image_design', array($this, 'designAction'))
            ->bind('image_design');

        $imagesController->match('/image_quotes/', array($this, 'quotesAction'));
        $imagesController->match('/image_quotes', array($this, 'quotesAction'))
            ->bind('image_quotes');

        $imagesController->match('/image_fine_art/', array($this, 'fine_artAction'));
        $imagesController->match('/image_fine_art', array($this, 'fine_artAction'))
            ->bind('image_fine_art');

        $imagesController->match('/image_street_art/', array($this, 'street_artAction'));
        $imagesController->match('/image_street_art', array($this, 'street_artAction'))
            ->bind('image_street_art');

        return $imagesController;
    }

//    /**
//     * Index action.
//     *
//     * @access public
//     * @param Silex\Application $app Silex application
//     * @param Symfony\Component\HttpFoundation\Request $request Request object
//     * @return string Output
//     */
//    public function indexAction(Application $app, Request $request)
//    {
//        $pageLimit = 3;
//        $page = (int) $request->get('page', 1);
//        $albumsModel = new AlbumsModel($app);
//        $pagesCount = $albumsModel->countAlbumsPages($pageLimit);
//        $page = $albumsModel->getCurrentPageNumber($page, $pagesCount);
//        $albums = $albumsModel->getAlbumsPage($page, $pageLimit);
//        $this->view['paginator']
//            = array('page' => $page, 'pagesCount' => $pagesCount);
//        $this->view['albums'] = $albums;
//        return $app['twig']->render('albums/index.twig', $this->view);
//    }
//
//    /**
//     * View action.
//     *
//     * @access public
//     * @param Silex\Application $app Silex application
//     * @param Symfony\Component\HttpFoundation\Request $request Request object
//     * @return string Output
//     */
//    public function viewAction(Application $app, Request $request)
//    {
//        $id = (int)$request->get('id', null);
//        $albumsModel = new AlbumsModel($app);
//        $this->view['album'] = $albumsModel->getAlbum($id);
//        return $app['twig']->render('albums/view.twig', $this->view);
//    }

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
        $imagesModel = new ImagesModel($app);
        $view['images'] = $imagesModel->getAllImages();
        return $app['twig']->render('images/index.twig', $view);
    }

    /**
     * View action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function viewAction(Application $app, Request $request)
    {
        $id = (int)$request->get('id', null);
        $imagesModel = new ImagesModel($app);
        $this->view['images'] = $imagesModel->getImages($id);
        return $app['twig']->render('images/view.twig', $this->view);
    }

    /**
     * Add action.
     *
     * @access public
     *
     * @param Application $app Silex application
     * @param Request $request Request object
     *
     * @return string Output
     */
    public function addAction(Application $app, Request $request)
    {
        // default values:
        $data = array();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();

//        $token = $app['security']->getToken();
//
//        if (null !== $token) {
//            $user = $token->getUserId();
//        }

        var_dump($useriD);
        $form = $app['form.factory']
            ->createBuilder(new AddImageForm(), $data)->getForm();

        if ($request->isMethod('POST')) {

            $form->bind($request);

            if ($form->isValid()) {

                try {
                    $files = $request->files->get($form->getName());
                    $data = $form->getData();
                    $data['data'] = date("Y/m/d");
//                    $data['id_user'] =
                    echo "bu";
                    var_dump($data);
                    $mediaPath = dirname(dirname(dirname(__FILE__))) . '/web/upload';
                    $imagesModel = new ImagesModel($app);
                    $imagesModel->saveImage($files, $mediaPath, $data);

                    $app['session']->getFlashBag()->add(
                        'message',
                        array(
                            'type' => 'success',
                            'content' => $app['translator']
                                ->trans('File successfully uploaded.')
                        )
                    );

                } catch (Exception $e) {
                    $app['session']->getFlashBag()->add(
                        'message',
                        array(
                            'type' => 'error',
                            'content' => $app['translator']
                                ->trans('Can not upload file.')
                        )
                    );
                }

            } else {
                $app['session']->getFlashBag()->add(
                    'message',
                    array(
                        'type' => 'error',
                        'content' => $app['translator']
                            ->trans('Form contains invalid data.')
                    )
                );
            }

        }
        $this->view['form'] = $form->createView();
        return $app['twig']->render('images/add.twig', $this->view);
    }

}