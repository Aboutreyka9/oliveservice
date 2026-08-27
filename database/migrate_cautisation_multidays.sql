-- Migration pour ajouter les champs multi-jours dans cautisation_clients
ALTER TABLE `cautisation_clients`
  ADD COLUMN `nombre_jours_cautisation` INT DEFAULT NULL AFTER `montant_cautisation_client`,
  ADD COLUMN `montant_journalier_cautisation` INT DEFAULT NULL AFTER `nombre_jours_cautisation`,
  ADD COLUMN `periode_debut_cautisation` DATE DEFAULT NULL AFTER `montant_journalier_cautisation`,
  ADD COLUMN `periode_fin_cautisation` DATE DEFAULT NULL AFTER `periode_debut_cautisation`,
  ADD COLUMN `mode_calcul_cautisation` ENUM('jours', 'montant') DEFAULT 'jours' AFTER `periode_fin_cautisation`;

-- Index pour améliorer les requêtes par période
ALTER TABLE `cautisation_clients`
  ADD INDEX `idx_periode` (`periode_debut_cautisation`, `periode_fin_cautisation`),
  ADD INDEX `idx_souscription` (`souscription_code`);
