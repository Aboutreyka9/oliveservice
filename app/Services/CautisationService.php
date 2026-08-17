<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\CautisationModel;
use TABLES;

class CautisationService
{
    public CautisationModel $cautisationModel;

    public function __construct()
    {
        $this->cautisationModel = new CautisationModel();
    }

    public function saveCautisationData(array $post): array
    {
        extract($post);

        $code = $this->cautisationModel->generatorCode(TABLES::CAUTISATION_CLIENTS, 'code_cautisation_client');

        $data = [
            'code_cautisation_client' => $code,
            'montant_cautisation_client' => $montant_cautisation,
            'inscription_code' => $inscription_code,
            'statut_cautisation_client' => 'En attente',
            'etablissement_code' => Auth::user('etablissement_code'),
            'user_code' => Auth::user('id'),
            'created_at_cautisation_client' => date('Y-m-d H:i:s'),
            'updated_at_cautisation_client' => date('Y-m-d H:i:s'),
        ];

        if (!$this->cautisationModel->create(TABLES::CAUTISATION_CLIENTS, $data)) {
            return ['success' => false, 'message' => "Désolé! échec d'opération."];
        }

        return [
            'success' => true,
            'message' => 'Cautisation enregistrée avec succès. En attente de validation.',
        ];
    }

    public function validateCautisation(string $codeCautisation, string $statut): array
    {
        $cautisation = $this->cautisationModel->getFieldsForParams(
            TABLES::CAUTISATION_CLIENTS,
            ['code_cautisation_client' => $codeCautisation]
        );

        if (empty($cautisation)) {
            return ['success' => false, 'message' => 'Cautisation introuvable.'];
        }

        if ($cautisation['statut_cautisation_client'] !== 'En attente') {
            return ['success' => false, 'message' => 'Cette cautisation a déjà été traitée.'];
        }

        $data = [
            'statut_cautisation_client' => $statut,
            'updated_at_cautisation_client' => date('Y-m-d H:i:s'),
        ];

        if (!$this->cautisationModel->update(TABLES::CAUTISATION_CLIENTS, 'code_cautisation_client', $codeCautisation, $data)) {
            return ['success' => false, 'message' => "Désolé! échec de validation."];
        }

        $message = $statut === 'valide' ? 'Cautisation validée avec succès.' : 'Cautisation annulée.';
        return ['success' => true, 'message' => $message];
    }

    public function getCautisationDataService(array $cautions): array
    {
        $i = 0;
        $data = [];

        foreach ($cautions as $c) {
            $i++;
            $totalPaye = $this->cautisationModel->getTotalCautisationByInscription($c['code_inscription']);
            $reste = max(0, ($c['montant_total_pack'] ?? 0) - $totalPaye);

            $actions = '
            <button class="btn btn-light btn-link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">
                <button class="dropdown-item" onclick="validateCautisation(\'' . $c['code_cautisation_client'] . '\', \'valide\')">
                    <i class="fa fa-check text-icon-success"></i> Valider
                </button>
                <button class="dropdown-item" onclick="validateCautisation(\'' . $c['code_cautisation_client'] . '\', \'ennule\')">
                    <i class="fa fa-times text-icon-danger"></i> Annuler
                </button>
            </div>';

            $data[] = [
                $i,
                $c['code_cautisation_client'],
                $c['nom_client'],
                $c['telephone_client'],
                $c['libelle_session'],
                $c['libelle_zone'],
                number_format($c['montant_cautisation_client'], 0, ',', ' ') . ' FCFA',
                number_format($totalPaye, 0, ',', ' ') . ' FCFA',
                number_format($reste, 0, ',', ' ') . ' FCFA',
                date_formater($c['created_at_cautisation_client'], true),
                $actions
            ];
        }

        return $data;
    }

    public function cautisationAddModalService(array $inscriptions = []): string
    {
        $output = '';
        $output .= '
        <form action="#" method="post" id="frmAddCautisation">
            <div class="row mb-3">
                <div class="col-md-12 mb-3">
                    <input type="hidden" value="btn_add_cautisation" name="action">
                    <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                    <label for="inscription_code" class="form-label">Inscription <strong class="text-danger">*</strong></label>
                    <select class="form-control select2" id="inscription_code" name="inscription_code" required>
                        <option value="">--- CHOISIR ---</option>';

        if (!empty($inscriptions)) {
            foreach ($inscriptions as $ins) {
                $label = $ins['nom_client'] . ' - ' . $ins['libelle_session'] . ' (' . $ins['libelle_annee'] . ')';
                $output .= '<option value="' . $ins['code_inscription'] . '">' . $label . '</option>';
            }
        }

        $output .= '
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="montant_cautisation" class="form-label">Montant caution <strong class="text-danger">*</strong></label>
                    <input type="number" class="form-control" id="montant_cautisation" name="montant_cautisation" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 modal_footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmitFormCautisation">
                        <i class="fas fa-save"></i> &nbsp; Enregistrer
                    </button>
                    <button type="button" class="btn btn-light dismiss_modal">Fermer</button>
                </div>
            </div>
        </form>';
        return $output;
    }
}
