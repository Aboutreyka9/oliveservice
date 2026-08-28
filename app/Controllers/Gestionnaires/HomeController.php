<?php

namespace App\Controllers\Gestionnaires;

use App\Core\Auth;
use App\Core\Gqr;
use App\Core\MainController;
use App\Models\DashboardModel;
use App\Models\CautisationModel;
use App\Models\UserModel;
use App\Services\Service;
use Groupes;
use Roles;

class HomeController extends MainController
{

    private DashboardModel $dashboardModel;
    private UserModel $userModel;
    private CautisationModel $cautisationModel;

    public function __construct()
    {
        parent::__construct();
        $this->dashboardModel = new DashboardModel();
        $this->userModel = new UserModel();
        $this->cautisationModel = new CautisationModel();
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

        if(Auth::hasGroupe(Groupes::COMMERCIAL)){

        $userCode = Auth::user('id');
        $etablissementCode = Auth::user('etablissement_code');
        $annee_code = Auth::user('annee_code');


        $stats = $this->userModel->getStatsCommercial($userCode, $etablissementCode);
        $performance = $this->userModel->getPerformanceCommercial($userCode, $etablissementCode);

        $clients = $this->userModel->getClientsByCommercial($userCode, $etablissementCode, [
            'date_debut' => date('Y-m-d', strtotime('-30 days')),
            'date_fin' => date('Y-m-d')
        ]);

        $versements = $this->userModel->getVersementsByCommercial($userCode, $etablissementCode, [
            'date_debut' => date('Y-m-d', strtotime('-30 days')),
            'date_fin' => date('Y-m-d')
        ]);

        $souscriptions = $this->cautisationModel->getSouscriptionsActivesByClient(
            $clients[0]['code_client'] ?? '',
            $etablissementCode
        );

        $totalClients = count($clients);
        $totalSouscriptions = $stats['total_insscriptions'] ?? 0;
        $totalPacks = $stats['total_packs'] ?? 0;
        $montantPacks = $stats['montant_total_packs'] ?? 0;
        $versementsValides = $stats['montant_versements_valides'] ?? 0;
        $versementsEnAttente = $stats['montant_versements_en_attente'] ?? 0;
        $cautionsValidees = $stats['montant_cautions_valides'] ?? 0;
        $tauxValidationVersements = $performance['taux_validation_versements'] ?? 0;
        $tauxValidationCautions = $performance['taux_validation_cautions'] ?? 0;

         return $this->view('commercials/dashboard/dashboard', compact(
            'versements',
            'souscriptions',
            'totalClients',
            'totalSouscriptions',
            'totalPacks',
            'montantPacks',
            'versementsValides',
            'versementsEnAttente',
            'cautionsValidees',
            'tauxValidationVersements',
            'tauxValidationCautions',
        ));
        }



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

    public function test(){
         return $this->view('test/show', []);
    }
}
