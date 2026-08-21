<?php

namespace App\Controller\Gestionnaires;

use App\Core\Auth;
use App\Core\MainController;
use App\Services\CommercialService;

class CommercialController extends MainController
{
    private CommercialService $commercialService;

    public function __construct()
    {
        parent::__construct();
        $this->commercialService = new CommercialService();
    }

    public function profile($code)
    {
        $userCode = $code ?? Auth::user('id');
        $profileData = [];
        $profileData = $this->commercialService->getProfileData($userCode);

        if (empty($profileData)) {
            $this->view('commerciaux/profile', [
                'title' => "Profil commercial",
                'commercial' => [],
                'stats' => [],
                'performance' => [],
                'clients' => [],
                'versements' => [],
            ]);
            return;
        }

        $this->view('commerciaux/profile', array_merge([
            'title' => "Profil commercial",
        ], $profileData));
    }
}
