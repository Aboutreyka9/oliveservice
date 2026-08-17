<?php
$user = $user ?? [];
$roles = $roles ?? [];
$commercial = $commercial ?? [];

$activePermissions = 0;
$totalPermissions = 0;
if (!empty($roles)) {
    foreach ($roles as $role) {
        $totalPermissions += 4;
        $activePermissions += ($role['create_permission'] ?? 0) + ($role['edit_permission'] ?? 0) + ($role['show_permission'] ?? 0) + ($role['delete_permission'] ?? 0);
    }
}
?>

<?php if (empty($user)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> Utilisateur non trouvé.
    </div>
<?php else: ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl mr-4">
                        <?php if (!empty($user['photo_user'])): ?>
                            <img src="<?= ASSETS ?>uploads/users/<?= $user['photo_user'] ?>" alt="Photo" class="avatar-img rounded-circle" width="80" height="80">
                        <?php else: ?>
                            <div class="avatar-title bg-primary text-white rounded-circle" style="width:80px;height:80px;font-size:32px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-1"><?= strtoupper(htmlspecialchars($user['nom_user'] . ' ' . $user['prenom_user'])) ?></h3>
                        <p class="text-muted mb-1">
                            <i class="fas fa-barcode mr-1"></i> <?= htmlspecialchars($user['code_user']) ?>
                            <?php if (!empty($user['email_user'])): ?>
                                &nbsp;|&nbsp; <i class="fas fa-envelope mr-1"></i> <?= htmlspecialchars($user['email_user']) ?>
                            <?php endif; ?>
                            <?php if (!empty($user['telephone_user'])): ?>
                                &nbsp;|&nbsp; <i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($user['telephone_user']) ?>
                            <?php endif; ?>
                        </p>
                        <?= checkEtatData($user['statut_user']) ?>
                    </div>
                    <div class="ml-auto">
                        <a href="<?= url('personnel-administratifs') ?>" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Rôles</h6>
                        <h5 class="montan-value"><?= count($roles) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Permissions actives</h6>
                        <h5 class="montan-value"><?= $activePermissions ?>/<?= $totalPermissions ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning mr-2">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Fonction</h6>
                        <h5 class="montan-value" style="font-size: 16px;"><?= htmlspecialchars($user['libelle_fonction'] ?? '-') ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-info mr-2">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Établissement</h6>
                        <h5 class="montan-value" style="font-size: 16px;"><?= htmlspecialchars($user['libelle_etablissement'] ?? '-') ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Informations personnelles</h4>
            </div>
            <div class="card-body">
                <form method="post" action="" id="form_update_user">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nom <strong class="text-danger">*</strong></label>
                            <input type="text" name="nom_user" class="form-control" value="<?= htmlspecialchars($user['nom_user']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Prénoms <strong class="text-danger">*</strong></label>
                            <input type="text" name="prenom_user" class="form-control" value="<?= htmlspecialchars($user['prenom_user']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Email <strong class="text-danger">*</strong></label>
                            <input type="email" name="email_user" class="form-control" value="<?= htmlspecialchars($user['email_user']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Contact <strong class="text-danger">*</strong></label>
                            <input type="text" name="telephone_user" class="form-control telephone" value="<?= htmlspecialchars($user['telephone_user']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Matricule <strong class="text-danger">*</strong></label>
                            <input type="text" name="matricule_user" class="form-control" value="<?= htmlspecialchars($user['matricule_user']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Genre</label>
                            <select name="sexe_user" class="form-control select2">
                                <option value="">--- CHOISIR ---</option>
                                <?php foreach (SEXEP as $genre): ?>
                                    <option <?= selected($user['sexe_user'], $genre) ?> value="<?= $genre ?>"><?= $genre ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Fonction</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['libelle_fonction'] ?? '-') ?>" readonly>
                            <input type="hidden" name="fonction_user" value="<?= htmlspecialchars($user['fonction_code']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Service</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['libelle_service'] ?? '-') ?>" readonly>
                            <input type="hidden" name="service_user" value="<?= htmlspecialchars($user['service_code']) ?>">
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="code_user" value="<?= $user['code_user'] ?>">
                            <input type="hidden" name="action" value="btn_update_user">
                            <input type="hidden" name="csrf_token" value="<?= csrfToken()::token() ?>">
                            <button type="submit" class="btn btn-primary w-100" form="form_update_user" id="btn_update_user">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Rôles & Permissions</h4>
            </div>
            <div class="card-body">
                <?php if (empty($roles)): ?>
                    <p class="text-muted text-center py-4">Aucun rôle attribué à cet utilisateur.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Rôle</th>
                                    <th>Module</th>
                                    <th>Groupe</th>
                                    <th>➕ Ajouter</th>
                                    <th>👁 Voir</th>
                                    <th>✏ Modifier</th>
                                    <th>❌ Supprimer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roles as $i => $role): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($role['libelle_role']) ?></td>
                                        <td><?= htmlspecialchars($role['module']) ?></td>
                                        <td><?= htmlspecialchars($role['groupe']) ?></td>
                                        <td><?= $role['create_permission'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' ?></td>
                                        <td><?= $role['show_permission'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' ?></td>
                                        <td><?= $role['edit_permission'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' ?></td>
                                        <td><?= $role['delete_permission'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($commercial)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h4>Informations commercial</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Code commercial</th>
                                <th>Zone</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><?= htmlspecialchars($commercial['code_commercial']) ?></td>
                                <td><?= htmlspecialchars($commercial['libelle_zone'] ?? '-') ?></td>
                                <td><span class="badge badge-success"><?= htmlspecialchars($commercial['statut_commercial']) ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card custom-card-detail">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon bg-primary mr-2">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h6 class="montan-title">Clients</h6>
                                <h5 class="montan-value">-</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card custom-card-detail">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon bg-success mr-2">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <h6 class="montan-title">Versements</h6>
                                <h5 class="montan-value">-</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4>Informations système</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Information</th>
                                <th>Valeur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Dernière connexion</td>
                                <td><?= !empty($user['last_connexion']) ? date_formater($user['last_connexion'], true, true) : 'Jamais' ?></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Date de création</td>
                                <td><?= date_formater($user['created_at_user'], true) ?></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Date de modification</td>
                                <td><?= !empty($user['updated_at_user']) ? date_formater($user['updated_at_user'], true) : '-' ?></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Établissement</td>
                                <td><?= htmlspecialchars($user['libelle_etablissement'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Adresse établissement</td>
                                <td><?= htmlspecialchars($user['adresse_etablissement'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Téléphone établissement</td>
                                <td><?= htmlspecialchars($user['telephone_etablissement'] ?? '-') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
