<?php

namespace App\Controllers\Gestionnaires;

use App\Core\Auth;
use App\Core\MainController;
use App\Models\VersementCommercialModel;
use App\Services\VersementCommercialService;

class ValidationController extends MainController
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
        $this->view('gestionnaires/validations/liste', ['title' => "Validations"]);
    }

    public function GetListeValidations()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $likeParams = [];
        $whereParams = ['etablissement_code' => Auth::user('etablissement_code')];

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
            4 => 'created_at_versement'
        ];

        $orderBy = $columns[$orderColumn] ?? 'created_at_versement';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';

        if (!empty($search)) {
            $likeParams = [
                'nom_commercial' => $search,
                'libelle_zone' => $search,
                'reference_versement' => $search
            ];
        }

        $filters = ['statut' => 'en_attente'];
        if (!empty($search)) {
            $filters['search'] = $search;
        }

        $total = $this->versementModel->dataTableCountTotalVersementsRow($whereParams, $likeParams);
        $totalFiltered = $total;
        $versements = $this->versementModel->getPendingVersements(Auth::user('etablissement_code'), $filters);
        $data = $this->versementService->getVersementDataService($versements);

        echo json_encode([
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
        return;
    }
}
