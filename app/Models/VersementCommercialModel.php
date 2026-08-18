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

    public function dataTableCountTotalVersementsRow(array $whereParams, $likeParams = []): int
    {
        $where = "WHERE vc.etablissement_code = :etablissement_code";

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
        $stmt->execute(array_merge($whereParams, $likeParams));
        return (int) ($stmt->fetch()['nb'] ?? 0);
    }

    public function DataTableFetchVersementsListe(array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10): array
    {
        $where = "WHERE vc.etablissement_code = :etablissement_code";

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
