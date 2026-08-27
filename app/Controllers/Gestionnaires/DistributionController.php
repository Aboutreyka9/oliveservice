<?php

namespace App\Controllers\Gestionnaires;

use App\Core\Auth;
use App\Core\MainController;
use App\Helpers\HttpStatusCode;
use App\Helpers\Response;
use App\Models\ClientModel;
use App\Services\ClientService;
use TABLES;

class DistributionController extends MainController
{
    private ClientModel $clientModel;
    private ClientService $clientService;

    public function __construct()
    {
        parent::__construct();
        $this->clientModel = new ClientModel();
        $this->clientService = new ClientService();
    }

    public function liste()
    {
        $this->view('gestionnaires/distributions/liste', ['title' => "Distributions"]);
    }

    public function getDistributionsByClient()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $clientCode = $_POST['client_code'] ?? '';
        if (empty($clientCode)) {
            Response::error('Code client requis', HttpStatusCode::UNAUTHORIZED);
        }

        $etablissementCode = Auth::user('etablissement_code');
        $distributions = $this->clientModel->getDistributionsByClientCode($clientCode, $etablissementCode);

        echo json_encode([
            'success' => true,
            'data' => $distributions
        ]);
    }
}
