<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\ActiviteModel;
use App\Models\FinanceModel;
use TABLES;

class ActiviteService
{

    public ActiviteModel $activiteModel;

    public function __construct()
    {
        $this->activiteModel = new ActiviteModel();
    }

    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * DEBUT SEXION SETTING REQUETES
     * **********************************************************************
     * --------------------------------------------------------------------------
     */



    // SEXION ZONE

    public function saveZoneData(array $post)
    {
        extract($post);

        if (!empty($this->activiteModel->getFieldsForParams(TABLES::ZONES, ['libelle_zone' => $libelle_zone, 'etablissement_code' => Auth::user('etablissement_code')]))) {
            return ['success' => false, 'message' => 'Desolé! Ce libelle de zone existe déjà.'];
        }

        $code = $this->activiteModel->generatorCode(TABLES::ZONES, 'code_zone');

        $data_zone = [
            'libelle_zone' => strtoupper($libelle_zone),
            'code_zone' => $code,
            'statut_zone' => STATUT_ACTIF,
            'etablissement_code' => Auth::user('etablissement_code'),
            'user_code' => Auth::user('id'),
            'created_at_zone' => date('Y-m-d H:i:s'),
        ];

        if (!$this->activiteModel->create(TABLES::ZONES, $data_zone)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }

        return [
            'success' => true,
            'message' => 'Zone enregistrée avec succès.',
        ];
    }


    public function updateZoneData($post)
    {
        extract($post);


        $libelle = $this->activiteModel->getFieldsForParams(TABLES::ZONES, ['libelle_zone' => $libelle_zone, 'etablissement_code' => Auth::user('etablissement_code')]);
        if (!empty($libelle) && $libelle['code_zone'] != $code_zone) {
            return ['success' => false, 'message' => 'Desolé! Ce libellé de zone existe déjà.'];
        }


        $data_zone = [
            'libelle_zone' => strtoupper($libelle_zone),
            'description_zone' => $description_zone,
            'updated_at_zone' => date('Y-m-d H:i:s'),
        ];

        if (!$this->activiteModel->update(TABLES::FONCTIONS, 'code_zone', $code_zone, $data_zone)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }


        return [
            'success' => true,
            'message' => 'Modification effectuée avec succès.',
        ];
    }

    // END SEXION  ZONE

    

    // SEXION ZONE

    public function savePackData(array $post)
    {
        extract($post);

        if (!empty($this->activiteModel->getFieldsForParams(TABLES::PACKS, ['libelle_zone' => $libelle_zone, 'etablissement_code' => Auth::user('etablissement_code')]))) {
            return ['success' => false, 'message' => 'Desolé! Ce libelle de zone existe déjà.'];
        }

        $code = $this->activiteModel->generatorCode(TABLES::PACKS, 'code_zone');

        $data_zone = [
            'libelle_zone' => strtoupper($libelle_zone),
            'code_zone' => $code,
            'statut_zone' => STATUT_ACTIF,
            'etablissement_code' => Auth::user('etablissement_code'),
            'user_code' => Auth::user('id'),
            'created_at_zone' => date('Y-m-d H:i:s'),
        ];

        if (!$this->activiteModel->create(TABLES::ZONES, $data_zone)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }

        return [
            'success' => true,
            'message' => 'Zone enregistrée avec succès.',
        ];
    }


    public function updatePackData($post)
    {
        extract($post);


        $libelle = $this->activiteModel->getFieldsForParams(TABLES::ZONES, ['libelle_zone' => $libelle_zone, 'etablissement_code' => Auth::user('etablissement_code')]);
        if (!empty($libelle) && $libelle['code_zone'] != $code_zone) {
            return ['success' => false, 'message' => 'Desolé! Ce libellé de zone existe déjà.'];
        }


        $data_zone = [
            'libelle_zone' => strtoupper($libelle_zone),
            'description_zone' => $description_zone,
            'updated_at_zone' => date('Y-m-d H:i:s'),
        ];

        if (!$this->activiteModel->update(TABLES::FONCTIONS, 'code_zone', $code_zone, $data_zone)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }


        return [
            'success' => true,
            'message' => 'Modification effectuée avec succès.',
        ];
    }

    // END SEXION  ZONE


    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * DEBUT SEXION SETTING TEMPLATES 
     * **********************************************************************
     * --------------------------------------------------------------------------
     */

    
    // SEXION ZONES

    public function zoneAddModalService()
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmAddZone">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_add_zone" name="action">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_zone" class="form-label">Libelle zone <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_zone" name="libelle_zone" required>
                    </div>
                    
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormZone"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }


    public function zoneUpdateModalService(array $zone)
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmUpdateZone">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_update_zone" name="action">
                        <input type="hidden" value="' . $zone['code_zone'] . '" name="code_zone">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_zone" class="form-label">Libelle zone <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_zone" name="libelle_zone" value="' . $zone['libelle_zone'] . '" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description_zone" class="form-label">Description </label>
                        <textarea rows="3" class="form-control" name="description_zone" id="description_zone">' . $zone['description_zone'] . '</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormZone"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }

    public function zoneDataService($zones)
    {

        $i = 0;
        $data = [];

        foreach ($zones as $zone) {
            $i++;

            $etat = checkEtatData($zone['statut_zone']);

            $actions = '
            <button class="btn btn-light btn-link " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">

        <button class="dropdown-item " id="Modifier" onclick="modalUpdatedZone(\'' . $zone['code_zone'] . '\')" 
            data-toggle="tooltip" title="" data-original-title="Modifier zone">
        <i class="fa fa-edit text-icon-primary"></i> &nbsp; &nbsp; Modifier zone </button>
        ';
            if ($zone['statut_zone'] == STATUT_ACTIF) {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutZone(\'' . $zone['code_zone'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Désactiver zone ">
            <i class="fa fa-times text-icon-danger"></i> &nbsp; &nbsp; Désactiver zone </button>
        ';
            } else {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutZone(\'' . $zone['code_zone'] . '\',\'' . STATUT_ACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Activer zone ">
            <i class="fa fa-check text-icon-success"></i> &nbsp; &nbsp; Activer zone </button>
        ';
            }
            $actions .= ' </div>
            ';

            $data[] = [
                $i,
                $etat,
                strtoupper($zone['libelle_zone']),
                textLimit($zone['description_zone']),
                date_formater($zone['created_at_zone']),
                $actions
            ];
        }

        return $data;
    }

    // SEXION ZONE


      // SEXION PACKS

    public function packAddModalService()
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmAddZone">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_add_zone" name="action">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_zone" class="form-label">Libelle zone <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_zone" name="libelle_zone" required>
                    </div>
                    
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormZone"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }


    public function packUpdateModalService(array $zone)
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmUpdateZone">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_update_zone" name="action">
                        <input type="hidden" value="' . $zone['code_zone'] . '" name="code_zone">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_zone" class="form-label">Libelle zone <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_zone" name="libelle_zone" value="' . $zone['libelle_zone'] . '" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description_zone" class="form-label">Description </label>
                        <textarea rows="3" class="form-control" name="description_zone" id="description_zone">' . $zone['description_zone'] . '</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormZone"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }

    public function packDataService($zones)
    {

        $i = 0;
        $data = [];

        foreach ($zones as $zone) {
            $i++;

            $etat = checkEtatData($zone['statut_zone']);

            $actions = '
            <button class="btn btn-light btn-link " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">

        <button class="dropdown-item " id="Modifier" onclick="modalUpdatedZone(\'' . $zone['code_zone'] . '\')" 
            data-toggle="tooltip" title="" data-original-title="Modifier zone">
        <i class="fa fa-edit text-icon-primary"></i> &nbsp; &nbsp; Modifier zone </button>
        ';
            if ($zone['statut_zone'] == STATUT_ACTIF) {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutZone(\'' . $zone['code_zone'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Désactiver zone ">
            <i class="fa fa-times text-icon-danger"></i> &nbsp; &nbsp; Désactiver zone </button>
        ';
            } else {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutZone(\'' . $zone['code_zone'] . '\',\'' . STATUT_ACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Activer zone ">
            <i class="fa fa-check text-icon-success"></i> &nbsp; &nbsp; Activer zone </button>
        ';
            }
            $actions .= ' </div>
            ';

            $data[] = [
                $i,
                $etat,
                strtoupper($zone['libelle_zone']),
                textLimit($zone['description_zone']),
                date_formater($zone['created_at_zone']),
                $actions
            ];
        }

        return $data;
    }

    // SEXION PACKS
    

}
