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


// SEXION SOUSCRIPTION 
    public function dataTableCountTotalSouscriptionRow(array $whereParams, $likeParams = [])
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


        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::SOUSCRIPTIONS . " $where";

        $stmt = $this->db->prepare($sql);

        // return $sql;
        $stmt->execute(array_merge($whereParams, $likeParams));
        $data = $stmt->fetch();
        return $data['nb'] ?? 0;
    }

    public function getListeSouscriptionForComercial(string $etablissement,string $annee,string $zone,string $user,?string $session = null
    ) {
        $sql = "SELECT ins.*,se.libelle_session,cl.nom_client,cl.telephone_client
                FROM " . TABLES::VUE_TOTAL_SOUSCRIPTION_PACK . " ins
                JOIN " . TABLES::SESSIONS . " se ON se.code_session = ins.session_code
                JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code
                JOIN " . TABLES::USERS . " us ON us.code_user = ins.user_code
                WHERE ins.etablissement_code = :etablissement_code AND ins.annee_code = :annee_code AND ins.zone_code = :zone_code AND ins.user_code = :user_code";

        // Filtre session facultatif
        if (!empty($session)) {
            $sql .= " AND ins.session_code = :session_code";
        }

        $sql .= " ORDER BY ins.created_at_souscription DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':etablissement_code', $etablissement);
        $stmt->bindValue(':annee_code', $annee);
        $stmt->bindValue(':zone_code', $zone);
        $stmt->bindValue(':user_code', $user);

        if (!empty($session)) {
            $stmt->bindValue(':session_code', $session);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }   

        public function DataTableFetchSouscriptionListe(array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10)
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

        $sql = "SELECT ins.*, ins.created_at_souscription, ins.statut_souscription,
                       se.libelle_session, an.libelle_annee, zo.libelle_zone,
                       cl.nom_client, cl.telephone_client,
                       CONCAT(us.nom_user, ' ', us.prenom_user) AS nom_complet,
                       COALESCE(SUM(pi.pack_code), 0) as nb_packs,
                       COALESCE(SUM(p.montant_pack), 0) as montant_pack,
                       COALESCE(SUM(CASE WHEN cc.statut_cautisation_client = 'valide' THEN cc.montant_cautisation_client ELSE 0 END), 0) as montant_paye
                FROM " . TABLES::SOUSCRIPTIONS . " ins 
                JOIN " . TABLES::SESSIONS . "  se ON se.code_session = ins.session_code 
                JOIN " . TABLES::ANNEES . "  an ON an.code_annee = ins.annee_code
                JOIN " . TABLES::ZONES . "  zo ON zo.code_zone = ins.zone_code
                JOIN " . TABLES::CLIENTS . "  cl ON cl.code_client = ins.client_code 
                JOIN " . TABLES::USERS . "  us ON us.code_user = ins.user_code 
                LEFT JOIN " . TABLES::PACK_SOUSCRIPTIONS . " pi ON pi.souscription_code = ins.code_souscription
                LEFT JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code
                LEFT JOIN " . TABLES::CAUTISATION_CLIENTS . " cc ON cc.souscription_code = ins.code_souscription AND cc.statut_cautisation_client = 'valide'
                $where 
                GROUP BY ins.code_souscription
                ORDER BY $orderBy $orderDir LIMIT :start, :limit";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));
        $stmt->bindValue(":zone_code", Auth::user('zone_code'));
        $stmt->bindValue(":annee_code", Auth::user('annee_code'));

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

    public function searchClient(string $search): array
    {
        $data = [];
        try {
            $sql = "SELECT cl.* FROM " . TABLES::CLIENTS . " AS cl 
                    WHERE cl.code_client = :search OR cl.telephone_client = :search 
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['search' => $search]);
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

    public function getSouscriptionsByClientCode(string $clientCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT ins.*, se.libelle_session, se.date_debut_session, se.date_fin_session,
                           an.libelle_annee, an.date_debut_annee, an.date_fin_annee,
                           zo.libelle_zone
                    FROM " . TABLES::SOUSCRIPTIONS . " ins
                    JOIN " . TABLES::SESSIONS . " se ON se.code_session = ins.session_code
                    JOIN " . TABLES::ANNEES . " an ON an.code_annee = ins.annee_code
                    JOIN " . TABLES::ZONES . " zo ON zo.code_zone = ins.zone_code
                    WHERE ins.client_code = :client_code
                      AND ins.etablissement_code = :etablissement_code
                    ORDER BY ins.created_at_souscription DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['client_code' => $clientCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getPackSouscriptionsByClientCode(string $clientCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT pi.*, p.libelle_pack, p.montant_pack, p.statut_pack,
                           pa.quantite_article, pa.article_code, a.libelle_article
                    FROM " . TABLES::PACK_SOUSCRIPTIONS . " pi
                    JOIN " . TABLES::SOUSCRIPTIONS . " ins ON ins.code_souscription = pi.souscription_code
                    JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code
                    LEFT JOIN " . TABLES::PACK_ARTICLES . " pa ON pa.pack_code = pi.pack_code AND pa.annee_code = pi.annee_code
                    LEFT JOIN " . TABLES::ARTICLES . " a ON a.code_article = pa.article_code
                    WHERE ins.client_code = :client_code
                      AND ins.etablissement_code = :etablissement_code
                    ORDER BY pi.created_at_pack_souscription DESC";
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
                    JOIN " . TABLES::SOUSCRIPTIONS . " ins ON ins.code_souscription = d.souscription_code
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
                    JOIN " . TABLES::SOUSCRIPTIONS . " ins ON ins.code_souscription = c.souscription_code
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

    public function getStatsSouscriptionsForCommercial(string $etablissementCode, string $anneeCode, string $zoneCode, string $userCode, ?string $sessionCode = null ): array
    {
        $data = [];
        $params = ['etablissement_code' => $etablissementCode, 'annee_code' => $anneeCode, 'zone_code' => $zoneCode, 'user_code' => $userCode];

        try {
            $sql = "SELECT COUNT(*) AS total,
            COALESCE(SUM(CASE WHEN statut_souscription = 'valide' THEN 1 ELSE 0 END), 0) AS valide,
            COALESCE(SUM(CASE WHEN statut_souscription = 'solde' THEN 1 ELSE 0 END), 0) AS solde,
            COALESCE(SUM(CASE WHEN statut_souscription = 'reconduite' THEN 1 ELSE 0 END), 0) AS reconduite,
            COALESCE(SUM(CASE WHEN statut_souscription = 'annule' THEN 1 ELSE 0 END), 0) AS annule,
            COALESCE(SUM(montant_souscription), 0) AS montant_total,
            COALESCE(SUM(CASE WHEN statut_souscription = 'valide' THEN montant_souscription ELSE 0 END), 0) AS montant_valide,
            COALESCE(SUM( CASE WHEN statut_souscription = 'solde' THEN montant_souscription ELSE 0 END), 0) AS montant_solde,
            COALESCE(SUM( CASE WHEN statut_souscription = 'reconduite' THEN montant_souscription ELSE 0 END), 0) AS montant_reconduite,
            COALESCE(SUM( CASE WHEN statut_souscription = 'annule' THEN montant_souscription ELSE 0 END), 0) AS montant_annule
            FROM ".TABLES::VUE_TOTAL_SOUSCRIPTION_PACK." WHERE etablissement_code = :etablissement_code AND annee_code = :annee_code AND zone_code = :zone_code AND user_code = :user_code";

             // Filtre session facultatif
            if (!empty($sessionCode)) {
                $sql .= " AND session_code = :session_code";
                $params['session_code'] =$sessionCode;
            }


            $stmt = $this->db->prepare($sql);

            $stmt->execute($params);
            if($stmt->rowCount() > 0){
                $data = $stmt->fetch();
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $data;
    }

    public function getSouscriptionDetail(string $souscriptionCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT ins.*, cl.*, se.libelle_session, an.libelle_annee, zo.libelle_zone, u.nom_user, u.prenom_user
                    FROM " . TABLES::VUE_TOTAL_SOUSCRIPTION_PACK . " ins
                    JOIN " . TABLES::CLIENTS . " cl ON cl.code_client = ins.client_code
                    JOIN " . TABLES::SESSIONS . " se ON se.code_session = ins.session_code
                    JOIN " . TABLES::ANNEES . " an ON an.code_annee = ins.annee_code
                    JOIN " . TABLES::ZONES . " zo ON zo.code_zone = ins.zone_code
                    LEFT JOIN " . TABLES::USERS . " u ON u.code_user = ins.user_code
                    WHERE ins.code_souscription = :souscription_code
                      AND ins.etablissement_code = :etablissement_code
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['souscription_code' => $souscriptionCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetch();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getPacksBySouscription(string $souscriptionCode): array
    {
        $data = [];
        try {
            $sql = "SELECT pi.*, ca.libelle_categorie_pack FROM " . TABLES::VUE_TOTAL_PARK_ARTICLES . " pi
                   JOIN " . TABLES::PACK_SOUSCRIPTIONS . " ps ON ps.pack_code = pi.code_pack
                   JOIN " . TABLES::SOUSCRIPTIONS . " ins ON ins.code_souscription = ps.souscription_code 
                   JOIN " . TABLES::CATEGORIES . " ca ON ca.code_categorie_pack = pi.categorie_pack_code
                    WHERE ins.code_souscription = :souscription_code
                    GROUP BY ca.code_categorie_pack, pi.code_pack

                    ORDER BY ca.libelle_categorie_pack, pi.montant_pack DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['souscription_code' => $souscriptionCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getListeArticleByInscriptionCode($souscriptionCode){
        $data = [];
        try{
            $sql= 'SELECT a.code_article, a.libelle_article, a.description_article, a.statut_article, SUM(pa.quantite_article) AS quantite_totale FROM '.TABLES::PACK_SOUSCRIPTIONS.' pi 
            INNER JOIN pack_articles pa ON pa.pack_code = pi.pack_code AND pa.annee_code = pi.annee_code
            INNER JOIN articles a ON a.code_article = pa.article_code 
            WHERE pi.souscription_code = :souscription_code 
            GROUP BY a.code_article
            ORDER BY a.libelle_article';

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['souscription_code' => $souscriptionCode]);
            $data = $stmt->fetchAll();
        }catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getCautionsBySouscription(string $souscriptionCode): array
    {
        $data = [];
        try {
            $sql = "SELECT c.*
                    FROM " . TABLES::CAUTISATION_CLIENTS . " c
                    WHERE c.souscription_code = :souscription_code
                    ORDER BY c.created_at_cautisation_client DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['souscription_code' => $souscriptionCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getDistributionsBySouscription(string $souscriptionCode): array
    {
        $data = [];
        try {
            $sql = "SELECT d.*
                    FROM " . TABLES::DISTRIBUTIONS . " d
                    WHERE d.souscription_code = :souscription_code
                    ORDER BY d.created_at_distribution DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['souscription_code' => $souscriptionCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getStatsSouscriptions(string $etablissementCode, string $anneeCode, string $zoneCode, string $dateDebut, string $dateFin ): array
    {

        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        COALESCE(SUM(CASE WHEN ins.statut_souscription = 'valide' THEN 1 ELSE 0 END), 0) as valide,
                        COALESCE(SUM(CASE WHEN ins.statut_souscription = 'solde' THEN 1 ELSE 0 END), 0) as solde,
                        COALESCE(SUM(CASE WHEN ins.statut_souscription = 'reconduite' THEN 1 ELSE 0 END), 0) as reconduite,
                        COALESCE(SUM(CASE WHEN ins.statut_souscription = 'annule' THEN 1 ELSE 0 END), 0) as annule,
                        COALESCE(SUM(p.montant_pack), 0) as montant_total,
                        COALESCE(SUM(CASE WHEN ins.statut_souscription = 'valide' THEN p.montant_pack ELSE 0 END), 0) as montant_valide,
                        COALESCE(SUM(CASE WHEN ins.statut_souscription = 'solde' THEN p.montant_pack ELSE 0 END), 0) as montant_solde,
                        COALESCE(SUM(CASE WHEN ins.statut_souscription = 'reconduite' THEN p.montant_pack ELSE 0 END), 0) as montant_reconduite,
                        COALESCE(SUM(CASE WHEN ins.statut_souscription = 'annule' THEN p.montant_pack ELSE 0 END), 0) as montant_annule
                    FROM " . TABLES::SOUSCRIPTIONS . " ins
                    LEFT JOIN " . TABLES::PACK_SOUSCRIPTIONS . " pi ON pi.souscription_code = ins.code_souscription
                    LEFT JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code
                    WHERE ins.etablissement_code = :etablissement_code AND ins.annee_code = :annee_code AND ins.zone_code = :zone_code AND DATE(ins.created_at_souscription) BETWEEN :date_debut AND :date_fin GROUP BY ins.code_souscription";

            $stmt = $this->db->prepare($sql);

            $stmt->execute(['etablissement_code' => $etablissementCode, 'annee_code' => $anneeCode, 'zone_code' => $zoneCode, 'date_debut' => $dateDebut, 'date_fin' => $dateFin]);
            if($stmt->rowCount() > 0){
                $data = $stmt->fetch();
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $data;
    }

        public function searchClients(string $search, string $etablissementCode,$anneeCode,$userCode): array
    {
        $data = [];
        try {
            $sql = "SELECT cl.code_client, cl.nom_client, cl.telephone_client, cl.sexe_client, cl.lieu_residence_client
                    FROM " . TABLES::CLIENTS . " cl
                    JOIN ". TABLES::SOUSCRIPTIONS ." ins ON ins.client_code = cl.code_client AND ins.user_code = :user_code AND ins.annee_code = :annee_code
                    WHERE cl.etablissement_code = :etablissement_code 
                      AND (cl.nom_client LIKE :search OR cl.telephone_client LIKE :search OR cl.code_client LIKE :search)
                    ORDER BY cl.nom_client ASC
                    LIMIT 20";

                    // $sql = "SELECT cl.code_client, cl.nom_client, cl.telephone_client, cl.sexe_client, cl.lieu_residence_client
                    // FROM " . TABLES::CLIENTS . " cl
                    // JOIN " . TABLES::SOUSCRIPTIONS . " ins
                    // WHERE ins.client_code = cl.code_client AND cl.etablissement_code = :etablissement_code
                    //   AND (cl.nom_client LIKE :search OR cl.telephone_client LIKE :search OR cl.code_client LIKE :search OR ins.code_souscription LIKE :search)
                    //   GROUP BY ins.code_souscription
                    // ORDER BY cl.nom_client ASC
                    // LIMIT 20";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":user_code", $userCode);
            $stmt->bindValue(":annee_code", $anneeCode);
            $stmt->bindValue(":etablissement_code", $etablissementCode);
            $stmt->bindValue(":search", "%$search%", PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }
}
