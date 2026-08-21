<?php



use App\Controllers\ActiviteController;

use App\Controllers\CautisationController;

use App\Controllers\DistributionController;

use App\Controllers\EtudiantController;

use App\Controllers\FinanceController;

use App\Controllers\ValidationController;

use App\Controllers\VersementCommercialController;



session_name("APP545645465654_SESSION");





session_start();

include __DIR__ . '/../../app/Core/security.php';



use App\Controllers\AuthController;

use App\Controllers\ClientController;

use App\Controllers\SettingController;

use App\Controllers\UserController;



require __DIR__ . '/../../vendor/autoload.php';







if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    // http_response_code(405);

    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);

    exit;

}

// var_dump($_POST);



$action = $_POST['action'] ?? null;



switch ($action) {



    // Debut Actions authentification

    case 'btnLogin':

        $ajx = new AuthController();

        $ajx->authenticate();

        break;

    case 'btn_user_deconnect':

        $ajx = new AuthController();

        $ajx->deconnexion();

        break;



    // Debut Actions pour les utilisateurs

    case 'charger_data_utilisateurs':

        $ajx = new UserController();

        $ajx->GetListeUser();

        break;

    case 'charger_data_commercials':

        $ajx = new UserController();

        $ajx->GetListeCommercial();

        break;

    case 'change_statut_utilisateurs':

        $ajx = new UserController();

        $ajx->changeStatutUser();

        break;

    case 'btn_showmodal_role_permission_utilisateur':

        $ajx = new UserController();

        $ajx->modalAddPermission();

        break;

    case 'btn_load_data_role':

        $ajx = new UserController();

        $ajx->loadDataRole();

        break;

    case 'btn_add_permission':

        $ajx = new UserController();

        $ajx->ajouterRolesPermissions();

        break;

    case 'btn_showmodal_utilisateur_add':

        $ajx = new UserController();

        $ajx->modalAddUser();

        break;

    case 'btn_showmodal_utilisateur_update':

        $ajx = new UserController();

        $ajx->modalUpdatedUtilisateurr();

        break;

    case 'btn_add_user':

        $ajx = new UserController();

        $ajx->addUser();

        break;

    case 'btn_update_user':

        $ajx = new UserController();

        $ajx->updateUser();

        break;



    //end Actions pour les utilisateurs



    // Debut Actions pour les fonctions 

    case 'charger_data_fonctions':

        $ajx = new SettingController();

        $ajx->GetListeFonction();

        break;

    case 'change_statut_fonctions':

        $ajx = new SettingController();

        $ajx->changeStatutFonction();

        break;

    case 'btn_showmodal_fonction_add':

        $ajx = new SettingController();

        $ajx->modalAddFonction();

        break;

    case 'btn_showmodal_fonction_update':

        $ajx = new SettingController();

        $ajx->modalUpdatedFonction();

        break;

    case 'btn_add_fonction':

        $ajx = new SettingController();

        $ajx->addFonction();

        break;

    case 'btn_update_fonction':

        $ajx = new SettingController();

        $ajx->updateFonction();

        break;



    //end Actions pour les fonctions





    // Debut Actions pour services

    case 'charger_data_services':

        $ajx = new SettingController();

        $ajx->GetListeServices();

        break;

    case 'change_statut_services':

        $ajx = new SettingController();

        $ajx->changeStatutService();

        break;

    case 'btn_showmodal_service_add':

        $ajx = new SettingController();

        $ajx->modalAddService();

        break;

    case 'btn_showmodal_service_update':

        $ajx = new SettingController();

        $ajx->modalUpdatedService();

        break;

    case 'btn_add_service':

        $ajx = new SettingController();

        $ajx->addService();

        break;

    case 'btn_update_service':

        $ajx = new SettingController();

        $ajx->updateService();

        break;



    //end Actions pour les fonctions



    // Debut Actions pour les annees 

    case 'charger_data_annees':

        $ajx = new SettingController();

        $ajx->GetListeAnnee();

        break;

    case 'change_statut_annees':

        $ajx = new SettingController();

        $ajx->changeStatutAnnee();

        break;

    case 'btn_showmodal_annee_add':

        $ajx = new SettingController();

        $ajx->modalAddAnnee();

        break;

    case 'btn_showmodal_annee_update':

        $ajx = new SettingController();

        $ajx->modalUpdatedAnnee();

        break;

    case 'btn_add_annee':

        $ajx = new SettingController();

        $ajx->addAnnee();

        break;

    case 'btn_update_annee':

        $ajx = new SettingController();

        $ajx->updateAnnee();

        break;



    //end Actions pour les annees



    // Debut Actions pour les Session 

    case 'charger_data_sessions':

        $ajx = new SettingController();

        $ajx->GetListeSession();

        break;

    case 'change_statut_sessions':

        $ajx = new SettingController();

        $ajx->changeStatutSession();

        break;

    case 'btn_showmodal_session_add':

        $ajx = new SettingController();

        $ajx->modalAddSession();

        break;

    case 'btn_showmodal_session_update':

        $ajx = new SettingController();

        $ajx->modalUpdatedSession();

        break;

    case 'btn_add_session':

        $ajx = new SettingController();

        $ajx->addSession();

        break;

    case 'btn_update_session':

        $ajx = new SettingController();

        $ajx->updateSession();

        break;



    //end Actions pour les session



    // Debut Actions pour les zone 

    case 'charger_data_zones':

        $ajx = new ActiviteController();

        $ajx->getListeZone();

        break;

    case 'change_statut_zones':

        $ajx = new ActiviteController();

        $ajx->changeStatutZone();

        break;

    case 'btn_showmodal_zone_add':

        $ajx = new ActiviteController();

        $ajx->modalAddZone();

        break;

    case 'btn_showmodal_zone_update':

        $ajx = new ActiviteController();

        $ajx->modalUpdatedZone();

        break;

    case 'btn_add_zone':

        $ajx = new ActiviteController();

        $ajx->addZone();

        break;

    case 'btn_update_zone':

        $ajx = new ActiviteController();

        $ajx->updateZone();

        break;



    //end Actions pour les Zones



    // Debut Actions pour les Categories pack 

    case 'charger_data_categorie_packs':

        $ajx = new ActiviteController();

        $ajx->getListeCategoriePack();

        break;

    case 'change_statut_categoriePacks':

        $ajx = new ActiviteController();

        $ajx->changeStatutCategoriePack();

        break;

    case 'btn_showmodal_categoriePack_add':

        $ajx = new ActiviteController();

        $ajx->modalAddCategoriePack();

        break;

    case 'btn_showmodal_categoriePack_update':

        $ajx = new ActiviteController();

        $ajx->modalUpdatedCategoriePack();

        break;

    case 'btn_add_categoriePack':

        $ajx = new ActiviteController();

        $ajx->addCategoriePack();

        break;

    case 'btn_update_categorie_pack':

        $ajx = new ActiviteController();

        $ajx->updateCategoriePack();

        break;



    //end Actions pour les Categories pack



      // Debut Actions pour les Articles 

    case 'charger_data_articles':

        $ajx = new ActiviteController();

        $ajx->getListeArticle();

        break;

    case 'change_statut_articles':

        $ajx = new ActiviteController();

        $ajx->changeStatutArticle();

        break;

    case 'btn_showmodal_article_add':

        $ajx = new ActiviteController();

        $ajx->modalAddArticle();

        break;

    case 'btn_showmodal_article_update':

        $ajx = new ActiviteController();

        $ajx->modalUpdatedArticle();

        break;

    case 'btn_add_article':

        $ajx = new ActiviteController();

        $ajx->addArticle();

        break;

    case 'btn_update_article':

        $ajx = new ActiviteController();

        $ajx->updateArticle();

        break;



    //end Actions pour les Articles



    // Debut Actions pour les packs 

    case 'charger_data_packs':

        $ajx = new ActiviteController();

        $ajx->getListePack();

        break;
    case 'get_nombre_jour_session_pack':

        $ajx = new ActiviteController();

        $ajx->getNombreJourSessionPack();

        break;

    case 'change_statut_packs':

        $ajx = new ActiviteController();

        $ajx->changeStatutPack();

        break;

    case 'btn_showmodal_pack_add':

        $ajx = new ActiviteController();

        $ajx->modalAddPack();

        break;

    case 'btn_showmodal_pack_update':

        $ajx = new ActiviteController();

        $ajx->modalUpdatedPack();

        break;

    case 'btn_add_pack':

        $ajx = new ActiviteController();

        $ajx->addPack();

        break;

    case 'btn_update_pack':

        $ajx = new ActiviteController();

        $ajx->updatePack();

        break;



    //end Actions pour les packs



    // Debut Actions pour les depenses 

    case 'charger_data_depenses':

        $ajx = new FinanceController();

        $ajx->GetListeDepense();

        break;

    case 'charger_data_depenses':

        $ajx = new FinanceController();

        $ajx->GetListeDepense();

        break;

    case 'change_statut_depenses':

        $ajx = new FinanceController();

        $ajx->changeStatutDepense();

        break;

    case 'btn_showmodal_depense_add':

        $ajx = new FinanceController();

        $ajx->modalAddDepense();

        break;

    case 'btn_showmodal_depense_update':

        $ajx = new FinanceController();

        $ajx->modalUpdatedDepense();

        break;

    case 'btn_add_depense':

        $ajx = new FinanceController();

        $ajx->addDepense();

        break;

    case 'btn_update_depense':

        $ajx = new FinanceController();

        $ajx->updateDepense();

        break;



    //end Actions pour les depense



    

    //end Actions pour les souscription



    // Debut Actions pour les categories par session

    case 'get_categories_by_session':

        $ajx = new ActiviteController();

        $ajx->getCategoriesBySession();

        break;

          // Debut Actions pour les packs par categorie

    case 'get_packs_by_categorie':

        $ajx = new ActiviteController();

        $ajx->getPacksByCategorie();

        break;

    //end Actions pour les categories par session



    // Debut Actions pour les clients 

    case 'charger_data_souscriptions':

        $ajx = new ClientController();

        $ajx->GetListeInscription();

        break;

    case 'btn_add_inscription':

        $ajx = new ClientController();

        $ajx->addInscription();

        break;

    case 'btn_add_resouscription':

        $ajx = new ClientController();

        $ajx->addResouscription();

        break;

    case 'search_client':

        $ajx = new ClientController();

        $ajx->searchClient();

        break;

    case 'charger_data_clients':

        $ajx = new ClientController();

        $ajx->GetListeClient();

        break;

    case 'btn_showmodal_client_update':

        $ajx = new ClientController();

        $ajx->modalUpdateClient();

        break;

    case 'btn_update_client':

        $ajx = new ClientController();

        $ajx->updateClient();

        break;

  



    //end Actions pour les clients



    // Debut Actions pour les cautions

    case 'charger_data_cautions':

        $ajx = new CautisationController();

        $ajx->GetListeCautions();

        break;

    case 'btn_add_cautisation':

        $ajx = new CautisationController();

        $ajx->addCautisation();

        break;

    case 'btn_validate_cautisation':

        $ajx = new CautisationController();

        $ajx->validateCautisation();

        break;

    case 'btn_showmodal_cautisation_add':

        $ajx = new CautisationController();

        $ajx->modalAddCautisation();

        break;

    case 'get_stats_cautions':

        $ajx = new CautisationController();

        $ajx->getStats();

        break;

    case 'search_client_cautisation':

        $ajx = new CautisationController();

        $ajx->searchClient();

        break;

    case 'get_souscriptions_client':

        $ajx = new CautisationController();

        $ajx->getInscriptionsClient();

        break;

    case 'btn_save_encaissement':

        $ajx = new CautisationController();

        $ajx->saveEncaissement();

        break;

    //end Actions pour les cautions



    // Debut Actions pour les versements commerciaux

    case 'charger_data_versements_commerciaux':

        $ajx = new VersementCommercialController();

        $ajx->GetListeVersements();

        break;

    case 'btn_add_versement_commercial':

        $ajx = new VersementCommercialController();

        $ajx->addVersement();

        break;

    case 'btn_validate_versement_commercial':

        $ajx = new VersementCommercialController();

        $ajx->validateVersement();

        break;

    case 'btn_showmodal_versement_commercial_add':

        $ajx = new VersementCommercialController();

        $ajx->modalAddVersement();

        break;

    case 'get_stats_versements':

        $ajx = new VersementCommercialController();

        $ajx->getStats();

        break;

    //end Actions pour les versements commerciaux



    // Debut Actions pour les validations

    case 'charger_data_validations':

        $ajx = new ValidationController();

        $ajx->GetListeValidations();

        break;

    //end Actions pour les validations



    // Debut Actions pour les distributions

    case 'charger_data_distributions':

        $ajx = new DistributionController();

        $ajx->getDistributionsByClient();

        break;

    //end Actions pour les distributions



    // Autres cas...

    default:

        echo json_encode(['status' => 'error', 'message' => 'Action inconnue']);

        break;

}

