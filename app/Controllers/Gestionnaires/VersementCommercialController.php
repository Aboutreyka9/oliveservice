<?php

namespace App\Controllers\Gestionnaires;

use App\Core\Auth;
use App\Core\MainController;
use App\Helpers\HttpStatusCode;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\VersementCommercialModel;
use App\Services\VersementCommercialService;
use TABLES;

class VersementCommercialController extends MainController
{
    private VersementCommercialModel $versementModel;
    private VersementCommercialService $versementService;

    public function __construct()
    {
        parent::__construct();
        $this->versementModel = new VersementCommercialModel();
        $this->versementService = new VersementCommercialService();
    }

    public function liste()
    {
        $this->view('gestionnaires/versements_commerciaux/liste', ['title' => "Versements commerciaux"]);
    }

    public function GetListeVersements()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $likeParams = [];
        $filters = ['etablissement_code' => Auth::user('etablissement_code')];

        $limit = (int) ($_POST['length'] ?? 10);
        $start = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');

        $columns = [
            0 => 'nom_commercial',
            1 => 'libelle_zone',
            2 => 'montant_versement',
            3 => 'periode_versement_debut',
            4 => 'statut_versement',
            5 => 'created_at_versement'
        ];

        $orderBy = $columns[$orderColumn] ?? 'created_at_versement';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';

        if (!empty($search)) {
            $likeParams = [
                'nom_commercial' => $search,
                'libelle_zone' => $search,
                'reference_versement' => $search,
                'statut_versement' => $search
            ];
        }

        if (!empty($_POST['commercial_code'])) {
            $filters['commercial_code'] = $_POST['commercial_code'];
        }
        if (!empty($_POST['zone_code'])) {
            $filters['zone_code'] = $_POST['zone_code'];
        }
        if (!empty($_POST['statut'])) {
            $filters['statut'] = $_POST['statut'];
        }
        if (!empty($_POST['date_debut']) && !empty($_POST['date_fin'])) {
            $filters['date_debut'] = $_POST['date_debut'];
            $filters['date_fin'] = $_POST['date_fin'];
        }

        $total = $this->versementModel->dataTableCountTotalVersementsRow($filters, $likeParams);
        $totalFiltered = $this->versementModel->dataTableCountTotalVersementsRow($filters, $likeParams);
        $versements = $this->versementModel->DataTableFetchVersementsListe($filters, $likeParams, $orderBy, $orderDir, $start, $limit);
        $data = $this->versementService->getVersementDataService($versements);

        echo json_encode([
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
        return;
    }

    public function getStats()
    {
        $_POST = sanitizePostData($_POST);
        
        $filters = [];
        if (!empty($_POST['commercial_code'])) {
            $filters['commercial_code'] = $_POST['commercial_code'];
        }
        if (!empty($_POST['zone_code'])) {
            $filters['zone_code'] = $_POST['zone_code'];
        }
        if (!empty($_POST['statut'])) {
            $filters['statut'] = $_POST['statut'];
        }
        if (!empty($_POST['date_debut']) && !empty($_POST['date_fin'])) {
            $filters['date_debut'] = $_POST['date_debut'];
            $filters['date_fin'] = $_POST['date_fin'];
        }

        $stats = $this->versementService->getStats(Auth::user('etablissement_code'), $filters);
        Response::success('', ['stats' => $stats]);
    }

    public function addVersement()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();
        $v->required('commercial_code', $commercial_code, 'Commercial')
          ->required('montant_versement', $montant_versement, 'Montant')
          ->digit('montant_versement', $montant_versement, 'Montant');

        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->versementService->saveVersementData($_POST);
        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function validateVersement()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $result = $this->versementService->validateVersement($code_versement, $statut_versement, $commentaire ?? null);
        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function modalAddVersement()
    {
        $commercials = $this->versementModel->getFieldsForParams(
            TABLES::USERS,
            ['etablissement_code' => Auth::user('etablissement_code'), 'statut_user' => 'actif'],
            [],
            true
        );

        if (empty($commercials)) Response::error('Aucun commercial disponible.');

        $zones = $this->versementModel->getFieldsForParams(
            TABLES::ZONES,
            ['etablissement_code' => Auth::user('etablissement_code'), 'statut_zone' => 'actif'],
            [],
            true
        );

        $output = $this->versementService->versementAddModalService($commercials, $zones);
        Response::success('', ['data' => $output]);
    }
}
