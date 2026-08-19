<?php

namespace App\Models;

use App\Core\Auth;
use App\Core\Model;
use Exception;
use PDO;
use TABLES;

class VersementCommercialModel extends Model
{
    protected string $table = "versements_commerciaux";
    public string $id = 'id_versement_commercial';

    public function getVersementsByCommercial(string $commercialCode, string $etablissementCode, array $filters = []): array
    {
        $data = [];
        try {
            $where = "WHERE vc.commercial_code = :commercial_code AND vc.etablissement_code = :etablissement_code";
            $params = ['commercial_code' => $commercialCode, 'etablissement_code' => $etablissementCode];

            if (!empty($filters['statut'])) {
                $where .= " AND vc.statut_versement = :statut";
                $params['statut'] = $filters['statut'];
            }
            if (!empty($filters['date_debut'])) {
                $where .= " AND DATE(vc.created_at_versement) >= :date_debut";
                $params['date_debut'] = $filters['date_debut'];
            }
            if (!empty($filters['date_fin'])) {
                $where .= " AND DATE(vc.created_at_versement) <= :date_fin";
                $params['date_fin'] = $filters['date_fin'];
            }

            $sql = "SELECT vc.*, 
                           CONCAT(u.nom_user, ' ', u.prenom_user) as nom_commercial,
                           zo.libelle_zone
                    FROM " . TABLES::VERSEMENTS_COMMERCIAUX . " vc
                    JOIN " . TABLES::USERS . " u ON u.code_user = vc.commercial_code
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

    public function getPendingVersements(string $etablissementCode, array $filters = []): array
    {
        $data = [];
        try {
            $where = "WHERE vc.etablissement_code = :etablissement_code AND vc.statut_versement = 'en_attente'";
            $params = ['etablissement_code' => $etablissementCode];

            if (!empty($filters['commercial_code'])) {
                $where .= " AND vc.commercial_code = :commercial_code";
                $params['commercial_code'] = $filters['commercial_code'];
            }
            if (!empty($filters['zone_code'])) {
                $where .= " AND vc.zone_code = :zone_code";
                $params['zone_code'] = $filters['zone_code'];
            }
            if (!empty($filters['search'])) {
                $where .= " AND (u.nom_user LIKE :search OR u.prenom_user LIKE :search OR vc.reference_versement LIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }

            $sql = "SELECT vc.*, 
                           CONCAT(u.nom_user, ' ', u.prenom_user) as nom_commercial,
                           zo.libelle_zone
                    FROM " . TABLES::VERSEMENTS_COMMERCIAUX . " vc
                    JOIN " . TABLES::USERS . " u ON u.code_user = vc.commercial_code
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

    public function getStatsVersements(string $etablissementCode, ?string $commercialCode = null, ?string $zoneCode = null, ?string $statut = null, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        $data = [
            'total' => 0,
            'en_attente' => 0,
            'valide' => 0,
            'rejete' => 0,
            'montant_total' => 0,
            'montant_en_attente' => 0,
            'montant_valide' => 0,
            'montant_rejete' => 0,
        ];

        try {
            $where = "WHERE vc.etablissement_code = :etablissement_code";
            $params = ['etablissement_code' => $etablissementCode];

            if ($commercialCode) {
                $where .= " AND vc.commercial_code = :commercial_code";
                $params['commercial_code'] = $commercialCode;
            }
            if ($zoneCode) {
                $where .= " AND vc.zone_code = :zone_code";
                $params['zone_code'] = $zoneCode;
            }
            if ($statut) {
                $where .= " AND vc.statut_versement = :statut";
                $params['statut'] = $statut;
            }
            if ($dateDebut && $dateFin) {
                $where .= " AND DATE(vc.created_at_versement) BETWEEN :date_debut AND :date_fin";
                $params['date_debut'] = $dateDebut;
                $params['date_fin'] = $dateFin;
            }

            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN vc.statut_versement = 'en_attente' THEN 1 ELSE 0 END) as en_attente,
                        SUM(CASE WHEN vc.statut_versement = 'valide' THEN 1 ELSE 0 END) as valide,
                        SUM(CASE WHEN vc.statut_versement = 'rejete' THEN 1 ELSE 0 END) as rejete,
                        SUM(vc.montant_versement) as montant_total,
                        SUM(CASE WHEN vc.statut_versement = 'en_attente' THEN vc.montant_versement ELSE 0 END) as montant_en_attente,
                        SUM(CASE WHEN vc.statut_versement = 'valide' THEN vc.montant_versement ELSE 0 END) as montant_valide,
                        SUM(CASE WHEN vc.statut_versement = 'rejete' THEN vc.montant_versement ELSE 0 END) as montant_rejete
                    FROM " . TABLES::VERSEMENTS_COMMERCIAUX . " vc
                    JOIN " . TABLES::USERS . " u ON u.code_user = vc.commercial_code
                    LEFT JOIN " . TABLES::ZONES . " zo ON zo.code_zone = vc.zone_code
                    $where";

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            $result = $stmt->fetch();

            if ($result) {
                $data = [
                    'total' => (int) $result['total'],
                    'en_attente' => (int) $result['en_attente'],
                    'valide' => (int) $result['valide'],
                    'rejete' => (int) $result['rejete'],
                    'montant_total' => (float) ($result['montant_total'] ?? 0),
                    'montant_en_attente' => (float) ($result['montant_en_attente'] ?? 0),
                    'montant_valide' => (float) ($result['montant_valide'] ?? 0),
                    'montant_rejete' => (float) ($result['montant_rejete'] ?? 0),
                ];
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $data;
    }

    public function dataTableCountTotalVersementsRow(array $filters, $likeParams = []): int
    {
        $where = "WHERE vc.etablissement_code = :etablissement_code";

        if (!empty($filters['statut'])) {
            $where .= " AND vc.statut_versement = :statut";
        }
        if (!empty($filters['commercial_code'])) {
            $where .= " AND vc.commercial_code = :commercial_code";
        }
        if (!empty($filters['zone_code'])) {
            $where .= " AND vc.zone_code = :zone_code";
        }
        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $where .= " AND DATE(vc.created_at_versement) BETWEEN :date_debut AND :date_fin";
        }

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::VERSEMENTS_COMMERCIAUX . " vc
                JOIN " . TABLES::USERS . " u ON u.code_user = vc.commercial_code
                LEFT JOIN " . TABLES::ZONES . " zo ON zo.code_zone = vc.zone_code
                $where";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));

        if (!empty($filters['statut'])) {
            $stmt->bindValue(":statut", $filters['statut']);
        }
        if (!empty($filters['commercial_code'])) {
            $stmt->bindValue(":commercial_code", $filters['commercial_code']);
        }
        if (!empty($filters['zone_code'])) {
            $stmt->bindValue(":zone_code", $filters['zone_code']);
        }
        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $stmt->bindValue(":date_debut", $filters['date_debut']);
            $stmt->bindValue(":date_fin", $filters['date_fin']);
        }

        if (!empty($likeParams)) {
            foreach ($likeParams as $key => $value) {
                $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return (int) ($stmt->fetch()['nb'] ?? 0);
    }

    public function DataTableFetchVersementsListe(array $filters, array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10): array
    {
        $where = "WHERE vc.etablissement_code = :etablissement_code";

        if (!empty($filters['statut'])) {
            $where .= " AND vc.statut_versement = :statut";
        }
        if (!empty($filters['commercial_code'])) {
            $where .= " AND vc.commercial_code = :commercial_code";
        }
        if (!empty($filters['zone_code'])) {
            $where .= " AND vc.zone_code = :zone_code";
        }
        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $where .= " AND DATE(vc.created_at_versement) BETWEEN :date_debut AND :date_fin";
        }

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        $sql = "SELECT vc.*, 
                       CONCAT(u.nom_user, ' ', u.prenom_user) as nom_commercial,
                       zo.libelle_zone
                FROM " . TABLES::VERSEMENTS_COMMERCIAUX . " vc
                JOIN " . TABLES::USERS . " u ON u.code_user = vc.commercial_code
                LEFT JOIN " . TABLES::ZONES . " zo ON zo.code_zone = vc.zone_code
                $where ORDER BY $orderBy $orderDir LIMIT :start, :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));

        if (!empty($filters['statut'])) {
            $stmt->bindValue(":statut", $filters['statut']);
        }
        if (!empty($filters['commercial_code'])) {
            $stmt->bindValue(":commercial_code", $filters['commercial_code']);
        }
        if (!empty($filters['zone_code'])) {
            $stmt->bindValue(":zone_code", $filters['zone_code']);
        }
        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $stmt->bindValue(":date_debut", $filters['date_debut']);
            $stmt->bindValue(":date_fin", $filters['date_fin']);
        }

        if (!empty($likeParams)) {
            foreach ($likeParams as $key => $value) {
                $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
            }
        }

        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
