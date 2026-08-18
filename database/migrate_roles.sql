-- Migration des rôles oliveservice
-- À exécuter après avoir sauvegardé la table user_roles existante

-- 1. Sauvegarder les permissions existantes
CREATE TABLE IF NOT EXISTS user_roles_backup AS
SELECT * FROM user_roles;

-- 2. Nettoyer les rôles existants
TRUNCATE TABLE roles;

-- 3. Insérer les nouveaux rôles métier
INSERT INTO roles (libelle_role, code_role, module, groupe, description) VALUES
('Super administrateur', 'super_admin', 'GLOBAL', 'SUPER', 'Accès total à tous les modules'),
('Administration - Paramétrage', 'admin_param', 'ADMIN', 'ADMIN', 'Gestion des paramètres globaux'),
('Administration - Utilisateurs', 'admin_user', 'ADMIN', 'ADMIN', 'Gestion des utilisateurs et rôles'),
('Comptable - Caisse', 'compt_caisse', 'FINANCE', 'COMPTABLE', 'Gestion de la caisse et paiements'),
('Comptable - Dépenses', 'compt_depense', 'FINANCE', 'COMPTABLE', 'Gestion des dépenses'),
('Comptable - Versements', 'compt_versement', 'FINANCE', 'COMPTABLE', 'Suivi des versements commerciaux'),
('Gestionnaire - Validations', 'gest_valid', 'FINANCE', 'GESTION', 'Validation des versements et cautions'),
('Gestionnaire - Distributions', 'gest_distrib', 'CLIENTS', 'GESTION', 'Gestion des distributions articles'),
('Gestionnaire - Cautions', 'gest_caution', 'CLIENTS', 'GESTION', 'Validation des cautions clients'),
('Commercial - Clients', 'comm_client', 'CLIENTS', 'COMMERCIAL', 'Gestion des clients et souscriptions'),
('Commercial - Cautions', 'comm_caution', 'CLIENTS', 'COMMERCIAL', 'Enregistrement des paiements cautions'),
('Commercial - Versements', 'comm_versement', 'FINANCE', 'COMMERCIAL', 'Dépôts de versements au bureau');

-- 4. Réattribuer les permissions aux utilisateurs existants
-- Super admin : tous les rôles
INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, r.code_role, 1, 1, 1, 1
FROM users u
CROSS JOIN roles r
WHERE u.email_user = 'admin@gmail.com';

-- Admin : param + user
INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'admin_param', 1, 1, 1, 1
FROM users u
WHERE u.email_user = 'admin@gmail.com';

INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'admin_user', 1, 1, 1, 1
FROM users u
WHERE u.email_user = 'admin@gmail.com';

-- Comptable
INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'compt_caisse', 1, 1, 1, 0
FROM users u
JOIN user_roles_backup urb ON u.code_user = urb.user_code
WHERE urb.role_code = 'gcom1'
LIMIT 1;

INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'compt_depense', 1, 1, 1, 0
FROM users u
JOIN user_roles_backup urb ON u.code_user = urb.user_code
WHERE urb.role_code = 'gcom1'
LIMIT 1;

INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'compt_versement', 1, 1, 1, 0
FROM users u
JOIN user_roles_backup urb ON u.code_user = urb.user_code
WHERE urb.role_code = 'gcom1'
LIMIT 1;

-- Gestionnaire
INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'gest_valid', 1, 0, 1, 0
FROM users u
JOIN user_roles_backup urb ON u.code_user = urb.user_code
WHERE urb.role_code = 'gh1'
LIMIT 1;

INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'gest_distrib', 1, 1, 1, 0
FROM users u
JOIN user_roles_backup urb ON u.code_user = urb.user_code
WHERE urb.role_code = 'gh1'
LIMIT 1;

INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'gest_caution', 1, 1, 1, 0
FROM users u
JOIN user_roles_backup urb ON u.code_user = urb.user_code
WHERE urb.role_code = 'gh1'
LIMIT 1;

-- Commercial
INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'comm_client', 1, 1, 1, 0
FROM users u
JOIN user_roles_backup urb ON u.code_user = urb.user_code
WHERE urb.role_code = 'grecp1'
LIMIT 1;

INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'comm_caution', 1, 0, 1, 0
FROM users u
JOIN user_roles_backup urb ON u.code_user = urb.user_code
WHERE urb.role_code = 'grecp1'
LIMIT 1;

INSERT INTO user_roles (user_code, role_code, create_permission, edit_permission, show_permission, delete_permission)
SELECT u.code_user, 'comm_versement', 1, 0, 1, 0
FROM users u
JOIN user_roles_backup urb ON u.code_user = urb.user_code
WHERE urb.role_code = 'grecp1'
LIMIT 1;

-- 5. Supprimer la table de sauvegarde
DROP TABLE IF EXISTS user_roles_backup;

-- 6. Vérification
SELECT r.libelle_role, r.code_role, r.groupe, COUNT(ur.user_code) as nb_utilisateurs
FROM roles r
LEFT JOIN user_roles ur ON r.code_role = ur.role_code
GROUP BY r.code_role
ORDER BY r.groupe, r.libelle_role;
