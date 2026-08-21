<?php

namespace App\Models;

use App\Core\Auth;
use App\Core\Model;
use Exception;
use PDO;
use TABLES;

class ReportModel extends Model
{
    public function getDashboardStats(): array
    {
        $data = [];
        try {
            $etablissement = Auth::user('etablissement_code');
            $annee = Auth::user('annee_code');

            // Total clients
            $stmt = $this->db->prepare("SELECT COUNT(*) as nb FROM " . TABLES::CLIENTS . " WHERE etablissement_code = :etablissement");
            $stmt->execute(['etablissement' => $etablissement]);
            $data['total_clients'] = $stmt->fetch()['nb'] ?? 0;

            // Total souscriptions
            $stmt = $this->db->prepare("SELECT COUNT(*) as nb FROM " . TABLES::INSCRIPTIONS . " WHERE etablissement_code = :etablissement AND annee_code = :annee");
            $stmt->execute(['etablissement' => $etablissement, 'annee' => $annee]);
            $data['total_souscriptions'] = $stmt->fetch()['nb'] ?? 0;

            // Total souscriptions valides
            $stmt = $this->db->prepare("SELECT COUNT(*) as nb FROM " . TABLES::INSCRIPTIONS . " WHERE etablissement_code = :etablissement AND annee_code = :annee AND statut_inscription = 'valide'");
            $stmt->execute(['etablissement' => $etablissement, 'annee' => $annee]);
            $data['souscriptions_validees'] = $stmt->fetch()['nb'] ?? 0;

            // Total souscriptions en attente
            $stmt = $this->db->prepare("SELECT COUNT(*) as nb FROM " . TABLES::INSCRIPTIONS . " WHERE etablissement_code = :etablissement AND annee_code = :annee AND statut_inscription = 'solde'");
            $stmt->execute(['etablissement' => $etablissement, 'annee' => $annee]);
            $data['souscriptions_en_attente'] = $stmt->fetch()['nb'] ?? 0;

            // Total packs vendus
            $stmt = $this->db->prepare("SELECT COUNT(*) as nb FROM " . TABLES::PACK_INSCRIPTIONS . " pi JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = pi.inscription_code WHERE ins.etablissement_code = :etablissement AND ins.annee_code = :annee");
            $stmt->execute(['etablissement' => $etablissement, 'annee' => $annee]);
            $data['total_packs_vendus'] = $stmt->fetch()['nb'] ?? 0;

            // Total cautions
            $stmt = $this->db->prepare("SELECT SUM(montant_cautisation_client) as total FROM " . TABLES::CAUTISATION_CLIENTS . " c JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code WHERE ins.etablissement_code = :etablissement AND ins.annee_code = :annee AND c.statut_cautisation_client = 'valide'");
            $stmt->execute(['etablissement' => $etablissement, 'annee' => $annee]);
            $data['total_cautions'] = $stmt->fetch()['total'] ?? 0;

            // Total versements commerciaux
            $stmt = $this->db->prepare("SELECT SUM(montant_versement) as total FROM " . TABLES::VERSEMENTS_COMMERCIAUX . " WHERE etablissement_code = :etablissement AND statut_versement = 'valide'");
            $stmt->execute(['etablissement' => $etablissement]);
            $data['total_versements'] = $stmt->fetch()['total'] ?? 0;

            // Total dépenses
            $stmt = $this->db->prepare("SELECT SUM(montant_depense) as total FROM " . TABLES::DEPENSES . " WHERE etablissement_code = :etablissement AND annee_code = :annee AND statut_depense = 'Confirmee'");
            $stmt->execute(['etablissement' => $etablissement, 'annee' => $annee]);
            $data['total_depenses'] = $stmt->fetch()['total'] ?? 0;

            // Total distributions
            $stmt = $this->db->prepare("SELECT COUNT(*) as nb FROM " . TABLES::DISTRIBUTIONS . " d JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = d.inscription_code WHERE ins.etablissement_code = :etablissement AND ins.annee_code = :annee AND d.statut_distribution = 'valide'");
            $stmt->execute(['etablissement' => $etablissement, 'annee' => $annee]);
            $data['total_distributions'] = $stmt->fetch()['nb'] ?? 0;

        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getInscriptionsByMonth(string $anneeCode): array
    {
        $data = [];
        try {
            $sql = "SELECT MONTH(created_at_inscription) as mois, COUNT(*) as total 
                    FROM " . TABLES::INSCRIPTIONS . " 
                    WHERE annee_code = :annee AND etablissement_code = :etablissement 
                    GROUP BY MONTH(created_at_inscription) 
                    ORDER BY mois";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['annee' => $anneeCode, 'etablissement' => Auth::user('etablissement_code')]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getTopPacks(int $limit = 5): array
    {
        $data = [];
        try {
            $sql = "SELECT p.libelle_pack, p.montant_pack, COUNT(pi.id_pack_inscription) as nb_ventes, SUM(p.montant_pack) as total_ventes 
                    FROM " . TABLES::PACK_INSCRIPTIONS . " pi 
                    JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code 
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = pi.inscription_code 
                    WHERE ins.etablissement_code = :etablissement AND ins.annee_code = :annee 
                    GROUP BY pi.pack_code 
                    ORDER BY nb_ventes DESC 
                    LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':etablissement', Auth::user('etablissement_code'));
            $stmt->bindValue(':annee', Auth::user('annee_code'));
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getCautionsByCommercial(): array
    {
        $data = [];
        try {
            $sql = "SELECT u.nom_user, u.prenom_user, SUM(c.montant_cautisation_client) as total_cautions, COUNT(c.id_cautisation_client) as nb_cautions 
                    FROM " . TABLES::CAUTISATION_CLIENTS . " c 
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = c.inscription_code 
                    JOIN " . TABLES::USERS . " u ON u.code_user = ins.user_code 
                    WHERE ins.etablissement_code = :etablissement AND ins.annee_code = :annee 
                    GROUP BY ins.user_code 
                    ORDER BY total_cautions DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement' => Auth::user('etablissement_code'), 'annee' => Auth::user('annee_code')]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getVersementsByCommercial(): array
    {
        $data = [];
        try {
            $sql = "SELECT u.nom_user, u.prenom_user, SUM(v.montant_versement) as total_versements, COUNT(v.id_versement) as nb_versements 
                    FROM " . TABLES::VERSEMENTS_COMMERCIAUX . " v 
                    JOIN " . TABLES::USERS . " u ON u.code_user = v.commercial_code 
                    WHERE v.etablissement_code = :etablissement AND v.statut_versement = 'valide' 
                    GROUP BY v.commercial_code 
                    ORDER BY total_versements DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement' => Auth::user('etablissement_code')]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getDistributionsByPack(): array
    {
        $data = [];
        try {
            $sql = "SELECT p.libelle_pack, COUNT(d.id_distribution) as nb_distributions 
                    FROM " . TABLES::DISTRIBUTIONS . " d 
                    JOIN " . TABLES::INSCRIPTIONS . " ins ON ins.code_inscription = d.inscription_code 
                    JOIN " . TABLES::PACK_INSCRIPTIONS . " pi ON pi.inscription_code = ins.code_inscription 
                    JOIN " . TABLES::PACKS . " p ON p.code_pack = pi.pack_code 
                    WHERE ins.etablissement_code = :etablissement AND ins.annee_code = :annee AND d.statut_distribution = 'valide' 
                    GROUP BY pi.pack_code 
                    ORDER BY nb_distributions DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement' => Auth::user('etablissement_code'), 'annee' => Auth::user('annee_code')]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getDepensesByType(): array
    {
        $data = [];
        try {
            $sql = "SELECT td.libelle_type_depense, SUM(d.montant_depense) as total 
                    FROM " . TABLES::DEPENSES . " d 
                    JOIN " . TABLES::TYPE_DEPENSES . " td ON td.code_type_depense = d.type_depense_code 
                    WHERE d.etablissement_code = :etablissement AND d.annee_code = :annee AND d.statut_depense = 'Confirmee' 
                    GROUP BY d.type_depense_code 
                    ORDER BY total DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement' => Auth::user('etablissement_code'), 'annee' => Auth::user('annee_code')]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }

    public function getClientsByZone(): array
    {
        $data = [];
        try {
            $sql = "SELECT z.libelle_zone, COUNT(c.id_client) as nb_clients 
                    FROM " . TABLES::CLIENTS . " c 
                    JOIN " . TABLES::ZONES . " z ON z.code_zone = c.zone_code 
                    WHERE c.etablissement_code = :etablissement 
                    GROUP BY c.zone_code 
                    ORDER BY nb_clients DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['etablissement' => Auth::user('etablissement_code')]);
            $data = $stmt->fetchAll();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $data;
    }
}
