<?php

use App\Core\Auth;
use App\Models\UserModel;

// Script de migration et d'initialisation des rôles
// À exécuter une fois après avoir mis à jour la structure

require_once __DIR__ . '/vendor/autoload.php';

// Charger les constantes
require_once __DIR__ . '/app/configs/const.php';

// Initialiser la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Créer une instance du modèle UserModel
$userModel = new UserModel();

// Récupérer tous les utilisateurs
$users = $userModel->raw("SELECT * FROM users WHERE statut_user = 'actif'");

echo "<h2>Migration des rôles et permissions</h2>";
echo "<p>Nombre d'utilisateurs actifs : " . count($users) . "</p>";

foreach ($users as $user) {
    echo "<hr>";
    echo "<h3>Utilisateur : " . htmlspecialchars($user['nom_user'] . ' ' . $user['prenom_user']) . "</h3>";
    echo "<p>Email : " . htmlspecialchars($user['email_user']) . "</p>";
    
    // Récupérer les groupes de l'utilisateur
    $groupes = $userModel->getUserGroups($user['code_user']);
    $groupeCodes = array_column($groupes, 'groupe');
    
    echo "<p>Groupes actuels : " . implode(', ', $groupeCodes) . "</p>";
    
    // Déterminer le rôle métier selon les groupes
    $roleMetier = 'COMMERCIAL'; // Par défaut
    
    if (in_array('SUPER', $groupeCodes) || in_array('GADMIN', $groupeCodes)) {
        $roleMetier = 'SUPER_ADMIN';
    } elseif (in_array('GADMIN', $groupeCodes)) {
        $roleMetier = 'ADMIN';
    } elseif (in_array('GCOMPT', $groupeCodes)) {
        $roleMetier = 'COMPTABLE';
    } elseif (in_array('GHOT', $groupeCodes)) {
        $roleMetier = 'GESTIONNAIRE';
    } elseif (in_array('GRECP', $groupeCodes)) {
        $roleMetier = 'COMMERCIAL';
    }
    
    echo "<p>Rôle métier détecté : <strong>$roleMetier</strong></p>";
    
    // Supprimer les anciennes permissions
    $userModel->deletePermission($user['code_user'], 'super_admin');
    $userModel->deletePermission($user['code_user'], 'admin_param');
    $userModel->deletePermission($user['code_user'], 'admin_user');
    $userModel->deletePermission($user['code_user'], 'compt_caisse');
    $userModel->deletePermission($user['code_user'], 'compt_depense');
    $userModel->deletePermission($user['code_user'], 'compt_versement');
    $userModel->deletePermission($user['code_user'], 'gest_valid');
    $userModel->deletePermission($user['code_user'], 'gest_distrib');
    $userModel->deletePermission($user['code_user'], 'gest_caution');
    $userModel->deletePermission($user['code_user'], 'comm_client');
    $userModel->deletePermission($user['code_user'], 'comm_caution');
    $userModel->deletePermission($user['code_user'], 'comm_versement');
    
    // Attribuer les nouveaux rôles selon le rôle métier
    switch ($roleMetier) {
        case 'SUPER_ADMIN':
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'super_admin',
                'create_permission' => 1,
                'edit_permission' => 1,
                'show_permission' => 1,
                'delete_permission' => 1
            ]);
            echo "<p style='color:green;'>✓ Rôle super_admin attribué</p>";
            break;
            
        case 'ADMIN':
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'admin_param',
                'create_permission' => 1,
                'edit_permission' => 1,
                'show_permission' => 1,
                'delete_permission' => 1
            ]);
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'admin_user',
                'create_permission' => 1,
                'edit_permission' => 1,
                'show_permission' => 1,
                'delete_permission' => 1
            ]);
            echo "<p style='color:green;'>✓ Rôles admin_param + admin_user attribués</p>";
            break;
            
        case 'COMPTABLE':
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'compt_caisse',
                'create_permission' => 1,
                'edit_permission' => 1,
                'show_permission' => 1,
                'delete_permission' => 0
            ]);
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'compt_depense',
                'create_permission' => 1,
                'edit_permission' => 1,
                'show_permission' => 1,
                'delete_permission' => 0
            ]);
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'compt_versement',
                'create_permission' => 1,
                'edit_permission' => 1,
                'show_permission' => 1,
                'delete_permission' => 0
            ]);
            echo "<p style='color:green;'>✓ Rôles comptable attribués</p>";
            break;
            
        case 'GESTIONNAIRE':
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'gest_valid',
                'create_permission' => 1,
                'edit_permission' => 0,
                'show_permission' => 1,
                'delete_permission' => 0
            ]);
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'gest_distrib',
                'create_permission' => 1,
                'edit_permission' => 1,
                'show_permission' => 1,
                'delete_permission' => 0
            ]);
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'gest_caution',
                'create_permission' => 1,
                'edit_permission' => 1,
                'show_permission' => 1,
                'delete_permission' => 0
            ]);
            echo "<p style='color:green;'>✓ Rôles gestionnaire attribués</p>";
            break;
            
        case 'COMMERCIAL':
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'comm_client',
                'create_permission' => 1,
                'edit_permission' => 1,
                'show_permission' => 1,
                'delete_permission' => 0
            ]);
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'comm_caution',
                'create_permission' => 1,
                'edit_permission' => 0,
                'show_permission' => 1,
                'delete_permission' => 0
            ]);
            $userModel->createPermission([
                'user_code' => $user['code_user'],
                'role_code' => 'comm_versement',
                'create_permission' => 1,
                'edit_permission' => 0,
                'show_permission' => 1,
                'delete_permission' => 0
            ]);
            echo "<p style='color:green;'>✓ Rôles commercial attribués</p>";
            break;
    }
}

echo "<hr>";
echo "<h2>Migration terminée !</h2>";
echo "<p>Actualisez la page pour voir les changements dans la sidebar.</p>";
