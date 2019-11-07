<?php
session_start();
use medianetapp\model\User as User;
use medianetapp\model\Document as Document;
use medianetapp\model\Borrow as Borrow;
use mf\router\Router as router;

require_once 'src/mf/utils/ClassLoader.php';
/* pour le chargement automatique des classes d'Eloquent (dans le répertoire vendor) */
require_once 'vendor/autoload.php';

$autolaod = new mf\utils\ClassLoader("src");
$autolaod->register();

\medianetapp\view\MedianetView::addStyleSheet("html/css/G_atFor.css");
\medianetapp\view\MedianetView::addStyleSheet("html/css/G_cssGrid.css");
\medianetapp\view\MedianetView::addStyleSheet("html/css/G_mixins.css");


$config = parse_ini_file('conf/config.ini');

/* une instance de connexion  */
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection( $config ); /* configuration avec nos paramètres */
$db->setAsGlobal();            /* rendre la connexion visible dans tout le projet */
$db->bootEloquent();           /* établir la connexion */

$router = new router();

$router->addRoute("catalogue","/catalogue","medianetapp\control\MedianetController","viewCatalogue",1);
$router->addRoute("login","/login","medianetapp\control\MedianetController","viewLogin",null);
$router->addRoute("check_login","/check_login","medianetapp\control\MedianetController","checkLogin",null);

$router->setDefaultRoute('/login');
/*Route : Search*/
$router->addRoute("search",
    "/search",
    "medianetapp\control\MedianetController",
    "viewSearch",
    null);

/*Route : post Search*/
$router->addRoute("to_search",
    "/to_search",
    "medianetapp\control\MedianetController",
    "search",
    null);

$router->run();