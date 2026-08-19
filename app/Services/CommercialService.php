<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\CommercialModel;
use TABLES;

class CommercialService
{
    public CommercialModel $commercialModel;

    public function __construct()
    {
        $this->commercialModel = new CommercialModel();
    }

    public function getProfileData(string $userCode): array
    {
        $etablissementCode = Auth::user('etablissement_code');
        $commercial = $this->commercialModel->getCommercialByUserCode($userCode, $etablissementCode);

        if (empty($commercial)) {
            return [];
        }

        $stats = $this->commercialModel->getStatsCommercial($userCode, $etablissementCode);
        $performance = $this->commercialModel->getPerformanceCommercial($userCode, $etablissementCode);
        $clients = $this->commercialModel->getClientsByCommercial($userCode, $etablissementCode);
        $versements = $this->commercialModel->getVersementsByCommercial($userCode, $etablissementCode);

        return [
            'commercial' => $commercial,
            'stats' => $stats,
            'performance' => $performance,
            'clients' => $clients,
            'versements' => $versements,
        ];
    }
}
