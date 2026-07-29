<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\EtudiantModel;
use TABLES;

class EtudiantService
{

    public static EtudiantModel $etudiantModel;

    public function __construct()
    {
        self::$etudiantModel = new EtudiantModel();
    }

    /**
     * ------------------------------------------------------------------------
     * **********************************************************************
     * * DEBUT SEXION SETTING REQUETES
     * **********************************************************************
     * --------------------------------------------------------------------------
     */


    // SEXION SEMESTRES

    public static function saveDepenseData(array $post)
    {
        extract($post);

        // if (!empty(self::$etudiantModel->getFieldsForParams(TABLES::DEPENSES, ['libelle_depense' => $libelle_depense, 'annee_code' => $libelle_annee, 'etablissement_code' => Auth::user('etablissement_code')]))) {
        //     return ['success' => false, 'message' => "Desolé! Ce depense existe déjà."];
        // }

        $code = self::$etudiantModel->generatorCode(TABLES::DEPENSES, 'code_depense');
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

        if (!self::$etudiantModel->create(TABLES::DEPENSES, $data_depense)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }

        return [
            'success' => true,
            'message' => 'Depense enregistrée avec succès.',
        ];
    }


    public static function updateDepenseData($post)
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

        if (!self::$etudiantModel->update(TABLES::DEPENSES, 'code_depense', $code_depense, $data_depense)) {
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


    // SEXION SEMESTRES

    public static function depenseAddModalService(array $typeDepenses)
    {

        $output = "";
        $output .= '
            <form action="#" method="post" id="frmAddDepense">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_add_depense" name="action">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_depense" class="form-label">Année academique <strong class="text-danger">*</strong></label>
                        <select class="form-control" id="libelle_depense"  name="libelle_depense" required>
                            <option value="">--- CHOISIR ---</option>

                        ';

        foreach ($typeDepenses as $tpd) {
            $output .= '<option value="' . $tpd['code_type_depense'] . '">' . $tpd['libelle_type_depense'] . '</option>';
        }

        $output .= '
     
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        
                        <label for="montant_depense" class="form-label">Montant <strong class="text-danger">*</strong></label>
                        <input type="number" class="form-control" name="montant_depense" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="date_depense" class="form-label">Date <strong class="text-danger">*</strong></label>
                        <input type="date" class="form-control" name="date_depense" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        
                        <label for="description_depense" class="form-label">Description </label>
                        <textarea rows="3"  class="form-control" name="description_depense" ></textarea>
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


    public static function depenseUpdateModalService(array $depense, $typeDepenses)
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

    public static function depenseDataService($depenses)
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
}
