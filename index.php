<?php

use App\Controllers\ActiviteController;
use App\Controllers\EtudiantController;
// if (session_status() === PHP_SESSION_NONE) {
session_name("APP545645465654_SESSION");
session_start();
include __DIR__ . '/app/Core/security.php';

// }
// Charger le fichier de configuration une fois en ligne

// declare(strict_types=1);
// include 'config-production.php';
// include 'config-production-user.php';

// Activer le rapport d'erreurs (en développement uniquement)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\Controller;
use App\Controllers\ControllerException;
use App\Controllers\DashboardController;
use App\Controllers\FinanceController;
use App\Controllers\HomeController;
use App\Controllers\SettingController;
use App\Controllers\UserController;
use App\Core\Router;
use App\Middlewares\RouteMiddleWare;
use App\Models\ActiviteModel;
use Phroute\Phroute\Dispatcher;

// $m = new FinanceModel();
// $type = $m->getFieldsForParams('type_depenses', ['etablissement_code' => Auth::user('etablissement_code')], [], true);

// foreach ($type as $key) {
//     $code = $m->generatorCode('type_depenses', 'code_type_depense');
//     $m->update('type_depenses', 'id_type_depense', $key['id_type_depense'], ['code_type_depense' => $code]);
// }


// var_dump(((new ActiviteModel())->deletePackArticles('l4ymf5',['MGUlgKfYtYque','tQ0oaE7ppDJ4zETjVl9u'])));
// var_dump($_SESSION);
// return;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$title = "";

$router = new Router();

/**
 * ************************************************
 * SEXION FILTER ROUTES 
 * ************************************************
 */

/* filter  for all routes*/
$router->filter('auth', [RouteMiddleWare::class, 'requireAuth']);

$router->filter('guest', [RouteMiddleWare::class, 'isLogged']);
// $router->filter('setting', [RouteMiddleWare::class, 'requireSetting']);
// $router->filter('ghotel', [RouteMiddleWare::class, 'requireGesHotel']);
// $router->filter('comptable', [RouteMiddleWare::class, 'requireComptable']);
// $router->filter('reception', [RouteMiddleWare::class, 'requireReception']);
// $router->filter('admin', [RouteMiddleWare::class, 'requireAdmin']);

/**
 * ************************************************
 * FIN SEXION FILTER ROUTES 
 * ************************************************
 */


/**
 * ************************************************
 * DEBUT SEXION ROUTES 
 * ************************************************
 */


$router->group(['before' => '', 'prefix' => 'oliveservice'], function ($router) {

    // ─── Auth ───────────────────────────────────────────────────────────────────
    // $router->post('auth/logout',  [AuthController::class, 'logout']);
    // $router->get('auth/check',    [AuthController::class, 'check']);

    // $router->get('/register', [UserController::class, 'register'], ['before' => 'guest']);
    // $router->get('/user', [UserController::class, 'userListe'], ['before' => 'auth'])->name('home');


    // $router->get('/admin/role', [UserController::class, 'role'], ['before' => 'auth'])->name('admin.role');

    // $router->get('/setting', [SettingController::class, 'setting'], ['before' => 'auth'])->name('setting');



    /**
     * ************************************************
     * SEXION ROUTE MAIL 
     * ************************************************
     */


// $router->group(['before' => 'auth', 'prefix' => 'gestocks/t'], function ($router) {

//     $router->get('/testd', [UserController::class, 'acueil']);
// });

    /**
     * ************************************************
     * FIN SEXION ROUTE MAIL 
     * ************************************************
     */



    /**
     * ************************************************
     * SEXION ROUTE PRINTER 
     * ************************************************
     */




    /**
     * ************************************************
     *  Routes SEXION HOTEL LISTE RECAP
     * ************************************************
     */

    /**
     * ************************************************
     *  Routes SEXION VUES 
     * ************************************************
     */

    // $router->get('/', function () {
    //     $client = new Google_Client();
    // });

    $router->get('login', [AuthController::class, 'login'], ['before' => 'guest']);
    $router->get('auth', [AuthController::class, 'googleAuth'], ['before' => 'guest']);
    // <!-- sexion utilisateur  -->
    $router->group(['before' => 'auth', 'prefix' => '/'], function ($router) {

        $router->get('dashboard', [HomeController::class, 'acueil']);
        $router->get('/', [HomeController::class, 'acueil'], ['before' => 'auth']);

        $router->get('recrutements/personnel', [UserController::class, 'recrutement']);
        $router->get('personnel-commercials', [UserController::class, 'enseignants']);
        $router->get('personnel-administratifs', [UserController::class, 'administratif']);

        // <!-- parametrage -->
        $router->get('services-fonctions', [SettingController::class, 'fonction']);
        $router->get('annees-sessions', [SettingController::class, 'annee']);

        // <!-- FINNCES -->
        $router->get('depenses', [FinanceController::class, 'depense']);
        // $router->get('annees-semestres', [SettingController::class, 'annee']);

        // <!-- Client -->
        $router->get('inscriptions', [ClientController::class, 'inscription']);
        // $router->get('annees-semestres', [SettingController::class, 'annee']);

        // <!-- Activity -->
        $router->get('zones', [ActiviteController::class, 'zone']);
        $router->get('packs', [ActiviteController::class, 'pack']);
        $router->get('detail-pack/{code}', [ActiviteController::class, 'detailPack']);
        $router->get('categories-packs', [ActiviteController::class, 'categorie']);
        $router->get('articles', [ActiviteController::class, 'article']);

    });



    /**
     * Page not found
     */
    $router->get('page-not-found', [ControllerException::class, 'notFound']);

    /**
     * ************************************************
     *  FIN Routes SEXION HOTEL AUTRES
     * ************************************************
     */
});


$dispatcher = new Dispatcher($router->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $path);

echo $response;



// session_destroy();
// var_dump($_SESSION);