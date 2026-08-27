<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\ClientModel;
use App\Models\CautisationModel;
use TABLES;

class ClientService
{

    public ClientModel $clientModel;
    public CautisationModel $cautisationModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->cautisationModel = new CautisationModel();
    }

    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * DEBUT SEXION SETTING REQUETES
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    // SEXION SOUSCRIPTION

        public function saveSouscriptionData(array $post, array $packs = [])
    {
        extract($post);

        if (!empty($this->clientModel->getFieldsForParams(TABLES::CLIENTS, ['telephone_client' => $telephone_client, 'etablissement_code' => Auth::user('etablissement_code'),'zone_code' => Auth::user('zone_code')]))) {
            return ['success' => false, 'message' => 'Desolé! Ce client existe déjà.'];
        }

        
        $code_client = $this->clientModel->generatorCodeClient(TABLES::CLIENTS, 'code_client',$nom_client,$telephone_client);
        $code_souscription = $this->clientModel->generatorCodeSouscription(TABLES::SOUSCRIPTIONS, 'code_souscription');

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

         
        $data_souscription = [
            'statut_souscription' => STATUT_SOUSCRIPTION[0],
            'client_code' => $code_client,
            'session_code' => $session_code,
            'code_souscription' => $code_souscription,
            'zone_code' => $zone_code,
            'annee_code' => $annee_code,
            'etablissement_code' => $etablissement_code,
            'user_code' => $user_code,
            'created_at_souscription' => $date
        ];




        $packSouscriptions = [];
        foreach ($packs as $packCode) {
            $packSouscriptions[] = [
                'statut_pack_souscription' => STATUT_ACTIF,
                'souscription_code' => $code_souscription,
                'pack_code' => $packCode,
                'annee_code' => $annee_code,
                'etablissement_code' => $etablissement_code,
                'user_code' => $user_code,
                'created_at_pack_souscription' => $date
            ];
        }


        $result = $this->clientModel->transactionData(function () use ($data_client, $data_souscription,$packSouscriptions) {

            $this->clientModel->create(TABLES::CLIENTS, $data_client);
            $this->clientModel->create(TABLES::SOUSCRIPTIONS, $data_souscription);
            $this->clientModel->insertMultiple(TABLES::PACK_SOUSCRIPTIONS, $packSouscriptions);

        });

        if (!$result) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }

        return [
            'success' => true,
            'message' => 'Souscription effectuée avec succès.',
        ];
    }

    public function saveResouscriptionData(array $post, array $packs = [])
    {
        extract($post);

        $client = $this->clientModel->getSingleClientByCode($client_code);
        if (empty($client)) {
            return ['success' => false, 'message' => 'Désolé! Ce client n\'existe pas.'];
        }

        $code_souscription = $this->clientModel->generatorCode(TABLES::SOUSCRIPTIONS, 'code_souscription');

        $etablissement_code = Auth::user('etablissement_code');
        $user_code = Auth::user('id');
        $annee_code = Auth::user('annee_code');
        $zone_code = Auth::user('zone_code');
        $date = date('Y-m-d H:i:s');

        $data_souscription = [
            'statut_souscription' => STATUT_SOUSCRIPTION[0],
            'client_code' => $client_code,
            'session_code' => $session_code,
            'code_souscription' => $code_souscription,
            'zone_code' => $zone_code,
            'annee_code' => $annee_code,
            'etablissement_code' => $etablissement_code,
            'user_code' => $user_code,
            'created_at_souscription' => $date
        ];

        $packSouscriptions = [];
        foreach ($packs as $packCode) {
            $packSouscriptions[] = [
                'statut_pack_souscription' => STATUT_ACTIF,
                'souscription_code' => $code_souscription,
                'pack_code' => $packCode,
                'annee_code' => $annee_code,
                'etablissement_code' => $etablissement_code,
                'user_code' => $user_code,
                'created_at_pack_souscription' => $date
            ];
        }

        $result = $this->clientModel->transactionData(function () use ($data_souscription, $packSouscriptions) {
            $this->clientModel->create(TABLES::SOUSCRIPTIONS, $data_souscription);
            $this->clientModel->insertMultiple(TABLES::PACK_SOUSCRIPTIONS, $packSouscriptions);
        });

        if (!$result) {
            return ['success' => false, 'message' => "Désolé! échec d'opération."];
        }

        return [
            'success' => true,
            'message' => 'Resouscription effectuée avec succès.',
        ];
    }

    public function getProfileData(string $clientCode): array
    {
        $etablissementCode = Auth::user('etablissement_code');
        $client = $this->clientModel->getSingleClientByCode($clientCode);

        if (empty($client)) {
            return [];
        }

        $souscriptions = $this->clientModel->getSouscriptionsByClientCode($clientCode, $etablissementCode);
        $packSouscriptions = $this->clientModel->getPackSouscriptionsByClientCode($clientCode, $etablissementCode);
        $distributions = $this->clientModel->getDistributionsByClientCode($clientCode, $etablissementCode);
        $cautisations = $this->clientModel->getCautisationsByClientCode($clientCode, $etablissementCode);

        return [
            'client' => $client,
            'souscriptions' => $souscriptions,
            'pack_souscriptions' => $packSouscriptions,
            'distributions' => $distributions,
            'cautisations' => $cautisations,
        ];
    }

    public function getSouscriptionDetailData(string $souscriptionCode): array
    {
        $etablissementCode = Auth::user('etablissement_code');
        $anneeCode = Auth::user('annee_code');

        $souscription = $this->clientModel->getSouscriptionDetail($souscriptionCode, $etablissementCode);

        if (empty($souscription)) {
            return [];
        }

        $packs = $this->clientModel->getPacksBySouscription($souscriptionCode);
        $articles = $this->clientModel->getListeArticleByInscriptionCode($souscriptionCode);
        $cautisations = $this->cautisationModel->getCautisationsBySouscription($souscriptionCode);
        $statCautisation = $this->cautisationModel->getStatsCautisationsBySouscriptionTest($souscriptionCode,$anneeCode);
        $distributions = $this->clientModel->getDistributionsBySouscription($souscriptionCode);

        return compact('souscription','packs','articles','cautisations','statCautisation','distributions');
    }

    public function souscriptionDataService($souscriptions)
    {
        $i = 0;
        $data = [];

        foreach ($souscriptions as $souscription) {
            $i++;

            $etat = checkStatusSouscription($souscription['statut_souscription']);

            $montantPack = (float) ($souscription['montant_pack'] ?? 0);
            $montantPaye = (float) ($souscription['montant_paye'] ?? 0);
            $resteDu = max(0, $montantPack - $montantPaye);

            $actions = '
            <button class="btn btn-light btn-link " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">
        ';
            if ($souscription['statut_souscription'] == STATUT_SOUSCRIPTION[0]) {
                $actions .= '
                  <button class="dropdown-item " id="Modifier" onclick="modalUpdatedDepense(\'' . $souscription['code_souscription'] . '\')" 
            data-toggle="tooltip" title="" data-original-title="Modifier souscription">
        <i class="fa fa-edit text-icon-primary"></i> &nbsp; &nbsp; Modifier souscription </button>

                <button class="dropdown-item " id="" onclick="changeStatutSouscription(\'' . $souscription['code_souscription'] . '\',\'' . STATUT_ACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Valider souscription ">
            <i class="fa fa-check text-icon-success"></i> &nbsp; &nbsp; Valider souscription </button>

             <button class="dropdown-item " id="" onclick="annulerDepense(\'' . $souscription['code_souscription'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Annuler souscription ">
            <i class="fa fa-trash text-icon-danger"></i> &nbsp; &nbsp; Annuler souscription </button>
        
        ';
            } else {
                $actions .= '
         <button class="dropdown-item " id="" onclick="imprimerSouscription(\'' . $souscription['code_souscription'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Imprimer souscription ">
            <i class="fa fa-print text-icon-info"></i> &nbsp; &nbsp; Imprimer souscription </button>
        ';
            }
            $actions .= '<a class="dropdown-item" href="' . url('souscriptions/detail/' . $souscription['code_souscription']) . '" data-toggle="tooltip" title="" data-original-title="Détails souscription">
                <i class="fa fa-eye text-icon-info"></i> &nbsp; Détails souscription
            </a>';
            $actions .= ' </div>
            ';

            $data[] = [
                $i,
                $souscription['code_souscription'],
                $souscription['nom_client'],
                $souscription['telephone_client'],
                $souscription['libelle_session'],
                number_format($montantPack, 0, ',', ' ') . ' FCFA',
                $etat,
                date_formater($souscription['created_at_souscription']),
                $actions
            ];
        }

        return $data;
    }


    public function souscriptionListeForCommercial(array $listeSoucriptions){
        $output = '';

        if (!empty($listeSoucriptions)) {
        $i = 0;
        foreach ($listeSoucriptions as $data) {
            $i++; 

            $output .= '
            <tr>
            <td>' . $i . '</td>
            <td>' . $data['code_souscription'] . '</td>
            <td>' . $data['nom_client'] . '</td>
            <td>' . $data['telephone_client'] . '</td>
            <td>' . $data['libelle_session'] . '</td>
            <td>' . money($data['montant_souscription']) . '</td>
            <td>' . checkStatusSouscription($data['statut_souscription']) . '</td>
            <td>' . date_formater($data['created_at_souscription']) . '</td>
            <td>
            <button class="btn btn-light btn-link " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu">
        
            <a href="'.url('commercial/souscription/detail',['code' => $data['code_souscription'] ]).'" class="dropdown-item " data-toggle="tooltip" title="" data-original-title="Voir details souscription ">
                <i class="fa fa-eye text-icon-primary"></i> Voir details </a>
                </div>
            </td>
            </tr>';
        }
        return $output;
        } else {
        return "<tr>
            <td colspan='9' class='text-center'>Aucune données disponible</td> </tr>";
        }
    }

    function StatsSouscriptionCommercial($stats)  {
         return '


      <div class="col-md-3">
          <div class="card custom-card-detail">
              <div class="card-body">
                  <div class="d-flex align-items-center">
                      <div class="icon bg-danger mr-2">
                          <i class="fas fa-times-circle"></i>
                      </div>
                      <div>
                          <h6 class="montan-title">Annulées</h6>
                          <h5 class="montan-value">'. ($stats['annule']??0) .'</h5>
                      </div>
                  </div>
                  <div class="mt-2">
                      <small class="text-muted">Montant: <strong class="text-danger">'. money($stats['montant_annule']??0) .' </strong></small>
                  </div>
              </div>
          </div>
      </div>

      <div class="col-md-3">
          <div class="card custom-card-detail">
              <div class="card-body">
                  <div class="d-flex align-items-center">
                      <div class="icon bg-warning mr-2">
                          <i class="fas fa-clock"></i>
                      </div>
                      <div>
                          <h6 class="montan-title">Reconduite</h6>
                          <h5 class="montan-value">'. ($stats['reconduite']??0) .'</h5>
                      </div>
                  </div>
                  <div class="mt-2">
                      <small class="text-muted">Montant: <strong class="text-warning"> '. money($stats['montant_reconduite']??0) .'</strong></small>
                  </div>
              </div>
          </div>
      </div>

      <div class="col-md-3">
          <div class="card custom-card-detail">
              <div class="card-body">
                  <div class="d-flex align-items-center">
                      <div class="icon bg-primary mr-2">
                          <i class="fas fa-clipboard-list"></i>
                      </div>
                      <div>
                          <h6 class="montan-title">En cour </h6>
                          <h5 class="montan-value">'. ($stats['valide']??0) .'</h5>
                      </div>
                  </div>
                  <div class="mt-2">
                      <small class="text-muted">Montant: <strong class="text-primary"> '. money($stats['montant_valide']??0) .' </strong></small>
                  </div>
              </div>
          </div>
      </div>

      <div class="col-md-3">
          <div class="card custom-card-detail">
              <div class="card-body">
                  <div class="d-flex align-items-center">
                      <div class="icon bg-success mr-2">
                          <i class="fas fa-check-circle"></i>
                      </div>
                      <div>
                          <h6 class="montan-title">Soldée</h6>
                          <h5 class="montan-value">'. ($stats['solde']??0) .'</h5>
                      </div>
                  </div>
                  <div class="mt-2">
                      <small class="text-muted">Montant: <strong class="text-success"> '. money($stats['montant_solde']??0) .' </strong></small>
                  </div>
              </div>
          </div>
      </div>

  ';
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

            $actions = '<a class="btn btn-link" href="' . url('clients/profile/' . $client['code_client']) . '" data-toggle="tooltip" title="" data-original-title="Voir profil">
                    <i class="fa fa-eye text-icon-primary"></i> 
                </a>';

            $data[] = [
                $i,
                strtoupper($client['nom_client']),
                $client['telephone_client'],
                $client['sexe_client'],
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
