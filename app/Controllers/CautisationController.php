<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\MainController;
use App\Helpers\HttpStatusCode;
use App\Helpers\Response;
use App\Models\CautisationModel;
use App\Services\CautisationService;
use TABLES;

class CautisationController extends MainController
{
    private CautisationModel $cautisationModel;
    private CautisationService $cautisationService;

    public function __construct()
    {
        parent::__construct();
        $this->cautisationModel = new CautisationModel();
        $this->cautisationService = new CautisationService();
    }

    public function liste()
    {
        $this->view('cautions/liste', ['title' => "Cautions clients"]);
    }

    public function GetListeCautions()
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
            0 => 'nom_client',
            1 => 'telephone_client',
            2 => 'libelle_session',
            3 => 'montant_cautisation_client',
            4 => 'created_at_cautisation_client'
        ];

        $orderBy = $columns[$orderColumn] ?? 'created_at_cautisation_client';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';

        if (!empty($search)) {
            $likeParams = [
                'nom_client' => $search,
                'telephone_client' => $search,
                'libelle_session' => $search,
                'code_cautisation_client' => $search
            ];
        }

        $total = $this->cautisationModel->dataTableCountTotalCautionsRow($whereParams, $likeParams);
        $totalFiltered = $this->cautisationModel->dataTableCountTotalCautionsRow($whereParams, $likeParams);
        $cautions = $this->cautisationModel->DataTableFetchCautionsListe($likeParams, $orderBy, $orderDir, $start, $limit);
        $data = $this->cautisationService->getCautisationDataService($cautions);

        echo json_encode([
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
        return;
    }

    public function addCautisation()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();
        $v->required('inscription_code', $inscription_code, 'Inscription')
          ->required('montant_cautisation', $montant_cautisation, 'Montant')
          ->digit('montant_cautisation', $montant_cautisation, 'Montant');

        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->cautisationService->saveCautisationData($_POST);
        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function validateCautisation()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $result = $this->cautisationService->validateCautisation($code_cautisation, $statut_cautisation);
        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function modalAddCautisation()
    {
        $inscriptions = $this->cautisationModel->getFieldsForParams(
            TABLES::INSCRIPTIONS,
            ['etablissement_code' => Auth::user('etablissement_code')],
            [],
            true,
            ['created_at_inscription' => 'DESC']
        );

        if (empty($inscriptions)) Response::error('Aucune inscription disponible.');

        $output = $this->cautisationService->cautisationAddModalService($inscriptions);
        Response::success('', ['data' => $output]);
    }
}
