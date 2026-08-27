<?php

namespace App\Models;

use App\Core\Auth;
use App\Core\Model;
use Exception;
use PDO;
use TABLES;

class DashboardModel extends Model
{
    protected string $table = "dashboard";
    protected string $id = 'id';

    public function getTotals(string $etablissementCode): array
    {
        $data = [
            'total_clients' => 0,
            'total_souscriptions' => 0,
            'total_souscriptions_valide' => 0,
            'total_souscriptions_annule' => 0,
            'total_packs' => 0,
            'total_articles' => 0,
            'total_users' => 0,
            'total_zones' => 0,
            'total_sessions' => 0,
            'total_depenses' => 0,
            'total_cautions' => 0,
            'total_distributions' => 0,
            'montant_total_cautions' => 0,
            'versements_en_attente' => 0,
            'montant_versements_en_attente' => 0,
            'cautions_en_attente' => 0,
            'montant_cautions_en_attente' => 0,
        ];

        try {
            $data['total_clients'] = (int) $this->countWhere(TABLES::CLIENTS, ['etablissement_code' => $etablissementCode]);
            $data['total_souscriptions'] = (int) $this->countWhere(TABLES::SOUSCRIPTIONS, ['etablissement_code' => $etablissementCode]);
            $data['total_souscriptions_valide'] = (int) $this->countWhere(TABLES::SOUSCRIPTIONS, ['etablissement_code' => $etablissementCode, 'statut_souscription' => 'valide']);
            $data['total_souscriptions_annule'] = (int) $this->countWhere(TABLES::SOUSCRIPTIONS, ['etablissement_code' => $etablissementCode, 'statut_souscription' => 'annule']);
            $data['total_packs'] = (int) $this->countWhere(TABLES::PACKS, ['etablissement_code' => $etablissementCode]);
            $data['total_articles'] = (int) $this->countWhere(TABLES::ARTICLES, ['etablissement_code' => $etablissementCode]);
            $data['total_users'] = (int) $this->countWhere(TABLES::USERS, ['etablissement_code' => $etablissementCode]);
            $data['total_zones'] = (int) $this->countWhere(TABLES::ZONES, ['etablissement_code' => $etablissementCode]);
            $data['total_sessions'] = (int) $this->countWhere(TABLES::SESSIONS, ['etablissement_code' => $etablissementCode]);
            $data['total_depenses'] = (int) $this->countWhere(TABLES::DEPENSES, ['etablissement_code' => $etablissementCode]);
            $data['total_distributions'] = (int) $this->countWhere(TABLES::DISTRIBUTIONS, ['etablissement_code' => $etablissementCode]);

            $cautions = $this->getFieldsForParams(TABLES::CAUTISATION_CLIENTS, ['etablissement_code' => $etablissementCode], ['montant_cautisation_client'], true);
            $data['total_cautions'] = count($cautions);
            $data['montant_total_cautions'] = array_sum(array_column($cautions, 'montant_cautisation_client'));

            if (class_exists(TABLES::VERSEMENTS_COMMERCIAUX)) {
                $data['versements_en_attente'] = (int) $this->countWhere(TABLES::VERSEMENTS_COMMERCIAUX, ['etablissement_code' => $etablissementCode, 'statut_versement' => 'en_attente']);
            }

            $cautionsEnAttente = $this->getFieldsForParams(TABLES::CAUTISATION_CLIENTS, ['etablissement_code' => $etablissementCode, 'statut_cautisation_client' => 'En attente'], ['montant_cautisation_client'], true);
            $data['cautions_en_attente'] = count($cautionsEnAttente);
            $data['montant_cautions_en_attente'] = array_sum(array_column($cautionsEnAttente, 'montant_cautisation_client'));
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $data;
    }

    public function getAlerts(string $etablissementCode): array
    {
        $alerts = [];

        try {
            if (class_exists(TABLES::VERSEMENTS_COMMERCIAUX)) {
                $pendingVersements = $this->countWhere(TABLES::VERSEMENTS_COMMERCIAUX, ['etablissement_code' => $etablissementCode, 'statut_versement' => 'en_attente']);
                if ($pendingVersements > 0) {
                    $alerts[] = [
                        'type' => 'warning',
                        'icon' => 'fa-clock',
                        'message' => "$pendingVersements versement(s) commercial(aux) en attente de validation",
                        'link' => url('validations')
                    ];
                }
            }

            $cautionsEnAttente = $this->getFieldsForParams(TABLES::CAUTISATION_CLIENTS, ['etablissement_code' => $etablissementCode, 'statut_cautisation_client' => 'En attente'], ['montant_cautisation_client'], true);
            if (!empty($cautionsEnAttente)) {
                $montant = array_sum(array_column($cautionsEnAttente, 'montant_cautisation_client'));
                $alerts[] = [
                    'type' => 'info',
                    'icon' => 'fa-hand-holding-usd',
                    'message' => count($cautionsEnAttente) . " caution(s) en attente de validation (" . number_format($montant, 0, ',', ' ') . " FCFA)",
                    'link' => url('cautions')
                ];
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $alerts;
    }

    public function getLastActivities(string $etablissementCode, int $limit = 8): array
    {
        $data = [];
        try {
            $sql = "SELECT 'souscription' AS type, ins.code_souscription AS code, CONCAT('Souscription ', cl.nom_client) AS libelle, CONCAT(us.nom_user, ' ', us.prenom_user) AS utilisateur, ins.created_at_souscription AS date_activite, ins.statut_souscription AS statut FROM " . TABLES::SOUSCRIPTIONS . " ins JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code JOIN " . TABLES::USERS . " us ON us.code_user = ins.user_code WHERE ins.etablissement_code = :etablissement_code
            UNION ALL
            SELECT 'pack' AS type, p.code_pack AS code, p.libelle_pack AS libelle, CONCAT(us.nom_user, ' ', us.prenom_user) AS utilisateur, p.created_at_pack AS date_activite, p.statut_pack AS statut FROM " . TABLES::PACKS . " p JOIN " . TABLES::USERS . " us ON us.code_user = p.user_code WHERE p.etablissement_code = :etablissement_code2
            UNION ALL
            SELECT 'client' AS type, cl.code_client AS code, CONCAT('Client ', cl.nom_client) AS libelle, CONCAT(us.nom_user, ' ', us.prenom_user) AS utilisateur, cl.created_at_client AS date_activite, 'actif' AS statut FROM " . TABLES::CLIENTS . " cl JOIN " . TABLES::USERS . " us ON us.code_user = cl.user_code WHERE cl.etablissement_code = :etablissement_code3
            UNION ALL
            SELECT 'depense' AS type, d.code_depense AS code, td.libelle_type_depense AS libelle, CONCAT(us.nom_user, ' ', us.prenom_user) AS utilisateur, d.created_at_depense AS date_activite, d.statut_depense AS statut FROM " . TABLES::DEPENSES . " d JOIN " . TABLES::TYPE_DEPENSES . " td ON td.code_type_depense = d.type_depense_code JOIN " . TABLES::USERS . " us ON us.code_user = d.user_code WHERE d.etablissement_code = :etablissement_code4
            ORDER BY date_activite DESC LIMIT :limit";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':etablissement_code', $etablissementCode);
            $stmt->bindValue(':etablissement_code2', $etablissementCode);
            $stmt->bindValue(':etablissement_code3', $etablissementCode);
            $stmt->bindValue(':etablissement_code4', $etablissementCode);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $data;
    }

    private function countWhere(string $table, array $params): int
    {
        $where = implode(' AND ', array_map(fn($f) => "$f = :$f", array_keys($params)));
        $sql = "SELECT COUNT(*) FROM $table WHERE $where";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}
