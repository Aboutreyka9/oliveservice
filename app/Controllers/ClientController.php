<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Factory;
use App\Core\MainController;
use App\Helpers\HttpStatusCode;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\ClientModel;
use App\Services\ClientService;
use TABLES;

class ClientController extends MainController
{

    // MODELS
    private ClientModel $clientModel;

    //   SERVICES
    private ClientService $clientService;

    public function __construct()
    {
        parent::__construct();
        //  MODELS
        $this->clientModel = new ClientModel();

        // SERVICES
        $this->clientService = new ClientService();
    }

    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES RENDUS
     * SEXION POUR LES VUES 
     * **********************************************************************
     * --------------------------------------------------------------------------
     */



    public function inscription()
    {
        $this->view('clients/inscription', ['title' => "Inscription"]);
    }




    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES REQUESTS AJAX
     * SEXION POUR LES AJAX REQUESTS
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    // SEXION depense


    public function GetListeInscription()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $f = new ClientModel();

        $likeParams = [];
        $whereParams = ['etablissement_code' => Auth::user('etablissement_code'), 'annee_code' => Auth::user('annee_code')];


        $limit  = (int) ($_POST['length'] ?? 10);
        $start  = (int) ($_POST['start'] ?? 0);
        $orderColumn = (int) ($_POST['order'][0]['column'] ?? 0);
        $orderDir    = strtolower($_POST['order'][0]['dir'] ?? 'desc');
        $search = trim($_POST['search']['value'] ?? '');
        // $search = $_POST['search'] ?? '';
        $columns = [
            0 => 'libelle_type_depense',
            1 => 'periode_depense',
            2 => 'statut_depense',
            3 => 'montant_depense',
            4 => 'user_confirm',
            5 => 'created_at_confirm'
        ];
        // $columns = [
        //     0 => 'libelle_type_depense',

        // ];

        $orderBy = $columns[$orderColumn] ?? 'libelle_type_depense';
        $orderDir = $orderDir === 'desc' ? 'DESC' : 'ASC';



        // 🔎 Recherche
        if (!empty($search)) {


            $likeParams = ['libelle_type_depense' => $search, 'periode_depense' => $search, 'statut_depense' => $search, 'montant_depense' => $search, 'user_confirm' => $search, 'created_at_confirm' => $search];

            // $likeParams = ['libelle_type_depense' => $search];
        }

        // 🔢 Total
        $total = $f->dataTbleCountTotalDepensesRow($whereParams);
        // 🔢 Total filtré

        $totalFiltered = $f->dataTbleCountTotalDepensesRow($whereParams, $likeParams);
        // 📄 Données

        $depenseList = $f->DataTableFetchDepensesListe($likeParams, $orderBy, $orderDir, $start, $limit);
        $data = [];


        $data = $this->clientService->depenseDataService($depenseList);
        // Response::success('operation reussie',);
        echo json_encode([
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => $total,
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
            // "data"            => $depenseList
        ]);
        // // echo json_encode(['data' => $total, 'code' => 200]);
        return;
    }

    public function modalAddInscription()
    {
        // $typeDepenses = $this->clientModel->getFieldsForParams(TABLES::);
        // if (empty($typeDepenses)) Response::error('Désolé, aucune année enregistrée!');

        // $output = $this->clientService->depenseAddModalService($typeDepenses);
        $output = $this->clientService->inscriptionAddModalService();
        Response::success('', ['data' => $output]);
    }

    public function modalUpdatedDepense()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);

        // $users = getAllusers();
        $depense = $this->clientModel->getSingledepenseByCode($codedepense);

        $typeDepenses = $this->clientModel->getAllTypeDepenses(Auth::user('etablissement_code'));


        if (empty($depense) || empty($typeDepenses)) Response::error('Désolé, une erreur est survenue lors du traitement!');

        $output = $this->clientService->depenseUpdateModalService($depense, $typeDepenses);
        echo json_encode(['data' => $output, 'code' => 200, 'message' => 'operation reussie', 'success' => true]);
    }

    public function addDepense()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

        $v = new Validator();

        $v->required('libelle_depense', $libelle_depense, 'Libelle depense')
            ->required('date_depense', $date_depense, 'Date depense')
            ->required('montant_depense', $montant_depense, 'Montant depense')
            ->digit('montant_depense', $montant_depense, 'Montant depense');


        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->clientService->saveDepenseData($_POST);


        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function updateDepense()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $v = new Validator();

        $v->required('libelle_depense', $libelle_depense, 'Libelle depense')
            ->required('date_depense', $date_depense, 'Date depense')
            ->required('montant_depense', $montant_depense, 'Montant depense')
            ->digit('montant_depense', $montant_depense, 'Montant depense');


        if ($v->fails()) Response::error($v->errors(), HttpStatusCode::UNAUTHORIZED);

        $result = $this->clientService->updateDepenseData($_POST);


        if (!$result['success']) {
            Response::error($result['message'], HttpStatusCode::UNAUTHORIZED);
        }

        Response::success($result['message'], []);
    }

    public function changeStatutDepense()
    {

        $_POST = sanitizePostData($_POST);
        extract($_POST);

        // $statut_user = (isset($statut_utilisateur) && $statut_utilisateur != STATUT_INACTIF) ? STATUT_ACTIF : STATUT_INACTIF;


        if ($this->clientModel->update(TABLES::DEPENSES, 'code_depense', $code_depense, ['statut_depense' => $statut_depense])) Response::success('Statut modifié avec succès', []);

        Response::error("Echec de l'opération", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    public static function getDataDateRangeFilterDepense()
    {
        if (isset($_POST['btn_filter_depense'])) {
            extract($_POST);
            $dateDebut = $_POST['dateDebut'] ?? null;
            $dateFin = $_POST['dateFin'] ?? null;

            $depense_annule = Soutra::getTotalDepenseAny($dateDebut, $dateFin, STATUT_DEPENSE[2]); // méthode adaptée que l'on a créée
            $depense_en_attente = Soutra::getTotalDepenseAny($dateDebut, $dateFin, STATUT_DEPENSE[0]); // méthode adaptée que l'on a créée
            $depense_approuve = Soutra::getTotalDepenseAny($dateDebut, $dateFin, STATUT_DEPENSE[1]); // 


            echo json_encode(compact('depense_annule', 'depense_en_attente', 'depense_approuve'));
        }
    }

    public static function validation_depense()
    {
        if (isset($_POST['btn_action']) && $_POST['btn_action'] == "btn_validation_depense") {
            extract($_POST);
            $msg = [];

            if (empty($id)) {
                $msg = ["success" => false, "msg" => "ID de la dépense manquant"];
                echo json_encode($msg);
                return;
            }

            $data = [
                'statut_depense' => STATUT_DEPENSE[1],
                'employe_confirm' => $_SESSION['id_employe'],
                'date_confirm' => date('Y-m-d H:i:s'),
                'entrepot_id' => $_SESSION['id_entrepot'],
                'ID_depense' => $id
            ];

            if (Soutra::update("depense", $data)) {
                $msg = ["success" => true, "msg" => "Dépense validee avec succès"];
            } else {
                $msg = ["success" => false, "msg" => "Une erreur est survenue !"];
            }
            echo json_encode($msg);
        }
    }

    public static function annulation_depense()
    {
        if (isset($_POST['btn_action']) && $_POST['btn_action'] == "btn_annulation_depense") {
            extract($_POST);
            $msg = [];
            if (empty($id)) {
                $msg = ["success" => false, "msg" => "ID de la dépense manquant"];
                echo json_encode($msg);
                return;
            }

            $data = [
                'statut_depense' => STATUT_DEPENSE[2],
                'employe_confirm' => $_SESSION['id_employe'],
                'date_confirm' => date('Y-m-d H:i:s'),
                'entrepot_id' => $_SESSION['id_entrepot'],
                'ID_depense' => $id
            ];

            if (Soutra::update("depense", $data)) {
                $msg = ["success" => true, "msg" => "Dépense annulee avec succès"];
            } else {
                $msg = ["success" => false, "msg" => "Une erreur est survenue !"];
            }
            echo json_encode($msg);
        }
    }
}
