<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\VersementCommercialModel;
use TABLES;

class VersementCommercialService
{
    public VersementCommercialModel $versementModel;

    public function __construct()
    {
        $this->versementModel = new VersementCommercialModel();
    }

    public function saveVersementData(array $post): array
    {
        extract($post);

        $code = $this->versementModel->generatorCode(TABLES::VERSEMENTS_COMMERCIAUX, 'code_versement_commercial');
        $reference = 'VERS-' . strtoupper(substr(uniqid(), -8));

        $data = [
            'code_versement_commercial' => $code,
            'reference_versement' => $reference,
            'montant_versement' => $montant_versement,
            'commercial_code' => $commercial_code,
            'zone_code' => $zone_code ?? null,
            'periode_versement_debut' => $periode_debut ?? null,
            'periode_versement_fin' => $periode_fin ?? null,
            'statut_versement' => 'en_attente',
            'etablissement_code' => Auth::user('etablissement_code'),
            'user_code' => Auth::user('id'),
            'created_at_versement' => date('Y-m-d H:i:s'),
        ];

        if (!$this->versementModel->create(TABLES::VERSEMENTS_COMMERCIAUX, $data)) {
            return ['success' => false, 'message' => "Désolé! échec d'opération."];
        }

        return [
            'success' => true,
            'message' => 'Versement enregistré avec succès. En attente de validation.',
        ];
    }

    public function validateVersement(string $codeVersement, string $statut, ?string $commentaire = null): array
    {
        $versement = $this->versementModel->getFieldsForParams(
            TABLES::VERSEMENTS_COMMERCIAUX,
            ['code_versement_commercial' => $codeVersement]
        );

        if (empty($versement)) {
            return ['success' => false, 'message' => 'Versement introuvable.'];
        }

        if ($versement['statut_versement'] !== 'en_attente') {
            return ['success' => false, 'message' => 'Ce versement a déjà été traité.'];
        }

        $data = [
            'statut_versement' => $statut,
            'user_validate' => Auth::user('id'),
            'date_validation' => date('Y-m-d H:i:s'),
            'commentaire_validation' => $commentaire,
        ];

        if (!$this->versementModel->update(TABLES::VERSEMENTS_COMMERCIAUX, 'code_versement_commercial', $codeVersement, $data)) {
            return ['success' => false, 'message' => "Désolé! échec de validation."];
        }

        $message = $statut === 'valide' ? 'Versement validé avec succès.' : 'Versement rejeté.';
        return ['success' => true, 'message' => $message];
    }

    public function getVersementDataService(array $versements): array
    {
        $i = 0;
        $data = [];

        foreach ($versements as $v) {
            $i++;

            $statutBadge = '<span class="badge badge-warning">En attente</span>';
            if ($v['statut_versement'] === 'valide') {
                $statutBadge = '<span class="badge badge-success">Validé</span>';
            } elseif ($v['statut_versement'] === 'rejete') {
                $statutBadge = '<span class="badge badge-danger">Rejeté</span>';
            }

            $actions = '
            <button class="btn btn-light btn-link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu">
                <button class="dropdown-item" onclick="validateVersement(\'' . $v['code_versement_commercial'] . '\', \'valide\')">
                    <i class="fa fa-check text-icon-success"></i> Valider
                </button>
                <button class="dropdown-item" onclick="validateVersement(\'' . $v['code_versement_commercial'] . '\', \'rejete\')">
                    <i class="fa fa-times text-icon-danger"></i> Rejeter
                </button>
            </div>';

            $data[] = [
                $i,
                $v['reference_versement'],
                $v['nom_commercial'],
                $v['libelle_zone'],
                number_format($v['montant_versement'], 0, ',', ' ') . ' FCFA',
                date_formater($v['periode_versement_debut'], true) . ' - ' . date_formater($v['periode_versement_fin'], true),
                $statutBadge,
                date_formater($v['created_at_versement'], true),
                $actions
            ];
        }

        return $data;
    }

    public function getStats(string $etablissementCode, array $filters = []): array
    {
        return $this->versementModel->getStatsVersements($etablissementCode, $filters['commercial_code'] ?? null, $filters['zone_code'] ?? null, $filters['statut'] ?? null, $filters['date_debut'] ?? null, $filters['date_fin'] ?? null);
    }

    public function versementAddModalService(array $commercials, array $zones): string
    {
        $output = '';
        $output .= '
        <form action="#" method="post" id="frmAddVersement">
            <div class="row mb-3">
                <div class="col-md-12 mb-3">
                    <input type="hidden" value="btn_add_versement_commercial" name="action">
                    <input type="hidden" value="' . csrfToken()::token() . '" name="csrf_token">
                    <label for="commercial_code" class="form-label">Commercial <strong class="text-danger">*</strong></label>
                    <select class="form-control select2" id="commercial_code" name="commercial_code" required>
                        <option value="">--- CHOISIR ---</option>';

        foreach ($commercials as $c) {
            $output .= '<option value="' . $c['code_user'] . '">' . $c['nom_user'] . ' ' . $c['prenom_user'] . '</option>';
        }

        $output .= '
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="zone_code" class="form-label">Zone</label>
                    <select class="form-control select2" id="zone_code" name="zone_code">
                        <option value="">--- CHOISIR ---</option>';

        foreach ($zones as $z) {
            $output .= '<option value="' . $z['code_zone'] . '">' . $z['libelle_zone'] . '</option>';
        }

        $output .= '
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="montant_versement" class="form-label">Montant versé <strong class="text-danger">*</strong></label>
                    <input type="number" class="form-control" id="montant_versement" name="montant_versement" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="periode_debut" class="form-label">Période début</label>
                    <input type="date" class="form-control" id="periode_debut" name="periode_debut">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="periode_fin" class="form-label">Période fin</label>
                    <input type="date" class="form-control" id="periode_fin" name="periode_fin">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 modal_footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmitFormVersement">
                        <i class="fas fa-save"></i> &nbsp; Enregistrer
                    </button>
                    <button type="button" class="btn btn-light dismiss_modal">Fermer</button>
                </div>
            </div>
        </form>';
        return $output;
    }
}
