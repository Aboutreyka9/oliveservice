<?php
$totals = $totals ?? [];
$activities = $activities ?? [];
$alerts = $alerts ?? [];
?>

<?= breakcrumb($title, 'fa-chart-bar '); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-chart-line mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Tableau de bord</h4>
            <small>Vue globale de l'activité</small>
        </div>
    </div>
</header>

<!-- ALERTES -->
<?php if (!empty($alerts)): ?>
<div class="row mb-4">
    <div class="col-md-12">
        <?php foreach ($alerts as $alert): ?>
            <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                <i class="fas <?= $alert['icon'] ?> mr-2"></i>
                <?= htmlspecialchars($alert['message']) ?>
                <?php if (!empty($alert['link'])): ?>
                    <a href="<?= $alert['link'] ?>" class="alert-link ml-2">Voir</a>
                <?php endif; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- STATS CARDS -->
<div class="row g-3 mb-1">
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">PACKS</span></h6>
                </div>
                <h5 class="montan-value"><?= number_format($totals['total_packs'] ?? 0) ?></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-info mr-2">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">ARTICLES</span></h6>
                </div>
                <h5 class="montan-value"><?= number_format($totals['total_articles'] ?? 0) ?></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">CLIENTS</span></h6>
                </div>
                <h5 class="montan-value"><?= number_format($totals['total_clients'] ?? 0) ?></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning mr-2">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">DÉPENSES</span></h6>
                </div>
                <h5 class="montan-value"><?= number_format($totals['total_depenses'] ?? 0) ?></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-danger mr-2">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">INSCRIPTIONS</span></h6>
                </div>
                <h5 class="montan-value"><?= number_format($totals['total_inscriptions'] ?? 0) ?></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-purple mr-2">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">UTILISATEURS</span></h6>
                </div>
                <h5 class="montan-value"><?= number_format($totals['total_users'] ?? 0) ?></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-orange mr-2">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">CAUTIONS</span></h6>
                </div>
                <h5 class="montan-value"><?= number_format($totals['total_cautions'] ?? 0) ?></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-dark mr-2">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">ZONES</span></h6>
                </div>
                <h5 class="montan-value"><?= number_format($totals['total_zones'] ?? 0) ?></h5>
            </div>
        </div>
    </div>

      <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-filter"></i>
                    </div>
                    <h6><span class="text-muted text-uppercase montan-title">SESSION</span></h6>
                </div>
                <h5 class="montan-value"><?= number_format($totals['total_sessionS'] ?? 0) ?></h5>
            </div>
        </div>
    </div>
</div>

<!-- DERNIÈRES ACTIVITÉS -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title">Dernières activités</div>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($activities)): ?>
                    <p class="text-muted text-center py-4">Aucune activité enregistrée.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Libellé</th>
                                    <th>Utilisateur</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activities as $i => $activity): ?>
                                    <?php
                                        $badgeClass = 'bg-primary';
                                        $statut = $activity['statut'] ?? '';
                                        if (in_array($statut, ['valide', 'actif', 'Confirmee', 'paye'], true)) {
                                            $badgeClass = 'bg-success';
                                        } elseif (in_array($statut, ['annule', 'Annulee', 'ennule'], true)) {
                                            $badgeClass = 'bg-danger';
                                        } elseif (in_array($statut, ['En attente', 'solde'], true)) {
                                            $badgeClass = 'bg-warning';
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><span class="badge badge-info"><?= htmlspecialchars(ucfirst($activity['type'])) ?></span></td>
                                        <td><?= htmlspecialchars($activity['libelle']) ?></td>
                                        <td><?= htmlspecialchars($activity['utilisateur'] ?? '-') ?></td>
                                        <td><?= !empty($activity['date_activite']) ? date_formater($activity['date_activite'], true) : '-' ?></td>
                                        <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($statut) ?></span></td>
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
