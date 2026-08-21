<?= breakcrumb($title, 'fa-chart-bar'); ?>

<header class="page-title-bar">
    <div class="header-dashboard d-flex align-items-center mb-4">
        <i class="fas fa-chart-bar mr-3 me-3" style="font-size:20px;"></i>
        <div>
            <h4 class="mb-0">Tableau de bord des rapports</h4>
            <small>Vue d'ensemble des statistiques et indicateurs clés</small>
        </div>
    </div>
</header>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary mr-2">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Clients</span></h6>
                </div>
                <h5 class="montan-value"><span id="total_clients"><?= number_format($stats['total_clients'] ?? 0) ?></span></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Souscriptions</span></h6>
                </div>
                <h5 class="montan-value"><span id="total_souscriptions"><?= number_format($stats['total_souscriptions'] ?? 0) ?></span></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning mr-2">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Cautions</span></h6>
                </div>
                <h5 class="montan-value"><span id="total_cautions"><?= number_format($stats['total_cautions'] ?? 0, 0, ',', ' ') ?></span> FCFA</h5>
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
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Versements</span></h6>
                </div>
                <h5 class="montan-value"><span id="total_versements"><?= number_format($stats['total_versements'] ?? 0, 0, ',', ' ') ?></span> FCFA</h5>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-danger mr-2">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Packs vendus</span></h6>
                </div>
                <h5 class="montan-value"><span id="total_packs"><?= number_format($stats['total_packs_vendus'] ?? 0) ?></span></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-secondary mr-2">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Distributions</span></h6>
                </div>
                <h5 class="montan-value"><span id="total_distributions"><?= number_format($stats['total_distributions'] ?? 0) ?></span></h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-dark mr-2">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Dépenses</span></h6>
                </div>
                <h5 class="montan-value"><span id="total_depenses"><?= number_format($stats['total_depenses'] ?? 0, 0, ',', ' ') ?></span> FCFA</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card custom-card-detail">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success mr-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h6 class="montan-title"><span class="text-muted text-uppercase">Souscriptions validées</span></h6>
                </div>
                <h5 class="montan-value"><span id="souscriptions_validees"><?= number_format($stats['souscriptions_validees'] ?? 0) ?></span></h5>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Souscriptions par mois</h5>
            </div>
            <div class="card-body">
                <canvas id="chartInscriptionsByMonth"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Top 5 des packs vendus</h5>
            </div>
            <div class="card-body">
                <canvas id="chartTopPacks"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- TABLES ROW -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Cautions par commercial</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Commercial</th>
                                <th>Nb cautions</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($cautionsByCommercial)): ?>
                                <?php foreach ($cautionsByCommercial as $c): ?>
                                    <tr>
                                        <td><?= ucfirst($c['nom_user']) . ' ' . ucfirst($c['prenom_user']) ?></td>
                                        <td><?= $c['nb_cautions'] ?></td>
                                        <td><?= number_format($c['total_cautions'], 0, ',', ' ') ?> FCFA</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center">Aucune donnée</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Versements par commercial</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Commercial</th>
                                <th>Nb versements</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($versementsByCommercial)): ?>
                                <?php foreach ($versementsByCommercial as $v): ?>
                                    <tr>
                                        <td><?= ucfirst($v['nom_user']) . ' ' . ucfirst($v['prenom_user']) ?></td>
                                        <td><?= $v['nb_versements'] ?></td>
                                        <td><?= number_format($v['total_versements'], 0, ',', ' ') ?> FCFA</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center">Aucune donnée</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Dépenses par type</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($depensesByType)): ?>
                                <?php foreach ($depensesByType as $d): ?>
                                    <tr>
                                        <td><?= $d['libelle_type_depense'] ?></td>
                                        <td><?= number_format($d['total'], 0, ',', ' ') ?> FCFA</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="text-center">Aucune donnée</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Clients par zone</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Zone</th>
                                <th>Nb clients</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clientsByZone)): ?>
                                <?php foreach ($clientsByZone as $c): ?>
                                    <tr>
                                        <td><?= $c['libelle_zone'] ?></td>
                                        <td><?= $c['nb_clients'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="text-center">Aucune donnée</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart souscriptions par mois
    const ctxInscriptions = document.getElementById('chartInscriptionsByMonth');
    if (ctxInscriptions) {
        const souscriptionsData = <?= json_encode($souscriptionsByMonth) ?>;
        const labels = souscriptionsData.map(item => 'Mois ' + item.mois);
        const data = souscriptionsData.map(item => item.total);

        new Chart(ctxInscriptions, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Souscriptions',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Chart top packs
    const ctxTopPacks = document.getElementById('chartTopPacks');
    if (ctxTopPacks) {
        const topPacksData = <?= json_encode($topPacks) ?>;
        const labels = topPacksData.map(item => item.libelle_pack);
        const data = topPacksData.map(item => item.nb_ventes);

        new Chart(ctxTopPacks, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
