

<?= breakcrumb($title ?? "Tableau de bord commercial", 'fa-chart-line'); ?>

<!-- <header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-chart-line mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Tableau de bord commercial</h4>
            <small>Vue d'ensemble de votre activité</small>
        </div>
    </div>
</header> -->

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
                        <h5 class="montan-value"><?= number_format($totalClients) ?></h5>
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
                        <h5 class="montan-value"><?= number_format($totalSouscriptions) ?></h5>
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
                        <h5 class="montan-value"><?= number_format($totalPacks) ?></h5>
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
                        <h5 class="montan-value"><?= number_format($montantPacks, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Versements validés</h6>
                        <h5 class="montan-value"><?= number_format($versementsValides, 0, ',', ' ') ?> FCFA</h5>
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
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Versements en attente</h6>
                        <h5 class="montan-value"><?= number_format($versementsEnAttente, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-purple mr-2">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Cautions validées</h6>
                        <h5 class="montan-value"><?= number_format($cautionsValidees, 0, ',', ' ') ?> FCFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PERFORMANCE -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Taux validation versements</h6>
                        <h5 class="montan-value"><?= number_format($tauxValidationVersements, 2) ?>%</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <h6 class="montan-title">Taux validation cautions</h6>
                        <h5 class="montan-value"><?= number_format($tauxValidationCautions, 2) ?>%</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CLIENTS RECENTS -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title">Clients récents (30 derniers jours)</div>
                    <a href="<?= url('clients') ?>" class="btn btn-primary btn-sm">Voir tous les clients</a>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($clients)): ?>
                    <p class="text-muted text-center py-4">Aucun client trouvé.</p>
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
                                <?php foreach (array_slice($clients, 0, 10) as $i => $client): ?>
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
