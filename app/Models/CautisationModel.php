<?php

namespace App\Models;

use App\Core\Auth;
use App\Core\Model;
use Exception;
use PDO;
use TABLES;

class CautisationModel extends Model
{
    protected string $table = "cautisation_clients";
    public string $id = 'id_cautisation_client';

    public function getCautisationsByInscription(string $inscriptionCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT c.*, ins.code_inscription, cl.nom_client, cl.telephone_client,
                           se.libelle_session, an.libelle_annee, zo.libelle_zone
                    FROM " . TABLES::CAUTISATION_CLIENTS . " c
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code
                    JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code
                    JOIN " . TABLES::SESSIONS . " se ON se.code_session = ins.session_code
                    JOIN " . TABLES::ANNEES . " an ON an.code_annee = ins.annee_code
                    JOIN " . TABLES::ZONES . " zo ON zo.code_zone = ins.zone_code
                    WHERE ins.code_inscription = :inscription_code
                      AND ins.etablissement_code = :etablissement_code
                    ORDER BY c.created_at_cautisation_client DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['inscription_code' => $inscriptionCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getCautisationsByClient(string $clientCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT c.*, ins.code_inscription, cl.nom_client, se.libelle_session, an.libelle_annee
                    FROM " . TABLES::CAUTISATION_CLIENTS . " c
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code
                    JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code
                    JOIN " . TABLES::SESSIONS . " se ON se.code_session = ins.session_code
                    JOIN " . TABLES::ANNEES . " an ON an.code_annee = ins.annee_code
                    WHERE ins.client_code = :client_code
                      AND ins.etablissement_code = :etablissement_code
                    ORDER BY c.created_at_cautisation_client DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['client_code' => $clientCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getTotalCautisationByInscription(string $inscriptionCode): float
    {
        $sql = "SELECT COALESCE(SUM(montant_cautisation_client), 0) 
                FROM " . TABLES::CAUTISATION_CLIENTS . " 
                WHERE inscription_code = :inscription_code 
                  AND statut_cautisation_client = 'valide'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['inscription_code' => $inscriptionCode]);
        return (float) $stmt->fetchColumn();
    }

    public function getPendingCautions(string $etablissementCode, array $filters = []): array
    {
        $data = [];
        try {
            $where = "WHERE ins.etablissement_code = :etablissement_code AND c.statut_cautisation_client = 'En attente'";
            $params = ['etablissement_code' => $etablissementCode];

            if (!empty($filters['session_code'])) {
                $where .= " AND ins.session_code = :session_code";
                $params['session_code'] = $filters['session_code'];
            }
            if (!empty($filters['zone_code'])) {
                $where .= " AND ins.zone_code = :zone_code";
                $params['zone_code'] = $filters['zone_code'];
            }
            if (!empty($filters['search'])) {
                $where .= " AND (cl.nom_client LIKE :search OR cl.telephone_client LIKE :search OR c.code_cautisation_client LIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }

            $sql = "SELECT c.*, ins.code_inscription, cl.nom_client, cl.telephone_client,
                           se.libelle_session, an.libelle_annee, zo.libelle_zone,
                           p.montant_pack as montant_total_pack
                    FROM " . TABLES::CAUTISATION_CLIENTS . " c
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code
                    JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code
                    JOIN " . TABLES::SESSIONS . " se ON se.code_session = ins.session_code
                    JOIN " . TABLES::ANNEES . " an ON an.code_annee = ins.annee_code
                    JOIN " . TABLES::ZONES . " zo ON zo.code_zone = ins.zone_code
                    LEFT JOIN " . TABLES::PACK_INSCRIPTIONS . " pi ON pi.inscription_code = ins.code_inscription
                    LEFT JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code
                    $where
                    ORDER BY c.created_at_cautisation_client DESC";

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

    public function getStatsCautions(string $etablissementCode, ?string $sessionCode = null, ?string $zoneCode = null, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        $data = [
            'total' => 0,
            'en_attente' => 0,
            'valide' => 0,
            'annule' => 0,
            'montant_total' => 0,
            'montant_en_attente' => 0,
            'montant_valide' => 0,
            'montant_annule' => 0,
        ];

        try {
            $where = "WHERE ins.etablissement_code = :etablissement_code";
            $params = ['etablissement_code' => $etablissementCode];

            if ($sessionCode) {
                $where .= " AND ins.session_code = :session_code";
                $params['session_code'] = $sessionCode;
            }
            if ($zoneCode) {
                $where .= " AND ins.zone_code = :zone_code";
                $params['zone_code'] = $zoneCode;
            }
            if ($dateDebut && $dateFin) {
                $where .= " AND DATE(c.created_at_cautisation_client) BETWEEN :date_debut AND :date_fin";
                $params['date_debut'] = $dateDebut;
                $params['date_fin'] = $dateFin;
            }

            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN c.statut_cautisation_client = 'En attente' THEN 1 ELSE 0 END) as en_attente,
                        SUM(CASE WHEN c.statut_cautisation_client = 'valide' THEN 1 ELSE 0 END) as valide,
                        SUM(CASE WHEN c.statut_cautisation_client = 'ennule' THEN 1 ELSE 0 END) as annule,
                        SUM(c.montant_cautisation_client) as montant_total,
                        SUM(CASE WHEN c.statut_cautisation_client = 'En attente' THEN c.montant_cautisation_client ELSE 0 END) as montant_en_attente,
                        SUM(CASE WHEN c.statut_cautisation_client = 'valide' THEN c.montant_cautisation_client ELSE 0 END) as montant_valide,
                        SUM(CASE WHEN c.statut_cautisation_client = 'ennule' THEN c.montant_cautisation_client ELSE 0 END) as montant_annule
                    FROM " . TABLES::CAUTISATION_CLIENTS . " c
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code
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
                    'annule' => (int) $result['annule'],
                    'montant_total' => (float) ($result['montant_total'] ?? 0),
                    'montant_en_attente' => (float) ($result['montant_en_attente'] ?? 0),
                    'montant_valide' => (float) ($result['montant_valide'] ?? 0),
                    'montant_annule' => (float) ($result['montant_annule'] ?? 0),
                ];
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $data;
    }

    public function getInscriptionsActivesByClient(string $clientCode, string $etablissementCode): array
    {
        $data = [];
        return [];
        try {
            $sql = "SELECT ins.*, cl.nom_client, cl.telephone_client,
                           se.libelle_session, an.libelle_annee, zo.libelle_zone,
                           p.montant_pack, p.duree_jours_pack,
                           COALESCE(SUM(CASE WHEN cc.statut_cautisation_client = 'valide' THEN cc.montant_cautisation_client ELSE 0 END), 0) as total_paye_valide
                    FROM " . TABLES::INSCRIPTIONS . " ins
                    JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code
                    JOIN " . TABLES::SESSIONS . " se ON se.code_session = ins.session_code
                    JOIN " . TABLES::ANNEES . " an ON an.code_annee = ins.annee_code
                    JOIN " . TABLES::ZONES . " zo ON zo.code_zone = ins.zone_code
                    JOIN " . TABLES::PACK_INSCRIPTIONS . " pi ON pi.inscription_code = ins.code_inscription
                    JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code
                    LEFT JOIN " . TABLES::CAUTISATION_CLIENTS . " cc ON cc.inscription_code = ins.code_inscription
                    WHERE ins.client_code = :client_code
                      AND ins.etablissement_code = :etablissement_code
                      AND ins.statut_inscription = 'valide'
                    GROUP BY ins.code_inscription
                    ORDER BY ins.created_at_inscription DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['client_code' => $clientCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getCautisationsByInscriptionAndPeriode(string $inscriptionCode, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        $data = [];
        try {
            $where = "WHERE inscription_code = :inscription_code";
            $params = ['inscription_code' => $inscriptionCode];

            if ($dateDebut && $dateFin) {
                $where .= " AND ((periode_debut_cautisation BETWEEN :date_debut AND :date_fin) 
                            OR (periode_fin_cautisation BETWEEN :date_debut AND :date_fin)
                            OR (periode_debut_cautisation <= :date_debut AND periode_fin_cautisation >= :date_fin))";
                $params['date_debut'] = $dateDebut;
                $params['date_fin'] = $dateFin;
            }

            $sql = "SELECT * FROM " . TABLES::CAUTISATION_CLIENTS . " $where ORDER BY created_at_cautisation_client DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function checkPeriodeCautisation(string $inscriptionCode, string $dateDebut, string $dateFin): bool
    {
        $cautions = $this->getCautisationsByInscriptionAndPeriode($inscriptionCode, $dateDebut, $dateFin);
        $conflicts = array_filter($cautions, function($c) {
            return $c['statut_cautisation_client'] === 'valide' || $c['statut_cautisation_client'] === 'En attente';
        });
        return empty($conflicts);
    }


  

    public function searchClients(string $search, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT cl.code_client, cl.nom_client, cl.telephone_client, cl.sexe_client, cl.lieu_residence_client
                    FROM " . TABLES::CLIENTS . " cl
                    WHERE cl.etablissement_code = :etablissement_code
                      AND (cl.nom_client LIKE :search OR cl.telephone_client LIKE :search OR cl.code_client LIKE :search)
                    ORDER BY cl.nom_client ASC
                    LIMIT 20";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":etablissement_code", $etablissementCode);
            $stmt->bindValue(":search", "%$search%", PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function dataTableCountTotalCautionsRow(array $filters, $likeParams = []): int
    {
        $where = "WHERE ins.etablissement_code = :etablissement_code";
        
        if (!empty($filters['statut'])) {
            $where .= " AND c.statut_cautisation_client = :statut";
        }
        if (!empty($filters['session_code'])) {
            $where .= " AND ins.session_code = :session_code";
        }
        if (!empty($filters['zone_code'])) {
            $where .= " AND ins.zone_code = :zone_code";
        }
        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $where .= " AND DATE(c.created_at_cautisation_client) BETWEEN :date_debut AND :date_fin";
        }

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::CAUTISATION_CLIENTS . " c
                JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code
                JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code
                $where";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));
        
        if (!empty($filters['statut'])) {
            $stmt->bindValue(":statut", $filters['statut']);
        }
        if (!empty($filters['session_code'])) {
            $stmt->bindValue(":session_code", $filters['session_code']);
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

    public function DataTableFetchCautionsListe(array $filters, array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10): array
    {
        $where = "WHERE ins.etablissement_code = :etablissement_code";
        
        if (!empty($filters['statut'])) {
            $where .= " AND c.statut_cautisation_client = :statut";
        }
        if (!empty($filters['session_code'])) {
            $where .= " AND ins.session_code = :session_code";
        }
        if (!empty($filters['zone_code'])) {
            $where .= " AND ins.zone_code = :zone_code";
        }
        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $where .= " AND DATE(c.created_at_cautisation_client) BETWEEN :date_debut AND :date_fin";
        }

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        $sql = "SELECT c.*, ins.code_inscription, cl.nom_client, cl.telephone_client,
                       se.libelle_session, an.libelle_annee, zo.libelle_zone,
                       p.montant_pack as montant_total_pack
                FROM " . TABLES::CAUTISATION_CLIENTS . " c
                JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code
                JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code
                JOIN " . TABLES::SESSIONS . " se ON se.code_session = ins.session_code
                JOIN " . TABLES::ANNEES . " an ON an.code_annee = ins.annee_code
                JOIN " . TABLES::ZONES . " zo ON zo.code_zone = ins.zone_code
                LEFT JOIN " . TABLES::PACK_INSCRIPTIONS . " pi ON pi.inscription_code = ins.code_inscription
                LEFT JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code
                $where ORDER BY $orderBy $orderDir LIMIT :start, :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));

        if (!empty($filters['statut'])) {
            $stmt->bindValue(":statut", $filters['statut']);
        }
        if (!empty($filters['session_code'])) {
            $stmt->bindValue(":session_code", $filters['session_code']);
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
