<?php

namespace App\Models;

use App\Core\Auth;
use App\Core\Model;
use Exception;
use PDO;
use TABLES;

class UserModel extends Model
{
    protected string $table = "users";
    public string $id = 'code_user';

    public function getUser($field, $value)
    {
        $sql = "SELECT * FROM " . TABLES::USERS . " WHERE " . $field . " = :field LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["field" => $value]);

        return $stmt->fetch() ?: null;
    }

    public function groupes(): ?array
    {
        $data = [];
        try {
            $sql = "SELECT * FROM roles r WHERE r.statut_role = :statut GROUP BY r.groupe";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["statut" => STATUT_ACTIF]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function createPermission(array $rolePermissions): ?String
    {

        $data = "";
        try {
            $sql = "INSERT INTO user_roles (user_code , role_code, create_permission, edit_permission, show_permission, delete_permission)
                    VALUES (:user_code, :role_code, :create_permission, :edit_permission, :show_permission, :delete_permission)
                    ON DUPLICATE KEY UPDATE 
                    create_permission = VALUES(create_permission), 
                    edit_permission = VALUES(edit_permission), 
                    show_permission = VALUES(show_permission), 
                    delete_permission = VALUES(delete_permission)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($rolePermissions);
            $data = $this->db->lastInsertId() ?: $stmt->rowCount();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }
    public function deletePermission(string $userCode, string $roleCode): ?bool
    {
        $data = false;
        try {
            $sql = "DELETE FROM user_roles WHERE user_code = :user_code AND role_code = :role_code";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_code' => $userCode,
                'role_code' => $roleCode
            ]);
            $data = $stmt->rowCount() > 0;
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getRolesByGroupe($groupe)
    {
        $result = [];
        try {
            $sql = "SELECT * FROM " . TABLES::ROLES . " WHERE groupe = :groupe";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(["groupe" => $groupe]);
            if ($stmt->rowCount() > 0)
                $result = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $result;
    }

    public function getAllPermissionForUser(string $userCode)
    {
        $data = [];
        $sql = "SELECT * FROM  " . TABLES::USER_ROLES . " ur WHERE ur.user_code =:user_code ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_code' => $userCode]);
        $data =  $stmt->fetchAll();
        return $data;
    }

    public function getUserDataForLogin(string $email, string $value)
    {
        $data = [];
        try {
            $sql = "SELECT fn.libelle_fonction, COALESCE(an.code_annee,null) AS annee_code , u.* FROM " . TABLES::USERS . " AS u 
            LEFT JOIN " . TABLES::FONCTIONS . " AS fn ON fn.code_fonction = u.fonction_code
            LEFT JOIN " . TABLES::ANNEES . " AS an ON an.etablissement_code = u.etablissement_code AND an.statut_annee = :statut_annee
         WHERE {$email} = :email AND statut_user = :statut  LIMIT 1

        ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                ['email' => $value, 'statut' => STATUT_ACTIF, 'statut_annee' => STATUT_ACTIF]
            );
            $data = $stmt->rowCount() > 0 ? $stmt->fetch() : [];
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function updateLastConnexion(string $code): void
    {
        $sql = "UPDATE " . TABLES::USERS . " SET last_connexion = NOW() WHERE code_user = :code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['code' => $code]);
    }

    public function updateLastGoogleUidConnexion(string $code, string $auth_uid): void
    {
        $sql = "UPDATE " . TABLES::USERS . " SET auth_uid = :auth_uid, last_connexion = NOW() WHERE code_user = :code_user";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['code_user' => $code, 'auth_uid' => $auth_uid]);
    }

    public function getUserGroups(string $userCode): array
    {
        $data = [];
        try {
            $sql = "SELECT r.groupe FROM " . TABLES::ROLES . " AS r 
            JOIN " . TABLES::USER_ROLES . " ur ON r.code_role = ur.role_code WHERE ur.user_code = :userCode GROUP BY r.groupe";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['userCode' => $userCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getUserRoles(string $userCode): array
    {
        $data = [];
        try {
            $sql = "SELECT r.code_role, r.libelle_role, r.description, ur.* FROM " . TABLES::ROLES . " AS r 
            JOIN " . TABLES::USER_ROLES . " ur ON r.code_role = ur.role_code WHERE ur.user_code = :userCode GROUP BY r.code_role";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['userCode' => $userCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getUserByCodeWithFoction($codeUser): ?array
    {
        $data = [];
        try {
            $sql = "SELECT us.*, fn.libelle_fonction FROM " . TABLES::USERS . " AS us JOIN " . TABLES::FONCTIONS . " fn ON fn.code_fonction = us.fonction_code 
            WHERE us.etablissement_code = :etablissement_code AND us.code_user = :code LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'code' => $codeUser,
                'etablissement_code' => Auth::user('etablissement_code')
            ]);
            if ($stmt->rowCount() > 0)
                $data = $stmt->fetch();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getUserWithFoction($etat = 1): ?array
    {
        $data = [];
        try {
            $sql = "SELECT us.*, fn.libelle_fonction FROM " . TABLES::USERS . " AS us JOIN " . TABLES::FONCTIONS . " fn ON fn.code_fonction = us.fonction_code AND fn.statut_fonction = :etat 
            WHERE us.etablissement_code = :etablissement_code  ORDER BY us.statut_user DESC, us.nom_user";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'etat' => $etat,
                'etablissement_code' => Auth::user('etablissement_code')
            ]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }



    public function dataTableCountTotalUsersRow(array $whereParams, array $likeParams = [])
    {



        // if (!empty($whereParams)) {
        //     $where = 'WHERE ';
        //     $where .=  implode(
        //         ' AND ',
        //         array_map(fn($f) => "$f = :$f ", array_keys($whereParams))
        //     );
        // }

        $where = "WHERE us.etablissement_code = :etablissement_code";

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


        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::USERS . " us 
            JOIN " . TABLES::FONCTIONS . " fn  ON fn.code_fonction = us.fonction_code  $where";

        $stmt = $this->db->prepare($sql);

        // return $sql;
        $stmt->execute(array_merge($whereParams, $likeParams));
        $data = $stmt->fetch();
        return $data['nb'] ?? 0;
    }


    public function getCommercialsCountTotal(array $whereParams, array $likeParams = [])
    {
        $where = "WHERE us.etablissement_code = :etablissement_code
                  AND co.user_code IS NOT NULL";

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        $sql = "SELECT COUNT(*) AS nb FROM " . TABLES::USERS . " us 
            JOIN " . TABLES::FONCTIONS . " fn ON fn.code_fonction = us.fonction_code
            LEFT JOIN " . TABLES::COMMERCIALS . " co ON co.user_code = us.code_user AND co.statut_commercial = :statut $where";

        $stmt = $this->db->prepare($sql);
        $params = array_merge($whereParams, ['statut' => STATUT_ACTIF], $likeParams);
        $stmt->execute($params);
        $data = $stmt->fetch();
        return $data['nb'] ?? 0;
    }

    public function DataTableFetchUsersListe(array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10)
    {
        $where = "WHERE us.etablissement_code = :etablissement_code";

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        $sql = "SELECT us.*, fn.* FROM " . TABLES::USERS . " us 
        JOIN " . TABLES::FONCTIONS . " fn ON fn.code_fonction = us.fonction_code
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

      public function DataTableFetchCommercialsListe(array $likeParams, string $orderBy, string $orderDir, int $start = 0, int $limit = 10)
    {
        $where = "WHERE us.etablissement_code = :etablissement_code
                  AND co.user_code IS NOT NULL";

        if (!empty($likeParams)) {
            $likes = [];
            foreach ($likeParams as $field => $search) {
                $likes[] = "$field LIKE :$field";
                $likeParams[$field] = "%$search%";
            }
            $where .= " AND (" . implode(' OR ', $likes) . ")";
        }

        $sql = "SELECT us.*, fn.* FROM " . TABLES::USERS . " us 
        LEFT JOIN " . TABLES::FONCTIONS . " fn ON fn.code_fonction = us.fonction_code
        LEFT JOIN " . TABLES::COMMERCIALS . " co ON co.user_code = us.code_user AND co.statut_commercial = :statut
        $where ORDER BY $orderBy $orderDir LIMIT :start, :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":etablissement_code", Auth::user('etablissement_code'));
        $stmt->bindValue(":statut", STATUT_ACTIF);

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
    public function getProfileWithRelations(string $userCode, string $etablissementCode): array
    {
        $data = [];
        try {
            $sql = "SELECT u.*, fn.libelle_fonction, sv.libelle_service, et.libelle_etablissement, et.adresse_etablissement, et.telephone_etablissement
                    FROM " . TABLES::USERS . " u
                    LEFT JOIN " . TABLES::FONCTIONS . " fn ON fn.code_fonction = u.fonction_code
                    LEFT JOIN " . TABLES::SERVICES . " sv ON sv.code_service = u.service_code
                    LEFT JOIN " . TABLES::ETABLISSEMENTS . " et ON et.code_etablissement = u.etablissement_code
                    WHERE u.code_user = :code_user AND u.etablissement_code = :etablissement_code
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['code_user' => $userCode, 'etablissement_code' => $etablissementCode]);
            $data = $stmt->fetch();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getRolesWithPermissions(string $userCode): array
    {
        $data = [];
        try {
            $sql = "SELECT r.code_role, r.libelle_role, r.description, r.module, r.groupe, ur.create_permission, ur.edit_permission, ur.show_permission, ur.delete_permission
                    FROM " . TABLES::ROLES . " r
                    JOIN " . TABLES::USER_ROLES . " ur ON r.code_role = ur.role_code
                    WHERE ur.user_code = :user_code
                    ORDER BY r.groupe, r.libelle_role";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_code' => $userCode]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }


    
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
        return [];
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
                           COUNT(DISTINCT ins.code_inscription) as nb_souscriptions,
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
        return [];
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
            'total_souscriptions' => 0,
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
        return $data;

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
                        COUNT(DISTINCT ins.code_inscription) as total_souscriptions,
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
                $data['total_souscriptions'] = (int) $result['total_souscriptions'];
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
        return $data;

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
