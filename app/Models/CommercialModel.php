<?php

namespace App\Models;

use App\Core\Auth;
use App\Core\Model;
use Exception;
use PDO;
use TABLES;

class CommercialModel extends Model
{
    protected string $table = "commercials";
    public string $id = 'id_commercial';

    public function getCommercialByUserCode(string $userCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT co.*, zo.libelle_zone, zo.code_zone as zone_code,
                           CONCAT(u.nom_user, ' ', u.prenom_user) as nom_commercial,
                           u.email_user, u.telephone_user, u.matricule_user,
                           fn.libelle_fonction, sv.libelle_service
                    FROM " . TABLES::COMMERCIALS . " co
                    JOIN " . TABLES::USERS . " u ON u.code_user = co.user_code
                    LEFT JOIN " . TABLES::ZONES . " zo ON zo.code_zone = co.zone_code
                    LEFT JOIN " . TABLES::FONCTIONS . " fn ON fn.code_fonction = u.fonction_code
                    LEFT JOIN " . TABLES::SERVICES . " sv ON sv.code_service = u.service_code
                    WHERE co.user_code = :user_code AND co.etablissement_code = :etablissement_code
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_code' => $userCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetch();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getClientsByCommercial(string $commercialUserCode, string $etablissementCode, array $filters = []): array
    {
        $data = [];
        try {
            $where = "WHERE ins.user_code = :user_code AND ins.etablissement_code = :etablissement_code";
            $params = ['user_code' => $commercialUserCode, 'etablissement_code' => $etablissementCode];

            if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
                $where .= " AND DATE(ins.created_at_inscription) BETWEEN :date_debut AND :date_fin";
                $params['date_debut'] = $filters['date_debut'];
                $params['date_fin'] = $filters['date_fin'];
            }
            if (!empty($filters['session_code'])) {
                $where .= " AND ins.session_code = :session_code";
                $params['session_code'] = $filters['session_code'];
            }
            if (!empty($filters['zone_code'])) {
                $where .= " AND ins.zone_code = :zone_code";
                $params['zone_code'] = $filters['zone_code'];
            }

            $sql = "SELECT DISTINCT cl.code_client, cl.nom_client, cl.telephone_client, cl.sexe_client,
                           cl.lieu_residence_client, cl.created_at_client,
                           COUNT(DISTINCT ins.code_inscription) as nb_inscriptions,
                           MIN(ins.created_at_inscription) as premiere_inscription
                    FROM " . TABLES::CLIENTS . " cl
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.client_code = cl.code_client
                    $where
                    GROUP BY cl.code_client
                    ORDER BY cl.created_at_client DESC";

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getVersementsByCommercial(string $commercialUserCode, string $etablissementCode, array $filters = []): array
    {
        $data = [];
        try {
            $where = "WHERE vc.commercial_code = :commercial_code AND vc.etablissement_code = :etablissement_code";
            $params = ['commercial_code' => $commercialUserCode, 'etablissement_code' => $etablissementCode];

            if (!empty($filters['statut'])) {
                $where .= " AND vc.statut_versement = :statut";
                $params['statut'] = $filters['statut'];
            }
            if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
                $where .= " AND DATE(vc.created_at_versement) BETWEEN :date_debut AND :date_fin";
                $params['date_debut'] = $filters['date_debut'];
                $params['date_fin'] = $filters['date_fin'];
            }

            $sql = "SELECT vc.*, zo.libelle_zone
                    FROM " . TABLES::VERSEMENTS_COMMERCIAUX . " vc
                    LEFT JOIN " . TABLES::ZONES . " zo ON zo.code_zone = vc.zone_code
                    $where ORDER BY vc.created_at_versement DESC";

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getStatsCommercial(string $commercialUserCode, string $etablissementCode, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        $data = [
            'total_clients' => 0,
            'total_inscriptions' => 0,
            'total_packs' => 0,
            'montant_total_packs' => 0,
            'total_versements' => 0,
            'montant_versements_valides' => 0,
            'montant_versements_en_attente' => 0,
            'montant_versements_rejetes' => 0,
            'total_cautions' => 0,
            'montant_cautions_valides' => 0,
            'montant_cautions_en_attente' => 0,
        ];

        try {
            $params = ['user_code' => $commercialUserCode, 'etablissement_code' => $etablissementCode];
            $dateFilter = '';
            if ($dateDebut && $dateFin) {
                $dateFilter = " AND DATE(ins.created_at_inscription) BETWEEN :date_debut AND :date_fin";
                $params['date_debut'] = $dateDebut;
                $params['date_fin'] = $dateFin;
            }

            $sql = "SELECT 
                        COUNT(DISTINCT cl.code_client) as total_clients,
                        COUNT(DISTINCT ins.code_inscription) as total_inscriptions,
                        COUNT(DISTINCT pi.code_pack_inscription) as total_packs,
                        COALESCE(SUM(p.montant_pack), 0) as montant_total_packs
                    FROM " . TABLES::INSCRIPTIONS . " ins
                    JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code
                    LEFT JOIN " . TABLES::PACK_INSCRIPTIONS . " pi ON pi.inscription_code = ins.code_inscription
                    LEFT JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code
                    WHERE ins.user_code = :user_code AND ins.etablissement_code = :etablissement_code $dateFilter";

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            $result = $stmt->fetch();

            if ($result) {
                $data['total_clients'] = (int) $result['total_clients'];
                $data['total_inscriptions'] = (int) $result['total_inscriptions'];
                $data['total_packs'] = (int) $result['total_packs'];
                $data['montant_total_packs'] = (float) $result['montant_total_packs'];
            }

            $versements = $this->getVersementsByCommercial($commercialUserCode, $etablissementCode, $dateDebut ? ['date_debut' => $dateDebut, 'date_fin' => $dateFin] : []);
            foreach ($versements as $v) {
                $data['total_versements']++;
                if ($v['statut_versement'] === 'valide') {
                    $data['montant_versements_valides'] += $v['montant_versement'];
                } elseif ($v['statut_versement'] === 'en_attente') {
                    $data['montant_versements_en_attente'] += $v['montant_versement'];
                } elseif ($v['statut_versement'] === 'rejete') {
                    $data['montant_versements_rejetes'] += $v['montant_versement'];
                }
            }

            $cautionsParams = ['etablissement_code' => $etablissementCode, 'ins.user_code' => $commercialUserCode];
            $cautionsDateFilter = '';
            if ($dateDebut && $dateFin) {
                $cautionsDateFilter = " AND DATE(c.created_at_cautisation_client) BETWEEN :date_debut AND :date_fin";
                $cautionsParams['date_debut'] = $dateDebut;
                $cautionsParams['date_fin'] = $dateFin;
            }

            $sqlCautions = "SELECT 
                                COUNT(*) as total_cautions,
                                SUM(CASE WHEN c.statut_cautisation_client = 'valide' THEN c.montant_cautisation_client ELSE 0 END) as montant_valides,
                                SUM(CASE WHEN c.statut_cautisation_client = 'En attente' THEN c.montant_cautisation_client ELSE 0 END) as montant_en_attente
                            FROM " . TABLES::CAUTISATION_CLIENTS . " c
                            JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code
                            WHERE ins.user_code = :user_code AND ins.etablissement_code = :etablissement_code $cautionsDateFilter";

            $stmtCautions = $this->db->prepare($sqlCautions);
            foreach ($cautionsParams as $key => $value) {
                $stmtCautions->bindValue(":$key", $value);
            }
            $stmtCautions->execute();
            $cautionsResult = $stmtCautions->fetch();

            if ($cautionsResult) {
                $data['total_cautions'] = (int) $cautionsResult['total_cautions'];
                $data['montant_cautions_valides'] = (float) ($cautionsResult['montant_valides'] ?? 0);
                $data['montant_cautions_en_attente'] = (float) ($cautionsResult['montant_en_attente'] ?? 0);
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $data;
    }

    public function getPerformanceCommercial(string $commercialUserCode, string $etablissementCode, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        $data = [
            'taux_validation_versements' => 0,
            'taux_validation_cautions' => 0,
            'montant_moyen_par_client' => 0,
            'nombre_moyen_packs_par_client' => 0,
            'evolution_clients' => [],
            'evolution_versements' => [],
        ];

        try {
            $versements = $this->getVersementsByCommercial($commercialUserCode, $etablissementCode, $dateDebut && $dateFin ? ['date_debut' => $dateDebut, 'date_fin' => $dateFin] : []);
            $totalVersements = count($versements);
            $versementsValides = 0;
            foreach ($versements as $v) {
                if ($v['statut_versement'] === 'valide') {
                    $versementsValides++;
                }
            }
            $data['taux_validation_versements'] = $totalVersements > 0 ? round(($versementsValides / $totalVersements) * 100, 2) : 0;

            $cautionsParams = ['etablissement_code' => $etablissementCode, 'ins.user_code' => $commercialUserCode];
            if ($dateDebut && $dateFin) {
                $cautionsParams['date_debut'] = $dateDebut;
                $cautionsParams['date_fin'] = $dateFin;
            }
            $sqlCautions = "SELECT c.statut_cautisation_client FROM " . TABLES::CAUTISATION_CLIENTS . " c
                            JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code
                            WHERE ins.user_code = :user_code AND ins.etablissement_code = :etablissement_code";
            if ($dateDebut && $dateFin) {
                $sqlCautions .= " AND DATE(c.created_at_cautisation_client) BETWEEN :date_debut AND :date_fin";
            }
            $stmtCautions = $this->db->prepare($sqlCautions);
            foreach ($cautionsParams as $key => $value) {
                $stmtCautions->bindValue(":$key", $value);
            }
            $stmtCautions->execute();
            $cautions = $stmtCautions->fetchAll();
            $totalCautions = count($cautions);
            $cautionsValides = 0;
            foreach ($cautions as $c) {
                if ($c['statut_cautisation_client'] === 'valide') {
                    $cautionsValides++;
                }
            }
            $data['taux_validation_cautions'] = $totalCautions > 0 ? round(($cautionsValides / $totalCautions) * 100, 2) : 0;

            $clients = $this->getClientsByCommercial($commercialUserCode, $etablissementCode, $dateDebut && $dateFin ? ['date_debut' => $dateDebut, 'date_fin' => $dateFin] : []);
            $data['nombre_moyen_packs_par_client'] = count($clients) > 0 ? round($data['total_packs'] / count($clients), 2) : 0;
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $data;
    }
}
