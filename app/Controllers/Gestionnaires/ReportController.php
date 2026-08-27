<?php

namespace App\Controllers\Gestionnaires;

use App\Core\Auth;
use App\Core\MainController;
use App\Models\ReportModel;
use App\Helpers\Response;

class ReportController extends MainController
{
    private ReportModel $reportModel;

    public function __construct()
    {
        parent::__construct();
        $this->reportModel = new ReportModel();
    }

    public function dashboard()
    {
        $stats = $this->reportModel->getDashboardStats();
        $souscriptionsByMonth = $this->reportModel->getSouscriptionsByMonth(Auth::user('annee_code'));
        $topPacks = $this->reportModel->getTopPacks(5);
        $cautionsByCommercial = $this->reportModel->getCautionsByCommercial();
        $versementsByCommercial = $this->reportModel->getVersementsByCommercial();
        $distributionsByPack = $this->reportModel->getDistributionsByPack();
        $depensesByType = $this->reportModel->getDepensesByType();
        $clientsByZone = $this->reportModel->getClientsByZone();

        $this->view('gestionnaires/reports/dashboard', [
            'title' => "Tableau de bord des rapports",
            'stats' => $stats,
            'souscriptionsByMonth' => $souscriptionsByMonth,
            'topPacks' => $topPacks,
            'cautionsByCommercial' => $cautionsByCommercial,
            'versementsByCommercial' => $versementsByCommercial,
            'distributionsByPack' => $distributionsByPack,
            'depensesByType' => $depensesByType,
            'clientsByZone' => $clientsByZone,
        ]);
    }

    public function souscriptions()
    {
        $this->view('gestionnaires/reports/souscriptions', ['title' => "Rapport des souscriptions"]);
    }

    public function cautions()
    {
        $this->view('gestionnaires/reports/cautions', ['title' => "Rapport des cautions"]);
    }

    public function versements()
    {
        $this->view('gestionnaires/reports/versements', ['title' => "Rapport des versements"]);
    }

    public function distributions()
    {
        $this->view('gestionnaires/reports/distributions', ['title' => "Rapport des distributions"]);
    }

    public function finances()
    {
        $this->view('gestionnaires/reports/finances', ['title' => "Rapport financier"]);
    }
}
