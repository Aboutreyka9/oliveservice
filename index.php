<?php

// if (session_status() === PHP_SESSION_NONE) {
session_name("APP545645465654_SESSION");
session_start();
include __DIR__ . '/app/Core/security.php';

// }

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\Gestionnaires\ActiviteController;
use App\Controllers\Gestionnaires\BoutiqueController;
use App\Controllers\Gestionnaires\CautisationController;
use App\Controllers\Gestionnaires\ClientController;
use App\Controllers\Gestionnaires\CommercialController;
use App\Controllers\Gestionnaires\ControllerException;
use App\Controllers\Gestionnaires\DistributionController;
use App\Controllers\Gestionnaires\FinanceController;
use App\Controllers\Gestionnaires\HomeController;
use App\Controllers\Gestionnaires\ReportController;
use App\Controllers\Gestionnaires\SettingController;
use App\Controllers\Gestionnaires\ValidationController;
use App\Controllers\Gestionnaires\VersementCommercialController;
use App\Controllers\Commercials\ClientController as CommercialsClientController;
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


// var_dump(((new ActiviteModel())->getPackBySessionAndCategorie('l8rmIqVzNWaRYF6Nb7kuckHC','6QIlVfXP0LiXE9tBzHownYLAA324qDi2','5454544456','0GklBk07waYoLB6pHwY')));
// var_dump(session_destroy());
// var_dump(session_destroy());
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
$router->filter('super', [RouteMiddleWare::class, 'requireSuper']);
$router->filter('admin', [RouteMiddleWare::class, 'requireAdmin']);
$router->filter('comptable', [RouteMiddleWare::class, 'requireComptable']);
$router->filter('gestion', [RouteMiddleWare::class, 'requireGestion']);
$router->filter('commercial', [RouteMiddleWare::class, 'requireCommercial']);

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

    $router->group(['before' => 'auth', 'prefix' => '/'], function ($router) {


    // ROUTES POUR COMMERCIAL
    $router->group(['before' => 'commercial', 'prefix' => '/commercial'], function ($router) {
  // <!-- Commercials / Client -->
        $router->get('souscriptions', [CommercialsClientController::class, 'souscription']);
        $router->get('resouscriptions', [CommercialsClientController::class, 'resouscription']);
        $router->get('souscriptions/liste', [CommercialsClientController::class, 'listeInscription']);
        $router->get('clients/liste', [CommercialsClientController::class, 'liste']);
        $router->get('clients/profile/{code}', [CommercialsClientController::class, 'profile'], ['before' => 'auth']);
        $router->get('souscription/detail/{code}', [CommercialsClientController::class, 'inscriptionDetail'], ['before' => 'auth']);
        $router->get('cautions', [CautisationController::class, 'liste']);
        $router->get('cautions/encaisser', [CautisationController::class, 'encaisser']);
    });


    // <!-- sexion utilisateur  -->

        $router->get('dashboard', [HomeController::class, 'acueil'], ['before' => 'auth']);
        $router->get('/', [HomeController::class, 'acueil'], ['before' => 'auth']);

        

        $router->get('recrutements', [UserController::class, 'recrutement'], ['before' => 'admin|super']);
        $router->get('personnel-commercials', [UserController::class, 'commercials'], ['before' => 'admin|super']);
        $router->get('personnel-administratifs', [UserController::class, 'administratif'], ['before' => 'admin|super']);
        $router->get('utilsateur/profile/{code}', [UserController::class, 'profile'], ['before' => 'auth']);
        $router->get('commerciaux/profile/{code}', [CommercialController::class, 'profile'], ['before' => 'auth']);

        // <!-- parametrage -->
        $router->get('services-fonctions', [SettingController::class, 'fonction'], ['before' => 'admin|super']);
        $router->get('annees-sessions', [SettingController::class, 'annee'], ['before' => 'admin|super']);

      

        // <!-- Activity -->
        $router->get('zones', [ActiviteController::class, 'zone'], ['before' => 'admin|super|gestion|commercial']);
        $router->get('packs', [ActiviteController::class, 'pack'], ['before' => 'admin|super']);
        $router->get('detail-pack/{code}', [ActiviteController::class, 'detailPack'], ['before' => 'admin|super']);
        $router->get('categories-packs', [ActiviteController::class, 'categorie'], ['before' => 'admin|super']);
        $router->get('articles', [ActiviteController::class, 'article'], ['before' => 'admin|super']);

        // <!-- Finance -->
        $router->get('depenses', [FinanceController::class, 'depense'], ['before' => 'comptable|admin|super']);
        $router->get('versements-commerciaux', [VersementCommercialController::class, 'liste'], ['before' => 'commercial|admin|super|gestion|comptable']);
        $router->get('validations', [ValidationController::class, 'liste'], ['before' => 'gestion|admin|super']);
        $router->get('distributions', [DistributionController::class, 'liste'], ['before' => 'gestion|admin|super']);

        // <!-- Reports -->
        $router->get('rapports', [ReportController::class, 'dashboard'], ['before' => 'auth']);
        $router->get('rapports/souscriptions', [ReportController::class, 'souscriptions'], ['before' => 'auth']);
        $router->get('rapports/cautions', [ReportController::class, 'cautions'], ['before' => 'auth']);
        $router->get('rapports/versements', [ReportController::class, 'versements'], ['before' => 'auth']);
        $router->get('rapports/distributions', [ReportController::class, 'distributions'], ['before' => 'auth']);
        $router->get('rapports/finances', [ReportController::class, 'finances'], ['before' => 'auth']);

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