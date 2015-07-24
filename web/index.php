<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', E_ALL);

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Symfony\Component\Translation\Loader\YamlFileLoader;

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => dirname(dirname(__FILE__)) . '/src/views',
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(
    new Silex\Provider\TranslationServiceProvider(), array(
        'locale' => 'pl',
        'locale_fallbacks' => array('pl'),
    )
);
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());
    $translator->addResource('yaml', dirname(dirname(__FILE__)) . '/config/locales/pl.yml', 'pl');
    return $translator;
}));
$app->register(
    new Silex\Provider\DoctrineServiceProvider(),
    array(
        'db.options' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => '13_pukalska',
            'user'      => '13_pukalska',
            'password'  => 'lubieczekolade',
            'charset'   => 'utf8',
            'driverOptions' => array(
                1002=>'SET NAMES utf8'
            )
        ),
    )
);
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(
    new Silex\Provider\SecurityServiceProvider(),
    array(
        'security.firewalls' => array(
            'admin' => array(
                'pattern' => '^.*$',
                'form' => array(
                    'login_path' => 'auth_login',
                    'check_path' => 'auth_login_check',
                    'default_target_path'=> '/',
                    'username_parameter' => 'loginForm[login]',
                    'password_parameter' => 'loginForm[password]',
                ),
                'anonymous' => true,
                'logout' => array(
                    'logout_path' => 'auth_logout',
                    'target_url' => '/'
                ),
                'users' => $app->share(
                    function() use ($app)
                    {
                        return new Provider\UserProvider($app);
                    }
                ),
            ),
        ),
        'security.access_rules' => array(
            array('^/.+$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
//            array('^/user/add.+$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/images/u/add', 'ROLE_USER'),
            array('^/admin/.+$', 'ROLE_ADMIN')
        ),
        'security.role_hierarchy' => array(
            'ROLE_ADMIN' => array('ROLE_USER'),
        ),
    )
);
//var_dump($app['security.encoder.digest']->encodePassword('PASSWORD_TO_ENCODE', ''));
//var_dump($app['security.encoder.digest']->encodePassword('testtest', ''));

$app->mount('/hello', new Controller\HelloController());
$app->mount('/', new Controller\IndexController());
$app->mount('auth', new Controller\AuthController());
$app->mount('/registration', new Controller\RegistrationController());
$app->mount('/comments', new Controller\CommentsController());
$app->mount('/images', new Controller\ImagesController());
$app->mount('/photos', new Controller\PhotosController());

$app->run();