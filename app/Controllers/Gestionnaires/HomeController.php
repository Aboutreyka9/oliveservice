<?php

namespace App\Controllers\Gestionnaires;

use App\Core\Auth;
use App\Core\Gqr;
use App\Core\MainController;
use App\Models\DashboardModel;
use App\Models\Factory;
use App\Services\Service;
use Roles;

class HomeController extends MainController
{

    private DashboardModel $dashboardModel;

    public function __construct()
    {
        parent::__construct();
        $this->dashboardModel = new DashboardModel();
    }

    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES RENDUS
     * SEXION POUR LES VUES 
     * **********************************************************************
     * --------------------------------------------------------------------------
     */

    public function acueil()
    {

        $etablissementCode = Auth::user('etablissement_code');
        $totals = $this->dashboardModel->getTotals($etablissementCode);
        $activities = $this->dashboardModel->getLastActivities($etablissementCode);
        $alerts = $this->dashboardModel->getAlerts($etablissementCode);

        return $this->view('gestionnaires/dashboard/dashboard', [
            'title' => "Mon espace",
            'totals' => $totals,
            'activities' => $activities,
            'alerts' => $alerts,
        ]);
    }

    public function googleAuth()
    {

        var_dump($_GET);
        $result = "";

        // return $this->view('welcome', ['title' => "Mon espace"]);
    }
}
