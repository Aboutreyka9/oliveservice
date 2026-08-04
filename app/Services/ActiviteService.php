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

     // SEXION CATEGORIE PACKS

    public function savecategoriePackData(array $post)
    {
        extract($post);

        if (!empty($this->activiteModel->getFieldsForParams(TABLES::CATEGORIES, ['libelle_categorie_pack' => $libelle_categorie_pack, 'etablissement_code' => Auth::user('etablissement_code')]))) {
            return ['success' => false, 'message' => 'Desolé! Ce libelle de categorie existe déjà.'];
        }

        $code = $this->activiteModel->generatorCode(TABLES::CATEGORIES, 'code_categorie_pack');

        $data_categories = [
            'libelle_categorie_pack' => strtoupper($libelle_categorie_pack),
            'code_categorie_pack' => $code,
            'statut_categorie_pack' => STATUT_ACTIF,
            'etablissement_code' => Auth::user('etablissement_code'),
            'user_code' => Auth::user('id'),
            'created_at_categorie_pack' => date('Y-m-d H:i:s'),
        ];

        if (!$this->activiteModel->create(TABLES::CATEGORIES, $data_categories)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }

        return [
            'success' => true,
            'message' => 'Categorie enregistrée avec succès.',
        ];
    }


    public function updatecategoriePackData($post)
    {
        extract($post);


        $libelle = $this->activiteModel->getFieldsForParams(TABLES::CATEGORIES, ['libelle_zone' => $libelle_zone, 'etablissement_code' => Auth::user('etablissement_code')]);
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

    // END SEXION  CATEGORIES PACK

       // SEXION ARTICLES

    public function saveArticleData(array $post)
    {
        extract($post);

        if (!empty($this->activiteModel->getFieldsForParams(TABLES::ARTICLES, ['libelle_article' => $libelle_article, 'etablissement_code' => Auth::user('etablissement_code')]))) {
            return ['success' => false, 'message' => 'Desolé! Ce libelle de article existe déjà.'];
        }

        $code = $this->activiteModel->generatorCode(TABLES::ARTICLES, 'code_article');

        $data_articles = [
            'libelle_article' => $libelle_article,
            'description_article' => $description_article,
            'code_article' => $code,
            'statut_article' => STATUT_ACTIF,
            'etablissement_code' => Auth::user('etablissement_code'),
            'user_code' => Auth::user('id'),
            'created_at_article' => date('Y-m-d H:i:s'),
        ];

        if (!$this->activiteModel->create(TABLES::ARTICLES, $data_articles)) {
            return ['success' => false, 'message' => "Desolé! echec d'operation."];
        }

        return [
            'success' => true,
            'message' => 'Article enregistré avec succès.',
        ];
    }


    public function updateArticleData($post)
    {
        extract($post);


        $libelle = $this->activiteModel->getFieldsForParams(TABLES::CATEGORIES, ['libelle_zone' => $libelle_zone, 'etablissement_code' => Auth::user('etablissement_code')]);
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

    // END SEXION  ARTICLES 

    

    // SEXION PACKS

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
            'message' => 'categoriePack enregistrée avec succès.',
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

    // END SEXION  PACK


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

    
    
    // SEXION CATEGORIE PACK

    public function categoriePackAddModalService()
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmAddCategoriePack">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_add_categoriePack" name="action">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_categorie_pack" class="form-label">Libelle categorie <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_categorie_pack" name="libelle_categorie_pack" required>
                    </div>
                    
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormCategoriePack"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }


    public function categoriePackUpdateModalService(array $categoriePack)
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmUpdateCategoriePack">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_update_categoriePack" name="action">
                        <input type="hidden" value="' . $categoriePack['code_categoriePack'] . '" name="code_categoriePack">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_categoriePack" class="form-label">Libelle categoriePack <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_categoriePack" name="libelle_categoriePack" value="' . $categoriePack['libelle_categoriePack'] . '" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description_categoriePack" class="form-label">Description </label>
                        <textarea rows="3" class="form-control" name="description_categoriePack" id="description_categoriePack">' . $categoriePack['description_categoriePack'] . '</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormCategoriePack"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }

    public function categoriePackDataService($categoriePacks)
    {

        $i = 0;
        $data = [];

        foreach ($categoriePacks as $categoriePack) {
            $i++;

            $etat = checkEtatData($categoriePack['statut_categorie_pack']);

            $actions = '
            <button class="btn btn-light btn-link " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">

        <button class="dropdown-item " id="Modifier" onclick="modalUpdatedCategoriePack(\'' . $categoriePack['code_categorie_pack'] . '\')" 
            data-toggle="tooltip" title="" data-original-title="Modifier categoriePack">
        <i class="fa fa-edit text-icon-primary"></i> &nbsp; &nbsp; Modifier categoriePack </button>
        ';
            if ($categoriePack['statut_categorie_pack'] == STATUT_ACTIF) {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutCategoriePack(\'' . $categoriePack['code_categorie_pack'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Désactiver categoriePack ">
            <i class="fa fa-times text-icon-danger"></i> &nbsp; &nbsp; Désactiver categoriePack </button>
        ';
            } else {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutCategoriePack(\'' . $categoriePack['code_categorie_pack'] . '\',\'' . STATUT_ACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Activer categoriePack ">
            <i class="fa fa-check text-icon-success"></i> &nbsp; &nbsp; Activer categoriePack </button>
        ';
            }
            $actions .= ' </div>
            ';

            $data[] = [
                $i,
                $etat,
                strtoupper($categoriePack['libelle_categorie_pack']),
                date_formater($categoriePack['created_at_categorie_pack']),
                $categoriePack['nom_user'],
                $actions
            ];
        }

        return $data;
    }

    // SEXION END CATEGORIE PACKS

    // SEXION ARTICLES

    public function articleAddModalService()
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmAddArticle">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_add_article" name="action">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_article" class="form-label">Libelle article <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_article" name="libelle_article" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        
                        <label for="description_article" class="form-label">Description </label>
                        <textarea rows="3" class="form-control" id="description_article" name="description_article"></textarea>
                        
                    </div>
                    
                    
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormArticle"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }


    public function articleUpdateModalService(array $article)
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmUpdateArticle">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_update_article" name="action">
                        <input type="hidden" value="' . $article['code_article'] . '" name="code_article">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_article" class="form-label">Libelle article <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_article" name="libelle_article" value="' . $article['libelle_article'] . '" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description_article" class="form-label">Description </label>
                        <textarea rows="3" class="form-control" name="description_article" id="description_article">' . $article['description_article'] . '</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormCategoriePack"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }

    public function articleDataService($articles)
    {

        $i = 0;
        $data = [];

        foreach ($articles as $article) {
            $i++;

            $etat = checkEtatData($article['statut_article']);

            $actions = '
            <button class="btn btn-light btn-link " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">

        <button class="dropdown-item " id="Modifier" onclick="modalUpdatedArticle(\'' . $article['code_article'] . '\')" 
            data-toggle="tooltip" title="" data-original-title="Modifier article">
        <i class="fa fa-edit text-icon-primary"></i> &nbsp; &nbsp; Modifier article </button>
        ';
            if ($article['statut_article'] == STATUT_ACTIF) {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutArticle(\'' . $article['code_article'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Désactiver article ">
            <i class="fa fa-times text-icon-danger"></i> &nbsp; &nbsp; Désactiver article </button>
        ';
            } else {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutArticle(\'' . $article['code_article'] . '\',\'' . STATUT_ACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Activer article ">
            <i class="fa fa-check text-icon-success"></i> &nbsp; &nbsp; Activer article </button>
        ';
            }
            $actions .= ' </div>
            ';

            $data[] = [
                $i,
                $etat,
                strtoupper($article['libelle_article']),
                textLimit($article['description_article']),
                date_formater($article['created_at_article']),
                $actions
            ];
        }

        return $data;
    }

    // SEXION ARTICLES 

      // SEXION PACKS

    public function packAddModalService(array $categorie_pack)
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmAddPack">
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <input type="hidden" value="btn_add_pack" name="action">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_pack" class="form-label">Libelle pack <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_pack" name="libelle_pack" required>
                    </div>
                    <div class="col-md-4 mb-3">
                       
                        <label for="libelle_pack" class="form-label">Libelle Categorie <strong class="text-danger">*</strong></label>
                    
                            <select class="form-control" id="libelle_categorie_pack"  name="libelle_categorie_pack" required>
                            <option value="">--- CHOISIR ---</option>

                        ';

        foreach ($categorie_pack as $cat) {
            $output .= '<option value="' . $cat['code_categorie_pack'] . '">' . $cat['libelle_categorie_pack'] . '</option>';
        }

        $output .= '
     
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                         <label for="montant_pack" class="form-label">Montant  <strong class="text-danger">*</strong></label>
                        <input type="number" class="form-control" id="montant_pack" name="montant_pack" required>
                    </div>
                    
                </div>
                <hr>
                
                <h4 class="text-center text-danger"> ---- SELECTION DES ARTICLES DU PACK ----  <span class="badge bg-dark" id="countArticle">0</span> </h4>
                <div class="row mt-3">
                <div class="col-md-8 my-3">
                <select style="background: #0037ff3d;color: #003825;font-size: 17px;" name="" id="dataPack" class="form-control">
                <option value="455&dhgdhgd58"></option>
                <option value="4oo5&dhgdhgd58"></option>
                <option value="4opp;5&5665dhgdhgd58"></option>
                <option value="4oo5&dhgdhgd58"></option>
                <option value="4555895&dhgdhgd58"></option>
                <option value="4u5&dhgdhgd58"></option>
                <option value="45tu&dh8989gdhgd58"></option>
                <option value="45ut&dhgdhgd58"></option>
                <option value="4eyy5&98dhgdhgd58"></option>
                <option value="45&dhgdhgd58"></option>
                <option value="4z5&dhgdhgd58"></option>
                <option value="458688&dhgdhgd58"></option>
                <option value="4rer5&dhgdhgd58"></option>
                <option value="4rr5&dhgdhgd58"></option>
                <option value="45&dhgdhgd58"></option>
                <option value="45&dfdhgdhgd58"></option>
                <option value="45d&dhgdhgd58"></option>
                <option value="45&dhgdhgd58"></option>
                <option value="45&dddhgdhgd58"></option>
                <option value="45&dhgdhgd58"></option>
                <option value="45&ddf"></option>
                <option value="4s5s&dhgdhgd58"></option>
                <option value="45&dhgdhgd58"></option>
                <option value="4dd5&dhgdhgd58"></option>
                <option value="45&dhgdhgd58"></option>
                <option value="12&super" class="3">super</option>
                <option value="35&cool" class="3">cool</option>
                <option value="33&kiz" class="3">kiz</option>*50
                </select>
                </div>
                <div class="col-md-4 my-3">
                <button class="btn btn-outline-danger w-100" type="button" id="btnAddDataPack" >Ajouter</button>
                 </div>

                  <div class="col-md-12 my-3">
                 <div class="table-container-pack">
                  <table class="table table-bordered table-hover table_add_pack" >
                    <thead class="thead-light">
                        <th width="80%">Libelle</th>
                        <th>Quantite</th>
                        <th>action</th>
                    </thead>
                    <tbody></tbody>
                  </table>
                 </div>
                 </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-secondary" id="btnSubmitFormPack"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }


    public function packUpdateModalService(array $pack)
    {
        $output = "";
        $output .= '
            <form action="#" method="post" id="frmUpdatePack">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <input type="hidden" value="btn_update_pack" name="action">
                        <input type="hidden" value="' . $pack['code_pack'] . '" name="code_pack">
                        <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                        <label for="libelle_pack" class="form-label">Libelle pack <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="libelle_pack" name="libelle_pack" value="' . $pack['libelle_pack'] . '" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description_pack" class="form-label">Description </label>
                        <textarea rows="3" class="form-control" name="description_pack" id="description_pack">' . $pack['description_pack'] . '</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 modal_footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitFormPack"><i class="fas fa-save"></i> &nbsp;  Enregistrer </button>
                        <button type="button" class="btn btn-light dismiss_modal">Close</button>

                    </div>
                </div>


            </form> ';
        return $output;
    }

    public function packDataService($packs)
    {

        $i = 0;
        $data = [];

        foreach ($packs as $pack) {
            $i++;

            $etat = checkEtatData($pack['statut_pack']);

            $actions = '
            <button class="btn btn-light btn-link " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">

        <button class="dropdown-item " id="Modifier" onclick="modalUpdatedCategoriePack(\'' . $pack['code_pack'] . '\')" 
            data-toggle="tooltip" title="" data-original-title="Modifier pack">
        <i class="fa fa-edit text-icon-primary"></i> &nbsp; &nbsp; Modifier pack </button>
        ';
            if ($pack['statut_pack'] == STATUT_ACTIF) {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutZone(\'' . $pack['code_pack'] . '\',\'' . STATUT_INACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Désactiver pack ">
            <i class="fa fa-times text-icon-danger"></i> &nbsp; &nbsp; Désactiver pack </button>
        ';
            } else {
                $actions .= '
        <button class="dropdown-item " id="" onclick="changeStatutZone(\'' . $pack['code_pack'] . '\',\'' . STATUT_ACTIF . '\')" 
            data-toggle="tooltip" title="" data-original-title="Activer pack ">
            <i class="fa fa-check text-icon-success"></i> &nbsp; &nbsp; Activer pack </button>
        ';
            }
            $actions .= ' </div>
            ';

            $data[] = [
                $i,
                $etat,
                strtoupper($pack['libelle_pack']),
                textLimit($pack['description_pack']),
                date_formater($pack['created_at_pack']),
                $actions
            ];
        }

        return $data;
    }

    // SEXION PACKS
    

}
