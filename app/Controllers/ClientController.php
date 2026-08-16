<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Factory;
use App\Core\MainController;
use App\Helpers\HttpStatusCode;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\ClientModel;
use App\Services\ClientService;
use TABLES;

class ClientController extends MainController
{

    // MODELS
    private ClientModel $clientModel;

    //   SERVICES
    private ClientService $clientService;

    public function __construct()
    {
        parent::__construct();
        //  MODELS
        $this->clientModel = new ClientModel();

        // SERVICES
        $this->clientService = new ClientService();
    }

    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES RENDUS
     * SEXION POUR LES VUES 
     * **********************************************************************
     * --------------------------------------------------------------------------
     */



    public function inscription()
    {
        $this->view('clients/inscription', ['title' => "Inscription"]);
    }

    public function liste()
    {
        $this->view('clients/liste', ['title' => "Liste des clients"]);
    }

    public function profile($code = null)
    {
        $client = [];
        if ($code) {
            $client = $this->clientModel->getClientByCode($code);
        }
        $this->view('clients/profile', ['title' => "Profil client", 'client' => $client]);
    }

    public function commande()
    {
        $this->view('clients/commande', ['title' => "Commandes clients"]);
    }

    public function listeInscription()
    {
        $this->view('clients/liste_inscription', ['title' => "Liste des inscriptions"]);
    }


    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES REQUESTS AJAX
     * SEXION POUR LES AJAX REQUESTS
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    // SEXION Inscription

    public function addInscription()
    {
        $packs = json_decode($_POST['selected_packs'], true);
        $_POST = sanitizePostData($_POST);
        extract($_POST);


        $v = new Validator();
        $v->required('nom_client', $nom_client, 'Nom client')
          ->required('telephone_client', $telephone_client, 'Contact')
          ->required('genre_client', $genre_client, 'Genre')
          ->required('lieu_client', $lieu_client, 'Lieu de residence')
          ->required('code_client', $code_client, 'Code client')
          ->required('session_code', $session_code, 'Libelle session');

        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        if (empty($packs)) Response::error('Aucun pack selectionné', HttpStatusCode::UNAUTHORIZED);

        // $packs = [];
        // if (!empty($selected_packs)) {
        //     $packs = json_decode($selected_packs, true);
        // }

        $result = $this->clientService->saveInscriptionData($_POST, $packs);



        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function GetListeInscription()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $f = new ClientModel();

        $likeParams = [];
        $whereParams = ['etablissement_code' => Auth::user('etablissement_code'), 'annee_code' => Auth::user('annee_code'),'zone_code' => Auth::user('zone_code')];


        $limit  = (int) ($_POST['length'] ?? 10);
        $start  = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir    = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');
        // $search = $_POST['search'] ?? '';
        $columns = [
            0 => 'code_client',
            1 => 'code_client',
            2 => 'code_client',
            3 => 'code_client',
            4 => 'code_client',
            5 => 'code_client'
        ];
        // $columns = [
        //     0 => 'libelle_type_depense',

        // ];

        $orderBy = $columns[$orderColumn] ?? 'code_client';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';



        // 🔎 Recherche
        if (!empty($search)) {


            $likeParams = ['code_client' => $search];
            // $likeParams = ['libelle_type_depense' => $search, 'periode_depense' => $search, 'statut_depense' => $search, 'montant_depense' => $search, 'user_confirm' => $search, 'created_at_confirm' => $search];

            // $likeParams = ['libelle_type_depense' => $search];
        }

        // 🔢 Total
        $total = $this->clientModel->dataTableCountTotalInscriptionRow($whereParams);
        // 🔢 Total filtré

        $totalFiltered = $this->clientModel->dataTableCountTotalInscriptionRow($whereParams, $likeParams);
        // 📄 Données

        $inscriptionList = $this->clientModel->DataTableFetchInscriptionListe($likeParams, $orderBy, $orderDir, $start, $limit);
        $data = [];


        $data = $this->clientService->inscriptionDataService($inscriptionList);
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

    // SEXION CLIENT

       public function GetListeClient()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $f = new ClientModel();

        $likeParams = [];
        $whereParams = ['etablissement_code' => Auth::user('etablissement_code')];

        $limit = (int) ($_POST['length'] ?? 10);
        $start = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');

        $columns = [
            0 => 'nom_client',
            1 => 'telephone_client',
            2 => 'genre_client',
            3 => 'lieu_client',
            4 => 'code_client',
            5 => 'created_at_client'
        ];

        $orderBy = $columns[$orderColumn] ?? 'nom_client';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';

        if (!empty($search)) {
            $likeParams = [
                'nom_client' => $search,
                'telephone_client' => $search,
                'sexe_client' => $search,
                'lieu_residence_client' => $search,
                'code_client' => $search
            ];
        }

        $total = $this->clientModel->dataTableCountTotalClientsRow($whereParams);
        $totalFiltered = $this->clientModel->dataTableCountTotalClientsRow($whereParams, $likeParams);
        $clientList = $this->clientModel->DataTableFetchClientsListe($likeParams, $orderBy, $orderDir, $start, $limit);
        $data = $this->clientService->clientDataService($clientList);

        echo json_encode([
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
        return;
    }

      public function modalUpdateClient()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        // $users = getAllusers();
        $client = $this->clientModel->getSingleClientByCode($codeclient);

        if (empty($client)) Response::error('Désolé, une erreur est survenue lors du traitement!');
   

        $output = $this->clientService->ClienteUpdateModalService($client);
        echo json_encode(['data' => $output, 'code' => 200, 'message' => 'operation reussie', 'success' => true]);
    }


    public function updateClient()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $v = new Validator();

        $v->required('nom_client', $nom_client, 'Nom client')
            ->required('telephone_client', $telephone_client, 'Telephone client')
            ->required('genre_client', $genre_client, 'Genre client')
            ->required('lieu_client', $lieu_client, 'Lieu de residence client');


        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->clientService->updateClientData($_POST);


        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

}
