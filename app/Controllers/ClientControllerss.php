<?php

namespace App\Controllers;

use TABLES;
use App\Core\Auth;
use App\Models\Personne;
use App\Services\Service;
use App\Core\MainController;
use App\Services\PersonneService;

class ClientControllerss extends MainController
{
    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES RENDUS
     * SEXION POUR LES VUES 
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    public  function inscription()
    {
        return $this->view('clients/liste', ['title' => "Clients"]);
    }



    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * SEXION POUR LES REQUESTS AJAX
     * SEXION POUR LES AJAX REQUESTS
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    public function bGetListeClients()
    {

        extract($_POST);
        $output = "";
        $personne = new Personne();
        $columns = ['nom_client', 'email_client', 'telephone_client', 'sexe_client', 'client_created_at'];

        $likeParams = [];
        $whereParams = ['boutique_code' => BOUTIQUE_CODE, 'etat_client' => 1];
        $orderBy = ["nom_client" => "ASC"];

        $limit  = $_POST['length'];
        $start  = $_POST['start'];
        $search = $_POST['search']['value'] ?? '';


        // 🔎 Recherche
        if (!empty($search)) {
            $likeParams = ['nom_client' => $search, 'telephone_client' => $search, 'sexe_client' => $search, 'client_created_at' => $search];
        }

        // 🔢 Total
        $total = $personne->dataTbleCountTotalRow(TABLES::CLIENTS, $whereParams);

        // 🔢 Total filtré

        $totalFiltered = $personne->dataTbleCountTotalRow(TABLES::CLIENTS, $whereParams, $likeParams);

        // 📄 Données

        $clients = $personne->DataTableFetchAllListe(TABLES::CLIENTS, $whereParams, $likeParams, $orderBy, $start, $limit);



        $data = [];


        $data = PersonneService::clientDataService($clients);
        echo json_encode([
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => $total,
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
            // "data"            => $data
        ]);
        // echo json_encode(['data' => $total, 'code' => 200]);
        return;
    }

    public function bModalAddClient()
    {

        extract($_POST);
        $output = PersonneService::bClientAddModalService();
        echo json_encode(['data' => $output, 'code' => 200]);
        return;
    }

    public function bAddNewClient()
    {
        $_POST = sanitizePostData($_POST);
        $msg['code'] = 400;
        $msg['type'] = "warning";

        if (!empty($_POST['nom_client']) && !empty($_POST['telephone_client']) && !empty($_POST['sexe'])) {
            extract($_POST);
            $telephone = removeSpace($telephone_client);
            $telephone = str_replace('(+225)', '', $telephone);

            if (ctype_digit($telephone) && mb_strlen($telephone) == 10) {

                $personne = new Personne();

                $client = $personne->getFieldsForParams(TABLES::CLIENTS, ['boutique_code' => BOUTIQUE_CODE, 'telephone_client' => $telephone]);

                if (empty($client)) {
                    $date = date("Y-m-d H:i:s");
                    $codeCient = $personne->generatorCode(TABLES::CLIENTS, "code_client");

                    $data_client = [
                        'nom_client' => strtoupper($nom_client),
                        'telephone_client' => $telephone,
                        'code_client' => $codeCient,
                        'boutique_code' => Auth::user("boutique_code"),
                        'compte_code' => Auth::user("compte_code"),
                        'sexe_client' => $sexe,
                        'client_created_at' => $date
                    ];

                    if ($personne->create(TABLES::CLIENTS, $data_client)) {
                        $msg['code'] = 200;
                        $msg['type'] = "success";
                        $msg['message'] = "Client enregistré avec succès";
                    } else {
                        $msg['message'] = "Désolé, erreur d'enregistrement du client";
                    }
                } else {
                    $msg['message'] = "Desolé, ce numéro de telephone existe déjà.";
                }
            } else {
                $msg['message'] = "Numero de telephone invalide.";
            }
        } else {
            $msg['message'] = "Veuillez remplire tous les champs.";
        }

        echo json_encode($msg);
        return;
    }

    public static function bModalUpdateClient()
    {
        $_POST = sanitizePostData($_POST);
        extract($_POST);
        $msg['code'] = 400;
        $msg['type'] = "warning";
        $personne = new Personne();
        $client = $personne->getFieldsForParams(TABLES::CLIENTS, ['boutique_code' => BOUTIQUE_CODE, 'code_client' => $codeClient]);

        $output = PersonneService::bClientUpdateModalService($client);

        // $output = Service::frmUpdateReservation($code_reservation);
        echo json_encode(['data' => $output, 'code' => 200]);
        return;
    }

    public function bUpdateClient()
    {
        $_POST = sanitizePostData($_POST);
        $msg['code'] = 400;
        $msg['type'] = "warning";

        if (!empty($_POST['nom_client']) && !empty($_POST['telephone_client']) && !empty($_POST['sexe']) && !empty($_POST['code_client'])) {
            extract($_POST);
            $telephone = removeSpace($telephone_client);
            $telephone = str_replace('(+225)', '', $telephone);


            if (ctype_digit($telephone) && mb_strlen($telephone) == 10) {

                $personne = new Personne();

                $client = $personne->getFieldsForParams(TABLES::CLIENTS, ['boutique_code' => BOUTIQUE_CODE, 'telephone_client' => $telephone]);


                if (empty($client) || ($code_client == $client['code_client'])) {

                    $data_client = [
                        'nom_client' => strtoupper($nom_client),
                        'telephone_client' => $telephone,
                        'sexe_client' => $sexe,
                        'email_client' => $email_client,
                    ];

                    $rest = $personne->update(TABLES::CLIENTS, 'code_client', $code_client, $data_client);
                    if ($rest || $rest == 0) {
                        $msg['code'] = 200;
                        $msg['type'] = "success";
                        $msg['message'] = "Client modifié avec succès";
                    } else {
                        $msg['message'] = "Désolé, erreur de modification du client";
                    }
                } else {
                    $msg['message'] = "Desolé, ce numéro de telephone existe déjà.";
                }
            } else {
                $msg['message'] = "Numero de telephone invalide.";
            }
        } else {
            $msg['message'] = "Veuillez renseigner tous les champs.";
        }

        echo json_encode($msg);
        return;
    }
}
