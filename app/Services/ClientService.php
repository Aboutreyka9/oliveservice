<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\ClientModel;
use TABLES;

class ClientService
{

    public ClientModel $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * DEBUT SEXION SETTING REQUETES
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    // SEXION INSCRIPTION

        public function saveInscriptionData(array $post, array $packs = [])
    {
        extract($post);

        if (!empty($this->clientModel->getFieldsForParams(TABLES::CLIENTS, ['telephone_client' => $telephone_client, 'etablissement_code' => Auth::user('etablissement_code'),'zone_code' => Auth::user('zone_code')]))) {
            return ['success' => false, 'message' => 'Desolé! Ce client existe déjà.'];
        }

        
        $code_client = $this->clientModel->generatorCode(TABLES::CLIENTS, 'code_client');
        $code_inscription = $this->clientModel->generatorCode(TABLES::INSCRIPTIONS, 'code_inscription');

        $etablissement_code = Auth::user('etablissement_code');
        $user_code = Auth::user('id');
        $annee_code = Auth::user('annee_code');
        $zone_code = Auth::user('zone_code');

        $date = date('Y-m-d H:i:s');

        $data_client = [
            'nom_client' => ucFirst($nom_client),
            'telephone_client' => $telephone_client,
            'sexe_client' => $genre_client,
            'lieu_residence_client' => $lieu_client,
            'code_client' => $code_client,
            'email_client' => $email_client ?? null,
            'profession_client' => $profession_client ?? null,
            'zone_code' => $zone_code,
            'etablissement_code' => $etablissement_code,
            'user_code' => $user_code,
            'created_at_client' => $date,
        ];

         
        $data_inscription = [
            'statut_inscription' => STATUT_INSCRIPTION[0],
            'client_code' => $code_client,
            'session_code' => $session_code,
            'code_inscription' => $code_inscription,
            'zone_code' => $zone_code,
            'annee_code' => $annee_code,
            'etablissement_code' => $etablissement_code,
            'user_code' => $user_code,
            'created_at_inscription' => $date
        ];




        $packInscriptions = [];
        foreach ($packs as $packCode) {
            $packInscriptions[] = [
                'statut_pack_inscription' => STATUT_ACTIF,
                'inscription_code' => $code_inscription,
                'pack_code' => $packCode,
                'annee_code' => $annee_code,
                'etablissement_code' => $etablissement_code,
                'user_code' => $user_code,
                'created_at_pack_inscription' => $date
            ];
        }


        $result = $this->clientModel->transactionData(function () use ($data_client, $data_inscription,$packInscriptions) {

            $this->clientModel->create(TABLES::CLIENTS, $data_client);
            $this->clientModel->create(TABLES::INSCRIPTIONS, $data_inscription);
            $this->clientModel->insertMultiple(TABLES::PACK_INSCRIPTIONS, $packInscriptions);

        });

        if (!$result) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }

        return [
            'success' => true,
            'message' => 'Inscription effectuée avec succès.',
        ];
    }

    public function getProfileData(string $clientCode): array
    {
        $etablissementCode = Auth::user('etablissement_code');
        $client = $this->clientModel->getSingleClientByCode($clientCode);

        if (empty($client)) {
            return [];
        }

        $inscriptions = $this->clientModel->getInscriptionsByClientCode($clientCode, $etablissementCode);
        $packInscriptions = $this->clientModel->getPackInscriptionsByClientCode($clientCode, $etablissementCode);
        $distributions = $this->clientModel->getDistributionsByClientCode($clientCode, $etablissementCode);
        $cautisations = $this->clientModel->getCautisationsByClientCode($clientCode, $etablissementCode);

        return [
            'client' => $client,
            'inscriptions' => $inscriptions,
            'pack_inscriptions' => $packInscriptions,
            'distributions' => $distributions,
            'cautisations' => $cautisations,
        ];
    }

    public function inscriptionDataService($inscriptions)
    {

        $i = 0;
        $data = [];

        foreach ($inscriptions as $inscription) {
            $i++;

            $etat = checkStatusInscription($inscription['statut_inscription']);

            $actions = '
            <button class="btn btn-light btn-link " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">

       ';
            if ($inscription['statut_inscription'] == STATUT_INSCRIPTION[0]) {
                $actions .= '

                  <button class="dropdown-item " id="Modifier" onclick="modalUpdatedDepense(\'' . $inscription['code_inscription'] . '\')" 
            data-toggle="tooltip" title="" data-original-title="Modifier inscription">
        <i class="fa fa-edit text-icon-primary"></i> &nbsp; &nbsp; Modifier inscription </button>
       

                <button class="dropdown-item " id="" onclick="changeStatutInscription(\'' . $inscription['code_inscription'] . '\',\'' . STATUT_ACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Activer inscription ">
            <i class="fa fa-check text-icon-success"></i> &nbsp; &nbsp; Valider inscription </button>

             <button class="dropdown-item " id="" onclick="annulerDepense(\'' . $inscription['code_inscription'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Annuler inscription ">
            <i class="fa fa-trash text-icon-danger"></i> &nbsp; &nbsp; Annuler inscription </button>
       
        ';
            } else {
                $actions .= '
         <button class="dropdown-item " id="" onclick="imprimerInscription(\'' . $inscription['code_inscription'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Imprimer inscription ">
            <i class="fa fa-print text-icon-info"></i> &nbsp; &nbsp; Imprimer inscription </button>
        ';
            }
            $actions .= ' </div>
            ';

            $data[] = [
                $i,
                $inscription['code_inscription'],
                $inscription['nom_client'],
                $inscription['telephone_client'],
                $inscription['libelle_session'],
                $inscription['code_inscription'],
                $inscription['code_inscription'],
                $etat,
                $inscription['nom_complet'],
                date_formater($inscription['created_at_inscription']),
                $actions
            ];
        }

        return $data;
    }



    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * DEBUT SEXION client TEMPLATES 
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    // SEXION CLIENT


    public function updateClientData($post)
    {
        extract($post);

          $client = $this->clientModel->getFieldsForParams(TABLES::CLIENTS, ['telephone_client' => $telephone_client,  'etablissement_code' => Auth::user('etablissement_code')]);
        if (!empty($client) && $client['code_client'] != $code_client) {
            return ['success' => false, 'message' => "Desolé! ce numero de telephone appartient a un autre client."];
        }


        $date = date('Y-m-d H:i:s');

         $data_client = [
            'nom_client' => ucFirst($nom_client),
            'telephone_client' => $telephone_client,
            'sexe_client' => $genre_client,
            'lieu_residence_client' => $lieu_client,
            'email_client' => $email_client ?? null,
            'profession_client' => $profession_client ?? null,
            'updated_at_client' => $date,
        ];

      

        if (!$this->clientModel->update(TABLES::CLIENTS, 'code_client', $code_client, $data_client)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }


        return [
            'success' => true,
            'message' => 'Modification effectuée avec succès.',
        ];
    }

    
    public function clientDataService($clients)
    {
        $i = 0;
        $data = [];

        foreach ($clients as $client) {
            $i++;

            // $etat = checkEtatData($client['statut_client'] ?? STATUT_ACTIF);

            $actions = '
            <button class="btn btn-light btn-link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="' . url('clients/profile/' . $client['code_client']) . '" data-toggle="tooltip" title="" data-original-title="Voir profil">
                    <i class="fa fa-eye text-icon-info"></i> &nbsp; Voir profil
                </a>
                <button class="dropdown-item" onclick="modalUpdatedClient(\'' . $client['code_client'] . '\')" data-toggle="tooltip" title="" data-original-title="Modifier client">
                    <i class="fa fa-edit text-icon-primary"></i> &nbsp; Modifier
                </button>
                <button class="dropdown-item" onclick="changeStatutClient(\'' . $client['code_client'] . '\',\'' . STATUT_INACTIF . '\')" data-toggle="tooltip" title="" data-original-title="Désactiver client">
                    <i class="fa fa-times text-icon-danger"></i> &nbsp; Désactiver
                </button>
                </div>';

            $data[] = [
                $i,
                // $etat,
                strtoupper($client['nom_client']),
                $client['telephone_client'],
                $client['sexe_client'],
                $client['lieu_residence_client'],
                $client['code_client'],
                $client['user_code'],
                date_formater($client['created_at_client']),
                $actions
            ];
        }

        return $data;
    }


     public function ClienteUpdateModalService(array $client)
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmUpdateClient">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_update_client" name="action">
                        <input type="hidden" value="' . $client['code_client'] . '" name="code_client">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="nom_client" class="form-label">Nom du client <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="nom_client" value="' . $client['nom_client'] . '" required>
                    </div>

                   <div class="col-md-12 mb-3">

                        <label for="telephone_client" class="form-label">Contact du client <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="telephone_client" value="' . $client['telephone_client'] . '" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="genre_client" class="form-label">Genre <strong class="text-danger">*</strong></label>
                        <select class="form-control" id="genre_client"  name="genre_client" required>
                            <option value="">--- CHOISIR ---</option>

                        ';

                        foreach (SEXEP as $sx) {
                            $output .= '<option '.selected($client['sexe_client'],$sx).' value="' . $sx . '">' . $sx . '</option>';
                        }

                        $output .= '
     
                        </select>
                    </div>
                     <div class="col-md-12 mb-3">
                        
                        <label for="lieu_client" class="form-label">Lieu de residence <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="lieu_client" value="' . $client['lieu_residence_client'] . '" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        
                        <label for="profession_client" class="form-label">Profession</label>
                        <input type="text" class="form-control" name="profession_client" value="' . $client['profession_client'] . '" required>
                    </div>
                    
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-secondary" id="btnSubmitFormClient"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }




  


}
