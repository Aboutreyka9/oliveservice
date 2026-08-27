<?php
$commercial = $commercial ?? [];
$stats = $stats ?? [];
$performance = $performance ?? [];
$clients = $clients ?? [];
$versements = $versements ?? [];
?>

<?php if (empty($commercial)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> Commercial non trouvé.
    </div>
<?php else: ?>

<?= breakcrumb($title, 'fa-user-tie'); ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl mr-4">
                        <?php if (!empty($commercial['photo_user'])): ?>
                            <img src="<?= ASSETS ?>uploads/users/<?= $commercial['photo_user'] ?>" alt="Photo" class="avatar-img rounded-circle" width="80" height="80">
                        <?php else: ?>
                            <div class="avatar-title bg-primary text-white rounded-circle" style="width:80px;height:80px;font-size:32px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-1"><?= strtoupper(htmlspecialchars($commercial['nom_commercial'] ?? ($commercial['nom_user'] . ' ' . $commercial['prenom_user']))) ?></h3>
                        <p class="text-muted mb-1">
                            <i class="fas fa-barcode mr-1"></i> <?= htmlspecialchars($commercial['code_commercial']) ?>
                            <?php if (!empty($commercial['email_user'])): ?>
                                &nbsp;|&nbsp; <i class="fas fa-envelope mr-1"></i> <?= htmlspecialchars($commercial['email_user']) ?>
                            <?php endif; ?>
                            <?php if (!empty($commercial['telephone_user'])): ?>
                                &nbsp;|&nbsp; <i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($commercial['telephone_user']) ?>
                            <?php endif; ?>
                        </p>
                        <span class="badge badge-success">Commercial actif</span>
                        <?php if (!empty($commercial['libelle_zone'])): ?>
                            <span class="badge badge-info ml-1"><?= htmlspecialchars($commercial['libelle_zone']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="ml-auto">
                        <a href="<?= url('personnel-commercials') ?>" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- STATS CARDS -->
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
                        <h5 class="montan-value"><?= number_format($stats['total_clients'] ?? 0) ?></h5>
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
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Souscriptions</h6>
                        <h5 class="montan-value"><?= number_format($stats['total_souscriptions'] ?? 0) ?></h5>
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
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Packs</h6>
                        <h5 class="montan-value"><?= number_format($stats['total_packs'] ?? 0) ?></h5>
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
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Montant packs</h6>
                        <h5 class="montan-value"><?= number_format($stats['montant_total_packs'] ?? 0, 0, ',', ' ') ?> FCFA</h5>
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
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Versements validés</h6>
                        <h5 class="montan-value"><?= number_format($stats['montant_versements_valides'] ?? 0, 0, ',', ' ') ?> FCFA</h5>
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
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Versements en attente</h6>
                        <h5 class="montan-value"><?= number_format($stats['montant_versements_en_attente'] ?? 0, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-danger mr-2">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Versements rejetés</h6>
                        <h5 class="montan-value"><?= number_format($stats['montant_versements_rejetes'] ?? 0, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-purple mr-2">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Cautions validées</h6>
                        <h5 class="montan-value"><?= number_format($stats['montant_cautions_valides'] ?? 0, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PERFORMANCE -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Taux validation versements</h6>
                        <h5 class="montan-value"><?= number_format($performance['taux_validation_versements'] ?? 0, 2) ?>%</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Taux validation cautions</h6>
                        <h5 class="montan-value"><?= number_format($performance['taux_validation_cautions'] ?? 0, 2) ?>%</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning mr-2">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Montant moyen par client</h6>
                        <h5 class="montan-value"><?= number_format($performance['montant_moyen_par_client'] ?? 0, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CLIENTS -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Clients du commercial</div>
            </div>
            <div class="card-body">
                <?php if (empty($clients)): ?>
                    <p class="text-muted text-center py-4">Aucun client trouvé pour ce commercial.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Code client</th>
                                    <th>Nom client</th>
                                    <th>Contact</th>
                                    <th>Sexe</th>
                                    <th>Lieu résidence</th>
                                    <th>Nb souscriptions</th>
                                    <th>Première souscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $i => $client): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><span class="badge badge-info"><?= htmlspecialchars($client['code_client']) ?></span></td>
                                        <td><?= htmlspecialchars($client['nom_client']) ?></td>
                                        <td><?= htmlspecialchars($client['telephone_client']) ?></td>
                                        <td><?= htmlspecialchars($client['sexe_client']) ?></td>
                                        <td><?= htmlspecialchars($client['lieu_residence_client'] ?? '-') ?></td>
                                        <td><?= number_format($client['nb_souscriptions'] ?? 0) ?></td>
                                        <td><?= !empty($client['premiere_souscription']) ? date_formater($client['premiere_souscription'], true) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
