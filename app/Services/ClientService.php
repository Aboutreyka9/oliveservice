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


    // SEXION SEMESTRES

    public function saveDepenseData(array $post)
    {
        extract($post);

        // if (!empty($this->etudiantModel->getFieldsForParams(TABLES::DEPENSES, ['libelle_depense' => $libelle_depense, 'annee_code' => $libelle_annee, 'etablissement_code' => Auth::user('etablissement_code')]))) {
        //     return ['success' => false, 'message' => "Desolé! Ce depense existe déjà."];
        // }

        $code = $this->clientModel->generatorCode(TABLES::DEPENSES, 'code_depense');
        $date = date('Y-m-d H:i:s');

        $data_depense = [
            'type_depense_code' => $libelle_depense,
            'code_depense' => $code,
            'montant_depense' => $montant_depense,
            'periode_depense' => $date_depense,
            'description_depense' => $description_depense,
            'statut_depense' => '',
            'annee_code' => Auth::user('annee_code'),
            'user_code' => Auth::user('id'),
            'etablissement_code' => Auth::user('etablissement_code'),
            'created_at_depense' => $date
        ];

        if (isset($statut_depense)) {
            $data_depense['user_confirm'] = Auth::user('id');
            $data_depense['created_at_confirm'] = $date;
            $data_depense['statut_depense'] = STATUT_ACTIF;
        }

        if (!$this->clientModel->create(TABLES::DEPENSES, $data_depense)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }

        return [
            'success' => true,
            'message' => 'Depense enregistrée avec succès.',
        ];
    }


    public function updateDepenseData($post)
    {
        extract($post);


        $date = date('Y-m-d H:i:s');

        $data_depense = [
            'type_depense_code' => $libelle_depense,
            'montant_depense' => $montant_depense,
            'periode_depense' => $date_depense,
            'description_depense' => $description_depense,
            'updated_at_depense' => $date
        ];

        if (isset($statut_depense)) {
            $data_depense['user_confirm'] = Auth::user('id');
            $data_depense['created_at_confirm'] = $date;
            $data_depense['statut_depense'] = STATUT_ACTIF;
        }

        if (!$this->clientModel->update(TABLES::DEPENSES, 'code_depense', $code_depense, $data_depense)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }


        return [
            'success' => true,
            'message' => 'Modification effectuée avec succès.',
        ];
    }

    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * DEBUT SEXION SETTING TEMPLATES 
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    // SEXION CLIENT

     public function packsSessionData($sessions,)
    {
        $output = '';
        foreach ($sessions as $data) {
            $output .= ' 
            <div class="session-container">
                    <div class="session-header  toggle-role"  data-user="' . $data['user'] . '" data-groupe="' . $data['groupe'] . '" data-role="' . $data['code_role'] . '" id="r' . $data['code_role'] . '" data-checked="false">
                    <div class="" >
                    <h5> <i class="fa fa-check-circle"></i> ' .  strtoupper($data['module']) . '</h5>
                   
                    </div>
                        <div class="">
                        </div>

                    </div>

                    <div class="packs mt-3" id="packs-r' . $data['code_role'] . '">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="45%">MODULES</th>
                                    <th>➕ AJOUTER</th>
                                    <th>👁️ VOIR</th>
                                    <th>✏️ MODIFIER</th>
                                    <th>❌ SUPPRIMER</th>
                                </tr>
                            </thead>
                            <tbody id="sexion-r' . $data['code_role'] . '">
                            </tbody>
                        </table>
                    </div>
                </div>
            ';
        }
        return $output;
    }

     public function DataTablePack($userRolesPermissions, $roles)
    {
        $output = '';
        $checked = '';
        foreach ($roles as $data) {
            $equal = $this->checkIfExistRole($userRolesPermissions, $data);
            // $checked = rolePermissionChecked($userRolesPermissions[$data['code_role']]) ? 'checked' : '';
            if (array_key_exists($data['code_role'], $userRolesPermissions))
                $checked = isAllPermissionsChecked($userRolesPermissions[$data['code_role']], [
                    'create',
                    'show',
                    'edit',
                    'delete'
                ]) ? 'checked' : '';

            $c = $equal['create'] ? 'checked' : '';
            $s = $equal['show'] ? 'checked' : '';
            $e = $equal['edit'] ? 'checked' : '';
            $d = $equal['delete'] ? 'checked' : '';

            $output .= '
                <tr data-id="' . $data['code_role'] . '" >
                    <td> 
                    <input ' . $checked . ' type="checkbox" class="form-check-input me-2 role-check" id="role' . $data['code_role'] . '"> &nbsp;
                        <label for="role' . $data['code_role'] . '">' .  strtoupper($data['libelle_role']) . '
                        </label> </td>

                    <td><input id="create' . $data['code_role'] . '" ' . $c . ' class="perm" data-type="create" type="checkbox"></td>
                    <td><input id="show' . $data['code_role'] . '" ' . $s . ' class="perm" data-type="show" type="checkbox"></td>
                    <td><input id="edit' . $data['code_role'] . '" ' . $e . ' class="perm" data-type="edit" type="checkbox"></td>
                    <td><input id="delete' . $data['code_role'] . '" ' . $d . ' class="perm" data-type="delete" type="checkbox"></td>
                </tr>
                ';
        }
        return $output;
    }

    public function inscriptionAddModalService(array $typeDepenses = [])
    {

        $output = "";
        $output .= '
            <form action="#" method="post" id="frmAddDepense">
                <div class="row mb-3">

                    <div class="col-md-6 mb-3">
                      <input type="hidden" value="btn_add_depense" name="action">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="nom_client" class="form-label">Nom du client <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="nom_client" required>
                    </div>
                       <div class="col-md-6 mb-3">

                        <label for="telephone_client" class="form-label">Contact du client <strong class="text-danger">*</strong></label>
                        <input type="number" class="form-control" name="telephone_client" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="genre_client" class="form-label">Genre <strong class="text-danger">*</strong></label>
                        <select class="form-control" id="genre_client"  name="genre_client" required>
                            <option value="">--- CHOISIR ---</option>

                        ';

                        foreach (SEXEP as $sx) {
                            $output .= '<option value="' . $sx . '">' . $sx . '</option>';
                        }

                        $output .= '
     
                        </select>
                    </div>
                     <div class="col-md-4 mb-3">
                        
                        <label for="lieu_client" class="form-label">Lieu de residence <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="lieu_client" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="code_client" class="form-label">Code client <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="code_client" required>
                    </div>
                </div>
                <hr>
                <h3 class="text-primary text-center">--------CHOIX DES PACKS--------</h1>
                <div class="row mt-2 mb-2">
                 <div class="col-md-4 mb-3">
                        <label for="code_client" class="form-label">Code client <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="code_client" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-secondary" id="btnSubmitFormDepense"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }


    public function depenseUpdateModalService(array $depense, $typeDepenses)
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmUpdateDepense">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_update_depense" name="action">
                        <input type="hidden" value="' . $depense['code_depense'] . '" name="code_depense">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_depense" class="form-label">Année academique <strong class="text-danger">*</strong></label>
                        <select class="form-control" id="libelle_depense"  name="libelle_depense" required>
                            <option value="">--- CHOISIR ---</option>

                        ';

        foreach ($typeDepenses as $tpd) {
            $output .= '<option  ' . selected($tpd['code_type_depense'], $depense['type_depense_code']) . '  value="' . $tpd['code_type_depense'] . '">' . $tpd['libelle_type_depense'] . '</option>';
        }

        $output .= '
     
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        
                        <label for="montant_depense" class="form-label">Montant <strong class="text-danger">*</strong></label>
                        <input type="number" class="form-control" value="' . $depense['montant_depense'] . '" name="montant_depense" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="date_depense" class="form-label">Date <strong class="text-danger">*</strong></label>
                        <input type="date" class="form-control" value="' . $depense['periode'] . '" name="date_depense" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        
                        <label for="description_depense" class="form-label">Description </label>
                        <textarea rows="3"  class="form-control" name="description_depense" >' . $depense['description_depense'] . '</textarea>
                    </div>

                    <div class="col-md-12">
                        
                        <label for="statut_depense" class="">
                        <input id="statut_depense" value="1" type="checkbox"  name="statut_depense" >
                        <strong class="text-danger">Confirmer, Cette action est irréversible
      et cela empechera toute modification en cas d\'eurreur ⚠ !</strong> 
                        </label>

                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-secondary" id="btnSubmitFormDepense"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }

    public function depenseDataService($depenses)
    {

        $i = 0;
        $data = [];

        foreach ($depenses as $depense) {
            $i++;

            $etat = checkStatusDepense($depense['statut_depense']);

            $actions = '
            <button class="btn btn-light btn-link " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">

       ';
            if ($depense['statut_depense'] == STATUT_DEPENSE[0]) {
                $actions .= '

                  <button class="dropdown-item " id="Modifier" onclick="modalUpdatedDepense(\'' . $depense['code_depense'] . '\')" 
            data-toggle="tooltip" title="" data-original-title="Modifier depense">
        <i class="fa fa-edit text-icon-primary"></i> &nbsp; &nbsp; Modifier depense </button>
       

                <button class="dropdown-item " id="" onclick="changeStatutDepense(\'' . $depense['code_depense'] . '\',\'' . STATUT_ACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Activer depense ">
            <i class="fa fa-check text-icon-success"></i> &nbsp; &nbsp; Valider depense </button>

             <button class="dropdown-item " id="" onclick="annulerDepense(\'' . $depense['code_depense'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Annuler depense ">
            <i class="fa fa-trash text-icon-danger"></i> &nbsp; &nbsp; Annuler depense </button>
       
        ';
            } else {
                $actions .= '
         <button class="dropdown-item " id="" onclick="imprimerDepense(\'' . $depense['code_depense'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Imprimer depense ">
            <i class="fa fa-print text-icon-info"></i> &nbsp; &nbsp; Imprimer depense </button>
        ';
            }
            $actions .= ' </div>
            ';

            $data[] = [
                $i,
                $depense['libelle_type_depense'],
                date_formater($depense['periode_depense']),
                $etat,
                $depense['montant_depense'],
                $depense['user_confirm'],
                date_formater($depense['periode_depense']),
                $actions
            ];
        }

        return $data;
    }

    // ================= CLIENTS =================

    public function clientDataService($clients)
    {
        $i = 0;
        $data = [];

        foreach ($clients as $client) {
            $i++;

            $etat = checkEtatData($client['statut_client'] ?? STATUT_ACTIF);

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
            ';

            if (($client['statut_client'] ?? STATUT_ACTIF) == STATUT_ACTIF) {
                $actions .= '
                <button class="dropdown-item" onclick="changeStatutClient(\'' . $client['code_client'] . '\',\'' . STATUT_INACTIF . '\')" data-toggle="tooltip" title="" data-original-title="Désactiver client">
                    <i class="fa fa-times text-icon-danger"></i> &nbsp; Désactiver
                </button>
                ';
            } else {
                $actions .= '
                <button class="dropdown-item" onclick="changeStatutClient(\'' . $client['code_client'] . '\',\'' . STATUT_ACTIF . '\')" data-toggle="tooltip" title="" data-original-title="Activer client">
                    <i class="fa fa-check text-icon-success"></i> &nbsp; Activer
                </button>
                ';
            }

            $actions .= '</div>';

            $data[] = [
                $i,
                $etat,
                strtoupper($client['nom_client']),
                $client['telephone_client'],
                $client['genre_client'],
                $client['lieu_client'],
                $client['code_client'],
                $client['user_code'],
                date_formater($client['created_at_client']),
                $actions
            ];
        }

        return $data;
    }

    public function clientAddModalService()
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmAddClient">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <input type="hidden" value="btn_add_client" name="action">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="nom_client" class="form-label">Nom complet <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="nom_client" name="nom_client" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telephone_client" class="form-label">Contact <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control telephone" id="telephone_client" name="telephone_client" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="genre_client" class="form-label">Genre <strong class="text-danger">*</strong></label>
                        <select class="form-control select2" id="genre_client" name="genre_client" required>
                            <option value="">--- CHOISIR ---</option>
                            <option value="Masculin">Masculin</option>
                            <option value="Féminin">Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="lieu_client" class="form-label">Lieu de residence <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="lieu_client" name="lieu_client" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="code_client" class="form-label">Code client <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="code_client" name="code_client" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormClient"><i class="fas fa-save"></i> &nbsp; Enregistrer</button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>
                    </div>
                </div>
            </form>
        ';
        return $output;
    }

    public function saveClientData(array $post, array $packs = [])
    {
        extract($post);

        if (!empty($this->activiteModel->getFieldsForParams(TABLES::CLIENTS, ['code_client' => $code_client, 'etablissement_code' => Auth::user('etablissement_code')]))) {
            return ['success' => false, 'message' => 'Desolé! Ce code client existe déjà.'];
        }

        $data_client = [
            'nom_client' => strtoupper($nom_client),
            'telephone_client' => $telephone_client,
            'genre_client' => $genre_client,
            'lieu_client' => strtoupper($lieu_client),
            'code_client' => strtoupper($code_client),
            'email_client' => $email_client ?? null,
            'profession_client' => $profession_client ?? null,
            'statut_client' => STATUT_ACTIF,
            'etablissement_code' => Auth::user('etablissement_code'),
            'user_code' => Auth::user('id'),
            'created_at_client' => date('Y-m-d H:i:s'),
        ];

        if (!$this->activiteModel->create(TABLES::CLIENTS, $data_client)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }

        if (!empty($packs)) {
            $date = date('Y-m-d H:i:s');
            $annee_code = Auth::user('annee_code');
            $etablissement_code = Auth::user('etablissement_code');
            $id = Auth::user('id');

            $packInscriptions = [];
            foreach ($packs as $packCode) {
                $packInscriptions[] = [
                    'pack_code' => $packCode,
                    'client_code' => $code_client,
                    'annee_code' => $annee_code,
                    'etablissement_code' => $etablissement_code,
                    'user_code' => $id,
                    'created_at_pack_inscription' => $date,
                ];
            }

            $this->activiteModel->insertMultiple(TABLES::PACK_INSCRIPTIONS, $packInscriptions);
        }

        return [
            'success' => true,
            'message' => 'Client enregistré avec succès.',
        ];
    }
}
