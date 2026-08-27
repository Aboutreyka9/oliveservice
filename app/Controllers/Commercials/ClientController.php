<?php

namespace App\Controllers\Commercials;

use App\Core\Auth;
use App\Core\MainController;
use App\Helpers\HttpStatusCode;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\ClientModel;
use App\Services\ClientService;
use DateTime;
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



    public function resouscription()
    {
        $this->view('commercials/clients/resouscription', ['title' => "Resouscription client"]);
    }

    public function souscription()
    {
       

        $this->view('commercials/clients/souscription', ['title' => "Souscription"]);
    }   

    public function liste()
    {
        $this->view('commercials/clients/liste', ['title' => "Liste des clients"]);
    }

    public function profile($code = null)
    {
        $data = [
            'title' => "Profil client",
            'client' => [],
            'souscriptions' => [],
            'pack_souscriptions' => [],
            'distributions' => [],
            'cautisations' => [],
        ];

        if ($code) {
            $profileData = $this->clientService->getProfileData($code);
            if (!empty($profileData)) {
                $data = array_merge($data, $profileData);
            }
        }

        $this->view('commercials/clients/profile', $data);
    }

    public function inscriptionDetail($code = null)
    {
        $data = [
            'title' => "Détails de l'inscription",
            'inscription' => [],
            'packs' => [],
            'cautions' => [],
            'distributions' => [],
        ];

        if ($code) {
            $detailData = $this->clientService->getInscriptionDetailData($code);
            if (!empty($detailData)) {
                $data = array_merge($data, $detailData);
            }
        }

        $this->view('commercials/clients/souscription_detail', $data);
    }

    public function listeInscription()
    {
        $this->view('commercials/clients/liste_souscription', ['title' => "Liste des souscriptions"]);
    }


    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES REQUESTS AJAX
     * SEXION POUR LES AJAX REQUESTS
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    // SEXION Souscription

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

    public function addResouscription()
    {
        $packs = json_decode($_POST['selected_packs'], true);
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();
        $v->required('client_code', $client_code, 'Client')
          ->required('session_code', $session_code, 'Session');

        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        if (empty($packs)) Response::error('Aucun pack selectionné', HttpStatusCode::UNAUTHORIZED);

        $result = $this->clientService->saveResouscriptionData($_POST, $packs);

        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function searchClient()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $client = $this->clientModel->searchClient($search_value);

        if (empty($client)) {
            Response::error('Client non trouvé', HttpStatusCode::NOT_FOUND);
        }

        Response::success('Client trouvé', ['client' => $client]);
    }

    public function GetListeInscription()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $f = new ClientModel();

        $likeParams = [];
        $whereParams = ['etablissement_code' => Auth::user('etablissement_code'), 'annee_code' => Auth::user('annee_code')];

        if (!empty($zone_code)) {
            $whereParams['zone_code'] = $zone_code;
        } else {
            $whereParams['zone_code'] = Auth::user('zone_code');
        }

        $limit  = (int) ($_POST['length'] ?? 10);
        $start  = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir    = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');

        $columns = [
            0 => 'ins.code_inscription',
            1 => 'cl.nom_client',
            2 => 'cl.telephone_client',
            3 => 'se.libelle_session',
            4 => 'an.libelle_annee',
            5 => 'zo.libelle_zone',
            6 => 'p.montant_pack',
            7 => 'montant_paye',
            8 => 'reste_du',
            9 => 'ins.statut_inscription',
            10 => 'ins.created_at_inscription'
        ];

        $orderBy = $columns[$orderColumn] ?? 'ins.created_at_inscription';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';

        if (!empty($search)) {
            $likeParams = [
                'ins.code_inscription' => $search,
                'cl.nom_client' => $search,
                'cl.telephone_client' => $search,
                'se.libelle_session' => $search
            ];
        }

        $total = $this->clientModel->dataTableCountTotalInscriptionRow($whereParams, $likeParams);
        $totalFiltered = $this->clientModel->dataTableCountTotalInscriptionRow($whereParams, $likeParams);
        $inscriptionList = $this->clientModel->DataTableFetchInscriptionListe($likeParams, $orderBy, $orderDir, $start, $limit);
        $data = $this->clientService->inscriptionDataService($inscriptionList);

        echo json_encode([
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
        return;
    }

      public function get_liste_souscription_commercial_for_session()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $listeSoucriptions = $this->clientModel->getListeSouscriptionForComercial(Auth::user('etablissement_code'), Auth::user('annee_code'), Auth::user('zone_code'),Auth::user('id'),$session_code);
        $stats = $this->clientModel->getStatsSouscriptionsForCommercial(Auth::user('etablissement_code'), Auth::user('annee_code'), Auth::user('zone_code'),Auth::user('id'),$session_code);

        $data_stats = $this->clientService->StatsSouscriptionCommercial($stats);
        $output = $this->clientService->inscriptionListeForCommercial($listeSoucriptions);

        echo json_encode(['success' => true,'data' => $output,"stats" => $data_stats]);
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
