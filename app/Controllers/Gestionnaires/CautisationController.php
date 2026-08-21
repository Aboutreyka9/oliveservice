<?php

namespace App\Controllers\Gestionnaires;

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
        $filters = [
            'etablissement_code' => Auth::user('etablissement_code'),
            'statut' => $_POST['statut'] ?? 'En attente'
        ];

        $limit = (int) ($_POST['length'] ?? 10);
        $start = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');

        $columns = [
            0 => 'cl.nom_client',
            1 => 'cl.telephone_client',
            2 => 'se.libelle_session',
            3 => 'c.montant_cautisation_client',
            4 => 'c.created_at_cautisation_client'
        ];

        $orderBy = $columns[$orderColumn] ?? 'c.created_at_cautisation_client';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';

        if (!empty($search)) {
            $likeParams = [
                'cl.nom_client' => $search,
                'cl.telephone_client' => $search,
                'se.libelle_session' => $search,
                'c.code_cautisation_client' => $search
            ];
        }

        if (!empty($_POST['session_code'])) {
            $filters['session_code'] = $_POST['session_code'];
        }
        if (!empty($_POST['zone_code'])) {
            $filters['zone_code'] = $_POST['zone_code'];
        }
        if (!empty($_POST['date_debut']) && !empty($_POST['date_fin'])) {
            $filters['date_debut'] = $_POST['date_debut'];
            $filters['date_fin'] = $_POST['date_fin'];
        }

        $total = $this->cautisationModel->dataTableCountTotalCautionsRow($filters, $likeParams);
        $totalFiltered = $this->cautisationModel->dataTableCountTotalCautionsRow($filters, $likeParams);
        $cautions = $this->cautisationModel->DataTableFetchCautionsListe($filters, $likeParams, $orderBy, $orderDir, $start, $limit);
        $data = $this->cautisationService->getCautisationDataService($cautions);

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
        
        $etablissementCode = Auth::user('etablissement_code');
        $stats = $this->cautisationModel->getStatsCautions(
            $etablissementCode,
            $_POST['session_code'] ?? null,
            $_POST['zone_code'] ?? null,
            $_POST['date_debut'] ?? null,
            $_POST['date_fin'] ?? null
        );

        Response::success('', ['stats' => $stats]);
    }

    public function addCautisation()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();
        $v->required('inscription_code', $inscription_code, 'Souscription')
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
        $souscriptions = $this->cautisationModel->getFieldsForParams(
            TABLES::INSCRIPTIONS,
            ['etablissement_code' => Auth::user('etablissement_code')],
            [],
            true,
            ['created_at_inscription' => 'DESC']
        );

        if (empty($souscriptions)) Response::error('Aucune souscription disponible.');

        $output = $this->cautisationService->cautisationAddModalService($souscriptions);
        Response::success('', ['data' => $output]);
    }

    public function encaisser()
    {
        $this->view('cautions/encaisser', ['title' => "Encaisser caution"]);
    }

    public function searchClient()
    {
        $_POST = sanitizePostData($_POST);
        $search = trim($_POST['search'] ?? '');
        $etablissementCode = Auth::user('etablissement_code');

        if (empty($search)) {
            Response::error('Veuillez saisir un terme de recherche');
        }

        $clients = $this->cautisationModel->searchClients($search, $etablissementCode);
        Response::success('', ['clients' => $clients]);
    }

    public function getInscriptionsClient()
    {
        $_POST = sanitizePostData($_POST);
        $clientCode = $_POST['client_code'] ?? '';
        $etablissementCode = Auth::user('etablissement_code');

        if (empty($clientCode)) {
            Response::error('Code client requis');
        }

        $souscriptions = $this->cautisationModel->getInscriptionsActivesByClient($clientCode, $etablissementCode);
        Response::success('', ['souscriptions' => $souscriptions]);
    }

    public function saveEncaissement()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();
        $v->required('inscription_code', $inscription_code, 'Souscription')
          ->required('montant_cautisation', $montant_cautisation, 'Montant')
          ->digit('montant_cautisation', $montant_cautisation, 'Montant')
          ->required('mode_calcul', $mode_calcul, 'Mode de calcul');

        if ($mode_calcul === 'jours') {
            $v->required('nombre_jours_cautisation', $nombre_jours_cautisation, 'Nombre de jours')
              ->digit('nombre_jours_cautisation', $nombre_jours_cautisation, 'Nombre de jours');
        }

        if ($v->fails()) {
            Response::error(implode(', ', $v->errors()), HttpStatusCode::UNAUTHORIZED);
        }

        $result = $this->cautisationService->encaisserCautisation($_POST);
        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }
}
