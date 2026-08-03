<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\MainController;
use App\Helpers\HttpStatusCode;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\ActiviteModel;
use App\Models\SettingModel;
use App\Services\ActiviteService;
use TABLES;

class ActiviteController extends MainController
{

    // MODELS
    private ActiviteModel $activiteModel;
    private SettingModel $settingModel;

    //   SERVICES
    private ActiviteService $activiteService;

    public function __construct()
    {
        parent::__construct();
        //  MODELS
        $this->activiteModel = new ActiviteModel();
        $this->settingModel = new SettingModel();

        // SERVICES
        $this->activiteService = new ActiviteService();
    }

    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES RENDUS
     * SEXION POUR LES VUES 
     * **********************************************************************
     * --------------------------------------------------------------------------
     */



    public function zone()
    {
        $this->view('activites/zone', ['title' => "Zone"]);
    }


        public function pack()
    {
        $this->view('activites/pack', ['title' => "Pack"]);
    }

      public function categorie()
    {
        $this->view('activites/categorie', ['title' => "Categories packs"]);
    }

       public function article()
    {
        $this->view('activites/article', ['title' => "Articles"]);
    }



    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES REQUESTS AJAX
     * SEXION POUR LES AJAX REQUESTS
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    // SEXION ZONES


    public function GetListeZone()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $f = new ActiviteModel();

        $likeParams = [];
        $whereParams = ['etablissement_code' => Auth::user('etablissement_code'), 'annee_code' => Auth::user('annee_code')];


        $limit  = (int) ($_POST['length'] ?? 10);
        $start  = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir    = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');
        // $search = $_POST['search'] ?? '';
        $columns = [
            0 => 'libelle_type_depense',
            1 => 'periode_depense',
            2 => 'statut_depense',
            3 => 'montant_depense',
            4 => 'user_confirm',
            5 => 'created_at_confirm'
        ];
        // $columns = [
        //     0 => 'libelle_type_depense',

        // ];

        $orderBy = $columns[$orderColumn] ?? 'libelle_type_depense';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';



        // 🔎 Recherche
        if (!empty($search)) {


            $likeParams = ['libelle_type_depense' => $search, 'periode_depense' => $search, 'statut_depense' => $search, 'montant_depense' => $search, 'user_confirm' => $search, 'created_at_confirm' => $search];

            // $likeParams = ['libelle_type_depense' => $search];
        }

        // 🔢 Total
        $total = $f->dataTbleCountTotalDepensesRow($whereParams);
        // 🔢 Total filtré

        $totalFiltered = $f->dataTbleCountTotalDepensesRow($whereParams, $likeParams);
        // 📄 Données

        $depenseList = $f->DataTableFetchDepensesListe($likeParams, $orderBy, $orderDir, $start, $limit);
        $data = [];


        $data = $this->activiteService->depenseDataService($depenseList);
        // Response::success('operation reussie',);
        echo json_encode([
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => $total,
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
            // "data"            => $depenseList
        ]);
        // // echo json_encode(['data' => $total, 'code' => 200]);
        return;
    }

    public function modalAddZone()
    {


        $output = $this->activiteService->zoneAddModalService();
        Response::success('', ['data' => $output]);
    }

    public function modalUpdatedZone()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        // $users = getAllusers();
        $depense = $this->activiteModel->getSingleZoneByCode($codedepense);

        $typeDepenses = $this->activiteModel->getAllTypeZones(Auth::user('etablissement_code'));


        if (empty($depense) || empty($typeDepenses)) Response::error('Désolé, une erreur est survenue lors du traitement!');

        $output = $this->activiteService->zoneUpdateModalService($depense, $typeDepenses);
        echo json_encode(['data' => $output, 'code' => 200, 'message' => 'operation reussie', 'success' => true]);
    }

    public function addZone()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();

        $v->required('libelle_zone', $libelle_zone, 'Libelle zone');


        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->activiteService->saveZoneData($_POST);


        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function updateZone()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $v = new Validator();

        $v->required('libelle_depense', $libelle_depense, 'Libelle depense')
            ->required('date_depense', $date_depense, 'Date depense')
            ->required('montant_depense', $montant_depense, 'Montant depense')
            ->digit('montant_depense', $montant_depense, 'Montant depense');


        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->activiteService->updateZoneData($_POST);


        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function changeStatutZone()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $statut_zone = (isset($statut_zone) && $statut_zone != STATUT_INACTIF) ? STATUT_ACTIF : STATUT_INACTIF;


        if ($this->activiteModel->update(TABLES::ZONES, 'code_zone', $code_zone, ['statut_zone' => $statut_zone])) Response::success('Statut modifié avec succès', []);

        Response::error("Echec de l'opération", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    // END SEXION ZONE

    // SEXION CATEGORIES PACK


    public function GetListeCategoriePack()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $f = new ActiviteModel();

        // var_dump($_POST);return;
        $likeParams = [];
        $whereParams = ['etablissement_code' => Auth::user('etablissement_code')];


        $limit  = (int) ($_POST['length'] ?? 10);
        $start  = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir    = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');
        // $search = $_POST['search'] ?? '';
        $columns = [
            0 => 'libelle_categorie_pack',
            1 => 'statut_categorie_pack',
            2 => 'libelle_categorie_pack',
            3 => 'created_at_categorie_pack'
        ];
        // $columns = [
        //     0 => 'libelle_type_depense',

        // ];

        $orderBy = $columns[$orderColumn] ?? 'libelle_categorie_pack';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';



        // 🔎 Recherche
        if (!empty($search)) {


            $likeParams = ['libelle_categorie_pack' => $search, 'statut_categorie_pack' => $search, 'created_at_categorie_pack' => $search];

            // $likeParams = ['libelle_type_depense' => $search];
        }

        // 🔢 Total
        $total = $f->dataTbleCountTotalCategoriePacksRow($whereParams);
        // 🔢 Total filtré

        $totalFiltered = $f->dataTbleCountTotalCategoriePacksRow($whereParams, $likeParams);
        // 📄 Données

        $depenseList = $f->DataTableFetchCategoriePacksListe($likeParams, $orderBy, $orderDir, $start, $limit);
        $data = [];


        $data = $this->activiteService->categoriePackDataService($depenseList);
        // Response::success('operation reussie',);
        echo json_encode([
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => $total,
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
            // "data"            => $depenseList
        ]);
        // // echo json_encode(['data' => $total, 'code' => 200]);
        return;
    }

    public function modalAddCategoriePack()
    {


        $output = $this->activiteService->categoriePackAddModalService();
        Response::success('', ['data' => $output]);
    }

    public function modalUpdatedCategoriePack()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        // $users = getAllusers();
        $depense = $this->activiteModel->getSingleCategoriePackByCode($codedepense);

        $typeDepenses = $this->activiteModel->getAllTypeCategoriePacks(Auth::user('etablissement_code'));


        if (empty($depense) || empty($typeDepenses)) Response::error('Désolé, une erreur est survenue lors du traitement!');

        $output = $this->activiteService->zoneUpdateModalService($depense, $typeDepenses);
        echo json_encode(['data' => $output, 'code' => 200, 'message' => 'operation reussie', 'success' => true]);
    }

    public function addCategoriePack()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();

        $v->required('libelle_categorie_pack', $libelle_categorie_pack, 'Libelle Categorie');

        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->activiteService->saveCategoriePackData($_POST);

        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function updateCategoriePack()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $v = new Validator();

        $v->required('libelle_depense', $libelle_depense, 'Libelle depense')
            ->required('date_depense', $date_depense, 'Date depense')
            ->required('montant_depense', $montant_depense, 'Montant depense')
            ->digit('montant_depense', $montant_depense, 'Montant depense');


        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->activiteService->updateCategoriePackData($_POST);


        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function changeStatutCategoriePack()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $statut_zone = (isset($statut_zone) && $statut_zone != STATUT_INACTIF) ? STATUT_ACTIF : STATUT_INACTIF;


        if ($this->activiteModel->update(TABLES::ZONES, 'code_zone', $code_zone, ['statut_zone' => $statut_zone])) Response::success('Statut modifié avec succès', []);

        Response::error("Echec de l'opération", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    // END SEXION CATEGORIES PACK

    // SEXION ARTICLES 


    public function getListeArticle()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $f = new ActiviteModel();

        // var_dump($_POST);return;
        $likeParams = [];
        $whereParams = ['etablissement_code' => Auth::user('etablissement_code')];


        $limit  = (int) ($_POST['length'] ?? 10);
        $start  = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir    = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');
        // $search = $_POST['search'] ?? '';
        $columns = [
            0 => 'libelle_categorie_pack',
            1 => 'statut_categorie_pack',
            2 => 'libelle_categorie_pack',
            3 => 'created_at_categorie_pack'
        ];
        // $columns = [
        //     0 => 'libelle_type_depense',

        // ];

        $orderBy = $columns[$orderColumn] ?? 'libelle_categorie_pack';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';



        // 🔎 Recherche
        if (!empty($search)) {


            $likeParams = ['libelle_categorie_pack' => $search, 'statut_categorie_pack' => $search, 'created_at_categorie_pack' => $search];

            // $likeParams = ['libelle_type_depense' => $search];
        }

        // 🔢 Total
        $total = $f->dataTbleCountTotalArticlesRow($whereParams);
        // 🔢 Total filtré

        $totalFiltered = $f->dataTbleCountTotalArticlesRow($whereParams, $likeParams);
        // 📄 Données

        $depenseList = $f->DataTableFetchArticlesListe($likeParams, $orderBy, $orderDir, $start, $limit);
        $data = [];


        $data = $this->activiteService->categoriePackDataService($depenseList);
        // Response::success('operation reussie',);
        echo json_encode([
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => $total,
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
            // "data"            => $depenseList
        ]);
        // // echo json_encode(['data' => $total, 'code' => 200]);
        return;
    }

    public function modalAddArticle()
    {


        $output = $this->activiteService->articleAddModalService();
        Response::success('', ['data' => $output]);
    }

    public function modalUpdatedArticle()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        // $users = getAllusers();
        $depense = $this->activiteModel->getSingleArticleByCode($codedepense);

        $typeDepenses = $this->activiteModel->getAllTypeArticles(Auth::user('etablissement_code'));


        if (empty($depense) || empty($typeDepenses)) Response::error('Désolé, une erreur est survenue lors du traitement!');

        $output = $this->activiteService->zoneUpdateModalService($depense, $typeDepenses);
        echo json_encode(['data' => $output, 'code' => 200, 'message' => 'operation reussie', 'success' => true]);
    }

    public function addArticle()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();

        $v->required('libelle_article', $libelle_article, 'Libelle article');

        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->activiteService->saveArticleData($_POST);

        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function updateArticle()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $v = new Validator();

        $v->required('libelle_depense', $libelle_depense, 'Libelle depense')
            ->required('date_depense', $date_depense, 'Date depense')
            ->required('montant_depense', $montant_depense, 'Montant depense')
            ->digit('montant_depense', $montant_depense, 'Montant depense');


        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->activiteService->updateArticleData($_POST);


        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function changeStatutArticle()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $statut_zone = (isset($statut_zone) && $statut_zone != STATUT_INACTIF) ? STATUT_ACTIF : STATUT_INACTIF;


        if ($this->activiteModel->update(TABLES::ZONES, 'code_zone', $code_zone, ['statut_zone' => $statut_zone])) Response::success('Statut modifié avec succès', []);

        Response::error("Echec de l'opération", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    // END SEXION ARTICLES 

    // SEXION PACK


    public function GetListePack()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $f = new ActiviteModel();

        $likeParams = [];
        $whereParams = ['etablissement_code' => Auth::user('etablissement_code'), 'annee_code' => Auth::user('annee_code')];


        $limit  = (int) ($_POST['length'] ?? 10);
        $start  = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir    = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');
        // $search = $_POST['search'] ?? '';
        $columns = [
            0 => 'libelle_type_depense',
            1 => 'periode_depense',
            2 => 'statut_depense',
            3 => 'montant_depense',
            4 => 'user_confirm',
            5 => 'created_at_confirm'
        ];
        // $columns = [
        //     0 => 'libelle_type_depense',

        // ];

        $orderBy = $columns[$orderColumn] ?? 'libelle_type_depense';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';



        // 🔎 Recherche
        if (!empty($search)) {


            $likeParams = ['libelle_type_depense' => $search, 'periode_depense' => $search, 'statut_depense' => $search, 'montant_depense' => $search, 'user_confirm' => $search, 'created_at_confirm' => $search];

            // $likeParams = ['libelle_type_depense' => $search];
        }

        // 🔢 Total
        $total = $f->dataTbleCountTotalPacksRow($whereParams);
        // 🔢 Total filtré

        $totalFiltered = $f->dataTbleCountTotalPacksRow($whereParams, $likeParams);
        // 📄 Données

        $depenseList = $f->DataTableFetchPacksListe($likeParams, $orderBy, $orderDir, $start, $limit);
        $data = [];


        $data = $this->activiteService->packDataService($depenseList);
        // Response::success('operation reussie',);
        echo json_encode([
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => $total,
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
            // "data"            => $depenseList
        ]);
        // // echo json_encode(['data' => $total, 'code' => 200]);
        return;
    }

    public function modalAddPack()
    {


        $categories = $this->activiteModel->getAllCategoriePacks(Auth::user('etablissement_code'));
        if (empty($categories)) Response::error('Désolé, aucune categorie enregistrée!');

        $output = $this->activiteService->packAddModalService($categories);
        Response::success('', ['data' => $output]);
    }

    public function modalUpdatedPack()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        // $users = getAllusers();
        $depense = $this->activiteModel->getSinglePackByCode($codedepense);

        $typeDepenses = $this->activiteModel->getAllTypePacks(Auth::user('etablissement_code'));


        if (empty($depense) || empty($typeDepenses)) Response::error('Désolé, une erreur est survenue lors du traitement!');

        $output = $this->activiteService->packUpdateModalService($depense, $typeDepenses);
        echo json_encode(['data' => $output, 'code' => 200, 'message' => 'operation reussie', 'success' => true]);
    }

    public function addPack()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();

        $v->required('libelle_zone', $libelle_zone, 'Libelle zone');


        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->activiteService->saveZoneData($_POST);


        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function updatePack()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $v = new Validator();

        $v->required('libelle_depense', $libelle_depense, 'Libelle depense')
            ->required('date_depense', $date_depense, 'Date depense')
            ->required('montant_depense', $montant_depense, 'Montant depense')
            ->digit('montant_depense', $montant_depense, 'Montant depense');


        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->activiteService->updatePackData($_POST);


        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function changeStatutPack()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

            $statut_zone = (isset($statut_zone) && $statut_zone != STATUT_INACTIF) ? STATUT_ACTIF : STATUT_INACTIF;


        if ($this->activiteModel->update(TABLES::PACKS, 'code_pack', $code_pack, ['statut_pack' => $statut_zone])) Response::success('Statut modifié avec succès', []);

        Response::error("Echec de l'opération", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }


}
