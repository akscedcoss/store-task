<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Http\Response;
use Phalcon\Http\Response\Cookies;
$config = new Config([]);
// for events 
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
// Application


// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);
require_once(APP_PATH."/vendor/autoload.php");
$loader->registerNamespaces(
    [
        'App\Components' =>  APP_PATH .'/components',
        'App\Listener' =>APP_PATH .'/Listener'
    ]
);
$loader->register();
$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$application = new Application($container);


// Container For Db 
$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'phalt',
                ]
            );
        }
);
//  Container For cookies 
$container->set(
    'cookies',
    function () {
        $response = new Response();
        $signKey  = "#1dj8$=dp?.ak//j1V$~%*0XaK\xb1\x8d\xa9\x98\x054t7w!z%C*F-Jk\x98\x05\\\x5c";

        $cookies  = new Cookies();

        $cookies->setSignKey($signKey);

        $response->setCookies($cookies);
        return $cookies;
    }
);
// Container For cookies  End 


// Event  Managment Start 
$eventsManager = new EventsManager();
$eventsManager->attach(
    'notifications',
    new App\Listener\NotificationsListener()
);

$eventsManager->attach(
    'application:beforeHandleRequest',
    new App\Listener\NotificationsListener()
);
// Set Event Manager 
$application->setEventsManager($eventsManager);
// Set container
$container->set('eventsManager', $eventsManager);
// Event Managment End 
try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
