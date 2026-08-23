<?php

namespace App\Services;

use App\Models\ReportModel;

class ReportService
{
    public ReportModel $reportModel;

    public function __construct()
    {
        $this->reportModel = new ReportModel();
    }

    public function getDashboardData(): array
    {
        return [
            'stats' => $this->reportModel->getDashboardStats(),
            'souscriptionsByMonth' => $this->reportModel->getInscriptionsByMonth(),
            'topPacks' => $this->reportModel->getTopPacks(5),
            'cautionsByCommercial' => $this->reportModel->getCautionsByCommercial(),
            'versementsByCommercial' => $this->reportModel->getVersementsByCommercial(),
            'distributionsByPack' => $this->reportModel->getDistributionsByPack(),
            'depensesByType' => $this->reportModel->getDepensesByType(),
            'clientsByZone' => $this->reportModel->getClientsByZone(),
        ];
    }
}
