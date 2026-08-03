<?php

namespace App\Models;

use App\Core\Auth;
use App\Core\Model;
use Exception;
use PDO;
use TABLES;

class ActiviteModel extends Model
{
    protected string $table = "zones";
    public string $id = 'code_zone';


    // SEXION ZONES

    // get all annee
    public function getAllTypeZones($etablissement_code): array
    {
        $data = [];
        try {
            $sql = "SELECT tpd.* FROM " . TABLES::TYPE_DEPENSES . " tpd WHERE tpd.etablissement_code = :etablissement_code  ORDER BY libelle_type_depense DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement_code' => $etablissement_code]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getSingleZoneByCode(string $code): array
    {
        $data = [];
        try {
            $sql = "SELECT de.*, DATE(de.periode_depense) AS periode FROM " . TABLES::DEPENSES . " AS de WHERE de.code_depense = :code LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['code' => $code]);
            $data = $stmt->fetch();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    // get all depense
    public function getAllZones($etablissement_code): array
    {
        $data = [];
        try {
            $sql = "SELECT de* FROM " . TABLES::DEPENSES . " AS de WHERE se.etablissement_code = :etablissement_code AND statut_depense = :statut ORDER BY libelle_depense";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement_code' => $etablissement_code, 'statut' => STATUT_ACTIF]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function dataTbleCountTotalZonesRow(array $whereParams, $likeParams = [])
    {
        // if (!empty($whereParams)) {
        //     $where = 'WHERE ';
        //     $where .=  implode(
        //         ' AND ',
        //         array_map(fn($f) => "$f = :$f ", array_keys($whereParams))
        //     );
        // }

        $where = "WHERE dp.etablissement_code = :etablissement_code AND dp.annee_code = :annee_code";

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


        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::DEPENSES . " dp $where";

        $stmt = $this->db->prepare($sql);

        // return $sql;
        $stmt->execute(array_merge($whereParams, $likeParams));
        $data = $stmt->fetch();
        return $data['nb'] ?? 0;
    }


    public function DataTableFetchZonesListe(array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10)
    {


        $where = "WHERE dp.etablissement_code = :etablissement_code AND dp.annee_code = :annee_code";

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }



        $sql = "SELECT dp.*, tp.libelle_type_depense FROM " . TABLES::DEPENSES . " dp 
        JOIN " . TABLES::TYPE_DEPENSES . "  tp ON tp.code_type_depense = dp.type_depense_code 
        $where ORDER BY $orderBy $orderDir LIMIT :start, :limit";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));
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

    // END SEXION ZONE

    // SEXION CATEGORIES PACKS

    // get all annee
    public function getAllTypeCategoriePacks($etablissement_code): array
    {
        $data = [];
        try {
            $sql = "SELECT tpd.* FROM " . TABLES::TYPE_DEPENSES . " tpd WHERE tpd.etablissement_code = :etablissement_code  ORDER BY libelle_type_depense DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement_code' => $etablissement_code]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getSingleCategoriePackByCode(string $code): array
    {
        $data = [];
        try {
            $sql = "SELECT de.*, DATE(de.periode_depense) AS periode FROM " . TABLES::DEPENSES . " AS de WHERE de.code_depense = :code LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['code' => $code]);
            $data = $stmt->fetch();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    // get all depense
    public function getAllCategoriePacks($etablissement_code): array
    {
        $data = [];
        try {
            $sql = "SELECT de* FROM " . TABLES::DEPENSES . " AS de WHERE se.etablissement_code = :etablissement_code AND statut_depense = :statut ORDER BY libelle_depense";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement_code' => $etablissement_code, 'statut' => STATUT_ACTIF]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function dataTbleCountTotalCategoriePacksRow(array $whereParams, $likeParams = [])
    {
        // if (!empty($whereParams)) {
        //     $where = 'WHERE ';
        //     $where .=  implode(
        //         ' AND ',
        //         array_map(fn($f) => "$f = :$f ", array_keys($whereParams))
        //     );
        // }

        $where = "WHERE cat.etablissement_code = :etablissement_code";

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


        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::CATEGORIES . " cat $where";

        $stmt = $this->db->prepare($sql);

        // return $sql;
        $stmt->execute(array_merge($whereParams, $likeParams));
        $data = $stmt->fetch();
        return $data['nb'] ?? 0;
    }


    public function DataTableFetchCategoriePacksListe(array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10)
    {


        $where = "WHERE cat.etablissement_code = :etablissement_code";

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }



        $sql = "SELECT cat.*, CONCAT(us.nom_user,' ',us.prenom_user) AS nom_user FROM " . TABLES::CATEGORIES . " cat 
        JOIN " . TABLES::USERS . " us ON us.code_user = cat.user_code
        $where ORDER BY $orderBy $orderDir LIMIT :start, :limit";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));

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

    // END SEXION CATEGORIES PACK

      // SEXION PACKS

    // get all annee
    public function getAllTypePAcks($etablissement_code): array
    {
        $data = [];
        try {
            $sql = "SELECT tpd.* FROM " . TABLES::TYPE_DEPENSES . " tpd WHERE tpd.etablissement_code = :etablissement_code  ORDER BY libelle_type_depense DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement_code' => $etablissement_code]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getSinglePAckByCode(string $code): array
    {
        $data = [];
        try {
            $sql = "SELECT de.*, DATE(de.periode_depense) AS periode FROM " . TABLES::DEPENSES . " AS de WHERE de.code_depense = :code LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['code' => $code]);
            $data = $stmt->fetch();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    // get all depense
    public function getAllPAcks($etablissement_code): array
    {
        $data = [];
        try {
            $sql = "SELECT de* FROM " . TABLES::DEPENSES . " AS de WHERE se.etablissement_code = :etablissement_code AND statut_depense = :statut ORDER BY libelle_depense";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement_code' => $etablissement_code, 'statut' => STATUT_ACTIF]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function dataTbleCountTotalPAcksRow(array $whereParams, $likeParams = [])
    {
        // if (!empty($whereParams)) {
        //     $where = 'WHERE ';
        //     $where .=  implode(
        //         ' AND ',
        //         array_map(fn($f) => "$f = :$f ", array_keys($whereParams))
        //     );
        // }

        $where = "WHERE dp.etablissement_code = :etablissement_code AND dp.annee_code = :annee_code";

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


        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::DEPENSES . " dp $where";

        $stmt = $this->db->prepare($sql);

        // return $sql;
        $stmt->execute(array_merge($whereParams, $likeParams));
        $data = $stmt->fetch();
        return $data['nb'] ?? 0;
    }


    public function DataTableFetchPAcksListe(array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10)
    {


        $where = "WHERE dp.etablissement_code = :etablissement_code AND dp.annee_code = :annee_code";

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }



        $sql = "SELECT dp.*, tp.libelle_type_depense FROM " . TABLES::DEPENSES . " dp 
        JOIN " . TABLES::TYPE_DEPENSES . "  tp ON tp.code_type_depense = dp.type_depense_code 
        $where ORDER BY $orderBy $orderDir LIMIT :start, :limit";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));
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

    // END SEXION ZONE
}
