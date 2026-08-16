<?php

namespace App\Models;

use App\Core\Auth;
use App\Core\Model;
use Exception;
use PDO;
use TABLES;

class ClientModel extends Model
{
    protected string $table = "client";
    public string $id = 'code_client';


// SEXION INSCRIPTION 
    public function dataTableCountTotalInscriptionRow(array $whereParams, $likeParams = [])
    {
        // if (!empty($whereParams)) {
        //     $where = 'WHERE ';
        //     $where .=  implode(
        //         ' AND ',
        //         array_map(fn($f) => "$f = :$f ", array_keys($whereParams))
        //     );
        // }

        $where = "WHERE etablissement_code = :etablissement_code AND annee_code = :annee_code AND zone_code = :zone_code";

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        // if (!empty($likeParams)) {
        //     $where .= empty($where) ? ' WHERE ' : ' AND ';
        //     $likes = [];
        //     foreach ($likeParams as $field => $search) {
        //         // $key = "$field";
        //         $likes[] = "$field LIKE :$field";
        //         $likeParams[$field] = "%$search%";
        //     }
        //     // return $likeParams;
        //     $where .= '(' . implode(' OR ', $likes) . ')';
        // }


        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::INSCRIPTIONS . " $where";

        $stmt = $this->db->prepare($sql);

        // return $sql;
        $stmt->execute(array_merge($whereParams, $likeParams));
        $data = $stmt->fetch();
        return $data['nb'] ?? 0;
    }


    public function DataTableFetchInscriptionListe(array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10)
    {


    
        $where = "WHERE ins.etablissement_code = :etablissement_code AND ins.annee_code = :annee_code AND ins.zone_code = :zone_code";


        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }



        $sql = "SELECT ins.*, ins.created_at_inscription, ins.statut_inscription,se.libelle_session, cl.nom_client, cl.telephone_client, CONCAT(us.nom_user, ' ', us.prenom_user) AS nom_complet FROM " . TABLES::INSCRIPTIONS . " ins 
        JOIN " . TABLES::SESSIONS . "  se ON se.code_session = ins.session_code 
        JOIN " . TABLES::CLIENTS . "  cl ON cl.code_client = ins.client_code 
        JOIN " . TABLES::USERS . "  us ON us.code_user = ins.user_code 
        $where ORDER BY $orderBy $orderDir LIMIT :start, :limit";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));
        $stmt->bindValue(":zone_code", Auth::user('zone_code'));
        $stmt->bindValue(":annee_code", Auth::user('annee_code'));

        // Bind les parametreslike
        $like = [];
        if (!empty($likeParams)) {

            foreach ($likeParams as $key => $value) {
                $like[] = "$key => $value";
                $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
            }
        }

        // ✅ Bind LIMIT params correctement
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }


    // SEXION CLIENTS

     public function getSingleClientByCode(string $code): array
    {
        $data = [];
        try {
            $sql = "SELECT cl.* FROM " . TABLES::CLIENTS . " AS cl WHERE cl.code_client = :code LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['code' => $code]);
            $data = $stmt->fetch();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }


    public function getAllClients($etablissement_code): array
    {
        $data = [];
        try {
            $sql = "SELECT * FROM " . TABLES::CLIENTS . " AS cl WHERE cl.etablissement_code = :etablissement_code AND cl.statut_client = :statut ORDER BY cl.nom_client";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement_code' => $etablissement_code, 'statut' => STATUT_ACTIF]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

      public function dataTableCountTotalClientsRow(array $whereParams, $likeParams = [])
    {
        $where = "WHERE cl.etablissement_code = :etablissement_code";

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::CLIENTS . " cl $where";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_merge($whereParams, $likeParams));
        $data = $stmt->fetch();
        return $data['nb'] ?? 0;
    }

    public function DataTableFetchClientsListe(array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10)
    {
        $where = "WHERE cl.etablissement_code = :etablissement_code";

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        $sql = "SELECT cl.* FROM " . TABLES::CLIENTS . " cl 
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

    // END SEXION CLIENTS

    public function getInscriptionsByClientCode(string $clientCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT ins.*, se.libelle_session, se.date_debut_session, se.date_fin_session,
                           an.libelle_annee, an.date_debut_annee, an.date_fin_annee,
                           zo.libelle_zone
                    FROM " . TABLES::INSCRIPTIONS . " ins
                    JOIN " . TABLES::SESSIONS . " se ON se.code_session = ins.session_code
                    JOIN " . TABLES::ANNEES . " an ON an.code_annee = ins.annee_code
                    JOIN " . TABLES::ZONES . " zo ON zo.code_zone = ins.zone_code
                    WHERE ins.client_code = :client_code
                      AND ins.etablissement_code = :etablissement_code
                    ORDER BY ins.created_at_inscription DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['client_code' => $clientCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getPackInscriptionsByClientCode(string $clientCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT pi.*, p.libelle_pack, p.montant_pack, p.statut_pack,
                           pa.quantite_article, pa.article_code, a.libelle_article
                    FROM " . TABLES::PACK_INSCRIPTIONS . " pi
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = pi.inscription_code
                    JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code
                    LEFT JOIN " . TABLES::PACK_ARTICLES . " pa ON pa.pack_code = pi.pack_code AND pa.annee_code = pi.annee_code
                    LEFT JOIN " . TABLES::ARTICLES . " a ON a.code_article = pa.article_code
                    WHERE ins.client_code = :client_code
                      AND ins.etablissement_code = :etablissement_code
                    ORDER BY pi.created_at_pack_inscription DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['client_code' => $clientCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getDistributionsByClientCode(string $clientCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT d.*
                    FROM " . TABLES::DISTRIBUTIONS . " d
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = d.inscription_code
                    WHERE ins.client_code = :client_code
                      AND ins.etablissement_code = :etablissement_code
                    ORDER BY d.created_at_distribution DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['client_code' => $clientCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getCautisationsByClientCode(string $clientCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT c.*
                    FROM " . TABLES::CAUTISATION_CLIENTS . " c
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code
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
}
